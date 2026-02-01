<?php
namespace OSW3\LocaleTools\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use OSW3\LocaleTools\Services\LocaleToolsService;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class LocaleRedirectSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LocaleToolsService $localeService,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 34],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $supportedLocales = array_map(fn($locale) => $locale['code'], $this->localeService->getAvailable());
        $pathInfo = $request->getPathInfo();

        if (preg_match('#^/(' . implode('|', $supportedLocales) . ')/#', $pathInfo)) {
            return;
        }

        if ($pathInfo === '/' || $pathInfo === '') {
            $preferredLocale = $this->getPreferredLocale($request);
            
            $response = new RedirectResponse(
                '/' . $preferredLocale . '/',
                RedirectResponse::HTTP_FOUND
            );
            
            $event->setResponse($response);
        }
    }

    private function getPreferredLocale($request): string
    {

        $supportedLocales = array_map(fn($locale) => $locale['code'], $this->localeService->getAvailable());
        $defaultLocale = $this->localeService->getDefault()['code'];

        $session = $request->hasSession() ? $request->getSession() : null;
        if ($session && $session->has('_locale')) {
            $locale = $session->get('_locale');
            if (in_array($locale, $supportedLocales)) {
                return $locale;
            }
        }

        $preferredLanguage = $request->getPreferredLanguage($supportedLocales);
        if ($preferredLanguage) {
            return $preferredLanguage;
        }

        return $defaultLocale;
    }
}