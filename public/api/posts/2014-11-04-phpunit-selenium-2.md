---
id: 2014-11-04-phpunit-selenium-2
title: PHPunit and Selenium Server 2
summary: This is the missing manual for phpunit-selenium.
draft: false
public: true
published: 2014-11-04T10:00:00+01:00
modified: 2015-02-21T12:42:00+01:00
tags:
    - PHPUnit
    - Selenium
---

Some functions that are available for selenium.

## Setup

```php
// Set the browser that Selenium will launch
$this->setBrowser(String $browser);

// Set the base URL. All paths in $this->url() calls are relative to this.
$this->setBrowserUrl(String $url);

// Set the hostname of the Selenium Server
$this->setHost(String $host);

// Set the port of the Selenium Server
$this->setPort(int $port);
```

## Selecting items

```php
// Select the element by the given id attribute
$element = $this->byId(String $id);

// Select the element by the given name attribute
$element = $this->byName(String $name);

// Select the element by the given class name
$element = $this->byClassName(String $className);

// Select the element based on the CSS selector
// Use # for ids, . for classes or the element names like form
$element = $this->byCssSelector(String $selector);

// Select the element by the given XPath pattern
$element = $this->byXPath(String $xpath)

// Select the anchor element by the given name text
$element = $this->byLinkText(String $linkText)
```

## Interacting with items

```php
// Move to an element
$this->moveto($element);

// Click on the element
$element->click();

// Clear the value of the element
$element->clear();

// Return the value of the element
$element->value();

// Set the value of the element
$element->value($value);

// Return the text of the element
$element->text();

// Submit the element
$element->submit();
```

## Selectbox

For some reason the select box works a bit different on linux and windows. To get this working on both use this to select a value:

```php
$this->select($this->byName('locale'))->selectOptionByValue('en_US');
```

## Resources

- [WebDriver Wire Protocol](https://code.google.com/p/selenium/wiki/JsonWireProtocol)
- [Selenium 2 Test Case](https://github.com/giorgiosironi/phpunit-selenium/blob/master/Tests/Selenium2TestCaseTest.php)
