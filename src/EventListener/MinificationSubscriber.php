<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @package App\Event
 */

class MinificationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -256]
        ];
    }

    public function onKernelResponse($event)
    {
        if (
            $event->getRequestType() != HttpKernelInterface::MAIN_REQUEST
            || $event->getRequest()->get('_route') === 'admin' // don't apply on admin pages
        ) {
            return;
        }
        $response = $event->getResponse();
        $buffer = $response->getContent();

        $replace = [
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            "/<\?php/" => '<?php ',
            "/\n([\S])/" => '$1',
            "/\r/" => '',
            "/\n/" => '',
            "/\t/" => '',
            '/ +/' => ' ',
        ];

        if (false !== strpos($buffer, '<pre>')) {
            $replace = [
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\r/" => '',
                "/>\n</" => '><',
                "/>\s+\n</" => '><',
                "/>\n\s+</" => '><',
            ];
        }

        $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);

        $response->setContent($buffer);
    }
}
