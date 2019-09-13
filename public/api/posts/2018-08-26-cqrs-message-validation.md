---
id: 2018-08-26-cqrs-message-validation
title: CQRS Message Validation
summary: Validating CQRS command/event/query messages with Assert, Symfony validator and zend-inputfilter.
draft: false
public: true
published: 2018-08-26T20:17:00+00:00
modified: 2018-08-26T20:25:00+00:00
tags:
    - cqrs
    - validation
---

Bare with me, I'm just starting with CQRS. I've got an application running with DDD, CQRS and even Event Sourcing. I think I did a good job because everything seems to be working. The write and read model are separated as it is supposed to be. The event store is working and my read models are populated when new events are received. I'll probably write something about this later but I want to have some more experience first.

## Message Validation

Now the first question I had is where do I validate the input when using CQRS? There are several places where you can do this: [Commands, Models and Forms](http://verraes.net/2015/02/form-command-model-validation/). I've got already form validation in place, but I want to reuse the commands in the api where no form validation is happening. After asking around, command message validation in the constructor is a common solution.

So let's have a look at the different solutions I tried. The goal is to generate an array of error messages which I can reuse in exceptions, json responses and linking the messages to the fields in forms. It should look like this:

```php
[
    'id' => [
        'This value can not be empty',
        'Invalid UUID format',
    ],
    'email' => [
        'This field is required',
        'Invalid email format',
    ]
]
```

## Assert

This is the first solution I used for validation. It's pretty straight forward and if you ever wrote tests with PHPUnit this should look familiar.

```bash
$ composer require beberlei/assert
```

```php
<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use Assert\Assert;

class ChangeUserEmail
{
    /** @var array */
    private $payload;

    public function __construct(array $payload)
    {
        Assert::that($payload['id'])->uuid()->notEmpty();
        Assert::that($payload['email'])->email()->notEmpty();

        $this->payload = $payload;
    }

    public function id() : string
    {
        return $this->payload['id'];
    }

    public function email() : string
    {
        return $this->payload['email'];
    }
}
```

This is a command that is used to change a user email address. It accepts a payload array which should contain an `id` and `email`.

While this works for validation, the problem I found is that it stops validating once it finds an error. You can chain all the asserts together, but then you end up with 1 exception where all single exceptions are glued together into 1 big message. You would need a regex to grab all single errors and their key names. Also if extra keys and values are added, they aren't stripped from the payload. I don't want extra information, only those two.

## Symfony Validator

What I'm looking for is validation for all values and throw an Exception containing all possible errors for each value in an array. The Symfony validator can help me with generating such an array.

```bash
$ composer require symfony/validator symfony/property-access
```

```php
<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use function count;

class ChangeUserEmail
{
    use MessageValidation;

    /** @var array */
    protected $payload;

    public function __construct(array $payload)
    {
        $this->validate($payload);
        $this->payload = $payload;
    }

    public function id() : string
    {
        return $this->payload['id'];
    }

    public function email() : string
    {
        return $this->payload['email'];
    }

    protected function validationConstrains() : Assert\Collection
    {
        return new Assert\Collection([
            'id'    => [
                new Assert\NotBlank(),
                new Assert\Uuid(),
            ],
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
        ]);
    }
}
```

In here the payload is validated and assigned to it's property. A validationConstrain method is added which is used by the MessageValidation trait.

```php
<?php
declare(strict_types=1);

namespace App\Domain;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use function count;

trait MessageValidation
{
    public function validate(array $payload) : void
    {
        $validator  = Validation::createValidator();
        $violations = $validator->validate($payload, $this->validationConstrains());
        if (count($violations) !== 0) {
            throw new MessageValidationException($violations);
        }
    }

    abstract protected function validationConstrains() : Assert\Collection;
}
```

This is where the actual validation is done. The validator is created and it validates the data against the validation constraints. If violations are found an Exception is thrown with a `ConstraintViolationList` object. As a side effect, if there are extra keys and values in the payload which are not mentioned in the validation constrain, it creates a violation. This is very nice and exactly what I want.

```php
<?php
declare(strict_types=1);

namespace App\Domain;

use DomainException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MessageValidationException extends DomainException
{
    /** @var ConstraintViolationListInterface $violations */
    private $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
        parent::__construct('Message validation failed.');
    }

    /** @return array Array with errors messages */
    public function getMessages() : array
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $messages = [];

        /** @var ConstraintViolation $violation */
        foreach ($this->violations as $violation) {
            $value   = $accessor->getValue($messages, $violation->getPropertyPath()) ?? [];
            $value[] = $violation->getMessage();
            $accessor->setValue($messages, $violation->getPropertyPath(), $value);
        }

        return $messages;
    }
}
```

The validation exception has some logic in it. It's really interesting to var_dump a `ConstraintViolationList`. There is a lot of information in it, which can be helpful for translations. All I need are the messages and the key names to create the array I want. The key names, accessed by `getPropertyPath`, are not the real key names. They are paths and look like this: `[id]`, `[email]`. This is where the property accessor comes in. It translates those paths into array keys and assigns the value to it.

This does exactly what I was looking for however I'm still missing something. I'm missing filters. For example if a name or password has trailing spaces, I don't want those. I want the input to be valid at all times before it's persisted.

## Zend Input Filter

And then there is zend-inputfilter. Don't let the name fool you, it does more then the name tells you. Besides the inputfilter it installs zend-validator and zend-filter. The zend-filter is the extra component that Symfony validator is missing.

```bash
$ composer require zendframework/zend-inputfilter
```

```php
<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use App\Domain\Command;
use Zend\Validator\EmailAddress;
use Zend\Validator\Uuid;

class ChangeUserEmail extends Command
{
    /** @var array */
    protected $payload;

    public function __construct(array $payload)
    {
        $this->payload = $this->validate($payload);
    }

    public function id() : string
    {
        return $this->payload['id'];
    }

    public function email() : string
    {
        return $this->payload['email'];
    }

    protected function inputFilterSpec() : array
    {
        return [
            [
                'name'       => 'id',
                'required'   => true,
                'validators' => [
                    ['name' => Uuid::class],
                ],
            ],
            [
                'name'       => 'email',
                'required'   => true,
                'filters'    => [
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => EmailAddress::class,
                        'options' => [
                            'useDomainCheck' => true,
                            'strict'         => true,
                        ],
                    ],
                ],
            ],
        ];
    }
}
```

In comparison with the Symfony validator the constructor has changed and also the validation specification. The constructor assigns the validated and filtered values to the payload property. Obviously the validation specification changed because the two work differently. I find the Symfony validator spec easier to read and understand, but the zend-inputfilter is much more powerful.

In this example I've added an email `StringTrim` filter. It isn't really needed since an email address can't have spaces in it. However in case the email address would have trailing spaces, they are stripped first and then validated, so it would still pass. And because the filtered values are returned you still have a valid email address in your command message without the trailing spaces.

```php
<?php

declare(strict_types=1);

namespace App\Domain;

use Zend\InputFilter\Factory;

trait MessageValidation
{
    public function validate(array $payload) : array
    {
        $factory     = new Factory();
        $inputFilter = $factory->createInputFilter($this->inputFilterSpec());
        $inputFilter->setData($payload);
        if (! $inputFilter->isValid()) {
            throw new MessageValidationException($inputFilter->getMessages());
        }

        // Return filtered values
        return $inputFilter->getValues();
    }

    abstract protected function inputFilterSpec() : array;
}
```

The InputFilter Factory is used to construct the InputFilter from the specification. The payload is passed as the data and it's validated by checking if it's valid. If the data is not valid an exception is thrown containing all error messages, otherwise the filtered values are returned. The filtered key and value pairs contain only the ones from the specification. Everything else is stripped. This is even a better solution than throwing error messages for the extra data.

```php
<?php

declare(strict_types=1);

namespace App\Domain;

use DomainException;

class MessageValidationException extends DomainException
{
    /** @var array */
    private $violations;

    public function __construct(array $violations)
    {
        $this->violations = $violations;
        parent::__construct('Message validation failed.');
    }

    /**
     * @return array Array with errors messages.
     */
    public function getMessages() : array
    {
        return $this->violations;
    }
}
```

The zend-inputfilter returns the validation messages in an usable format, so a copy of that array is sufficient.

Now I'm happy. It doesn't matter where I create a command, I will always have a valid message before I send it to the message bus.

As said before, this is one part to validate input. I recommend to still validate the submitted form values. If you don't like to work with zend-form or symfony/form, there is another easier solution that you might like: [html-form-validator](https://github.com/xtreamwayz/html-form-validator) :P
