<?php

declare(strict_types=1);

namespace App\Handler;

use App\Middleware\ReCaptchaMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Xtreamwayz\HTMLFormValidator\FormFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use function md5;
use function random_bytes;

class ContactHandler implements RequestHandlerInterface
{
    /** @var TemplateRendererInterface */
    private $template;

    /** @var TransportInterface */
    private $mailTransport;

    /** @var LoggerInterface */
    private $logger;

    /** @var array */
    private $config;

    public function __construct(
        TemplateRendererInterface $template,
        TransportInterface $mailTransport,
        LoggerInterface $logger,
        array $config
    ) {
        $this->template      = $template;
        $this->mailTransport = $mailTransport;
        $this->logger        = $logger;
        $this->config        = $config;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /* @var SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        // Generate csrf token
        if (! $session->get('csrf')) {
            $session->set('csrf', md5(random_bytes(32)));
        }

        // Generate form and inject csrf token
        $form = (new FormFactory())->fromHtml($this->template->render('app::contact-form', [
            'token' => $session->get('csrf'),
            'recaptchaSiteKey' => $request->getAttribute(ReCaptchaMiddleware::SITE_KEY),
        ]));

        // Validate form
        $validationResult = $form->validateRequest($request);
        if ($validationResult->isValid()) {
            $session->remove('csrf');
            // Get filter submitted values
            $data = $validationResult->getValues();

            $this->logger->notice('Sending contact mail to {from} <{email}> with subject "{subject}": {body}', $data);

            // Create the message
            $message = new Message();
            $message->setFrom($this->config['from'])
                ->setReplyTo($data['email'], $data['name'])
                ->setTo($this->config['to'])
                ->setSubject('[xtreamwayz-contact] ' . $data['subject'])
                ->setBody($data['body']);

            $this->mailTransport->send($message);

            // Display thank you page
            return new HtmlResponse($this->template->render('app::contact-thank-you'), 200);
        }

        // Display form and inject error messages and submitted values
        return new HtmlResponse($this->template->render('app::contact', [
            'form' => $form->asString($validationResult),
        ]), 200);
    }
}
