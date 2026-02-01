<?php
namespace OSW3\LocaleTools\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SwitchController extends AbstractController
{
    #[Route('/locale/{locale}', name: 'app_locale')]
    public function switch(
        string $locale,
        Request $request,
        RequestStack $stack,
    ): RedirectResponse {
        $stack->getSession()->set('_locale', $locale);

        $referer = $request->headers->get('referer');
        
        if ($referer) {
            // Extraire le path de l'URL de référence
            $path = parse_url($referer, PHP_URL_PATH) ?? '/';
            
            // Remplacer la locale dans le path (pattern: /XX/...)
            $newPath = preg_replace(
                '#^/[a-z]{2}(/|$)#',
                '/' . $locale . '$1',
                $path
            );
            
            // Si aucune locale n'était présente, l'ajouter
            if ($newPath === $path && !str_starts_with($path, '/' . $locale . '/')) {
                $newPath = '/' . $locale . $path;
            }
            
            // Préserver query string
            $query = parse_url($referer, PHP_URL_QUERY);
            if ($query) {
                $newPath .= '?' . $query;
            }
            
            // Préserver fragment
            $fragment = parse_url($referer, PHP_URL_FRAGMENT);
            if ($fragment) {
                $newPath .= '#' . $fragment;
            }
            
            return new RedirectResponse($newPath);
        }

        // Fallback : page d'accueil avec la nouvelle locale
        return new RedirectResponse('/' . $locale . '/');
    }
}
