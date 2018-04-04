<?php

declare(strict_types=1);

namespace App\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReCaptcha\ReCaptcha;
use function implode;
use function strtoupper;

/**
 * @see https://developers.google.com/recaptcha/docs/display
 * @see https://developers.google.com/recaptcha/docs/invisible
 */
class ReCaptchaMiddleware implements MiddlewareInterface
{
    public const SITE_KEY = 'RECAPTCHA_SITE_KEY';

    /** @var ReCaptcha */
    private $recaptcha;

    /** @var string */
    private $siteKey;

    public function __construct(ReCaptcha $recaptcha, string $siteKey)
    {
        $this->recaptcha = $recaptcha;
        $this->siteKey   = $siteKey;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $request = $request->withAttribute(self::SITE_KEY, $this->siteKey);

        // Only validate post requests
        if (strtoupper($request->getMethod()) !== 'POST') {
            return $handler->handle($request);
        }

        // Check if there is a valid captcha response
        $params = $request->getParsedBody();
        if (! isset($params['g-recaptcha-response']) || empty($params['g-recaptcha-response'])) {
            throw new Exception('The captcha response is missing in the request', 422);
        }

        // Verify
        $response = $this->recaptcha->verify(
            $params['g-recaptcha-response'],
            $request->getServerParams()['REMOTE_ADDR']
        );

        // Throw exception is the captcha is invalid
        if (! $response->isSuccess()) {
            $errors   = [];
            $errors[] = 'Invalid captcha response.';
            foreach ($response->getErrorCodes() as $error) {
                $errors[] = $error;
            }

            throw new Exception(implode(' ', $errors), 422);
        }

        // Next please!
        return $handler->handle($request);
    }
}
