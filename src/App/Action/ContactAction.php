<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Session\Http\SessionMiddleware;
use Swift_Mailer;
use Swift_Message;
use Xtreamwayz\HTMLFormValidator\FormFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class ContactAction
{
    private $template;

    private $mailer;

    private $config;

    public function __construct(TemplateRendererInterface $template, Swift_Mailer $mailer, array $config)
    {
        $this->template = $template;
        $this->mailer = $mailer;
        $this->config = $config;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        /* @var \PSR7Session\Session\SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        // Generate csrf token
        if (!$session->get('csrf')) {
            $session->set('csrf', md5(uniqid(rand(), true)));
        }

        // Generate form and inject csrf token
        $form = FormFactory::fromHtml($this->template->render('app::contact-form', [
            'token' => $session->get('csrf')
        ]));

        // Get request data
        $data = $request->getParsedBody() ?: [];

        if ($request->getMethod() !== 'POST') {
            // Display form
            return new HtmlResponse($this->template->render('app::contact', [
                'form' => $form->asString(),
            ]), 200);
        }

        // Validate form
        $validationResult = $form->validate($data);

        if (!$validationResult->isValid()) {
            // Display form and inject error messages and submitted values
            return new HtmlResponse($this->template->render('app::contact', [
                'form' => $form->asString($validationResult),
            ]), 200);
        }

        // Create the message
        $message = Swift_Message::newInstance()
            ->setFrom($this->config['from'])
            ->setReplyTo($data['email'], $data['name'])
            ->setTo($this->config['to'])
            ->setSubject('[xtreamwayz-contact] ' . $data['subject'])
            ->setBody($data['body']);

        if ($this->config['transport']['debug'] !== true) {
            $this->mailer->send($message);
        }

        // Display thank you page
        return new HtmlResponse($this->template->render('app::contact-thank-you'), 200);
    }
}