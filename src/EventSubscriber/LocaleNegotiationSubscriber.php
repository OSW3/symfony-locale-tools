<?php

namespace OSW3\LocaleTools\EventSubscriber;

use OSW3\LocaleTools\Services\LocaleToolsService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

final class LocaleNegotiationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly LocaleToolsService $localeService,
        // private readonly array $supportedLocales = ['fr', 'en'],
        // private readonly string $defaultLocale = 'en',
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['onKernelRequest', 32]];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $supportedLocales = array_map(fn($locale) => $locale['code'], $this->localeService->getAvailable());
        $defaultLocale = $this->localeService->getDefault()['code'];

        // 1) Si l’URL contient déjà la locale, ne rien faire
        $pathLocale = $request->attributes->get('_locale')
            ?? $this->extractLeadingLocaleFromPath($request->getPathInfo());

        if ($pathLocale && \in_array($pathLocale, $supportedLocales, true)) {
            $request->setLocale($pathLocale);
            return;
        }

        // 2) Négocier la langue (query > cookie > header > défaut)
        $negotiated = $request->query->get('lang')
            ?? $request->cookies->get('lang')
            ?? $request->getPreferredLanguage($supportedLocales)
            ?? $defaultLocale;
        $locale = \in_array($negotiated, $supportedLocales, true) ? $negotiated : $defaultLocale;

        // 3) Rediriger proprement vers l’URL localisée (éviter les boucles et les POST)
        if ($request->isMethodCacheable() && !$pathLocale) {
            $localizedPath = '/' . $locale . rtrim('/' . ltrim($request->getPathInfo(), '/'), '/');
            $response = new RedirectResponse($localizedPath, 302);
            $event->setResponse($response);
            return;
        }

        // 4) À défaut, au moins fixer la locale de la requête
        $request->setLocale($locale);
    }

    private function extractLeadingLocaleFromPath(string $path): ?string
    {
        $segments = array_values(array_filter(explode('/', $path)));
        return $segments[0] ?? null;
    }
}