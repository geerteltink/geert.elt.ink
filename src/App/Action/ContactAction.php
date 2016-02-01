<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Xtreamwayz\HTMLFormValidator\FormFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use Swift_Mailer;
use Swift_Message;

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
        $form = FormFactory::fromHtml($this->template->render('app::contact-form'));
        $data = $request->getParsedBody() ?: [];

        if ($request->getMethod() !== 'POST') {
            return new HtmlResponse($this->template->render('app::contact', [
                'form' => $form->asString(),
            ]), 200);
        }

        $validationResult = $form->validate($data);

        if (!$validationResult->isValid()) {
            return new HtmlResponse($this->template->render('app::contact', [
                'form' => $form->asString($validationResult),
            ]), 200);
        }

        // Create the message
        $message = Swift_Message::newInstance()
            ->setFrom($data['email'], $data['name'])
            ->setReplyTo($data['email'], $data['name'])
            ->setTo($this->config['to'])
            ->setSubject($data['body'])
            ->setBody($data['body']);

        if ($this->config['transport']['debug'] !== true) {
            $this->mailer->send($message);
        }

        // Display thank you page
        return new HtmlResponse($this->template->render('app::contact-thank-you'), 200);
    }
}
