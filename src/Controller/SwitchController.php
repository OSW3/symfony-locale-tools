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
            $path = parse_url($referer, PHP_URL_PATH) ?? '/';
            
            $newPath = preg_replace(
                '#^/[a-z]{2}(/|$)#',
                '/' . $locale . '$1',
                $path
            );
            
            if ($newPath === $path && !str_starts_with($path, '/' . $locale . '/')) {
                $newPath = '/' . $locale . $path;
            }
            
            $query = parse_url($referer, PHP_URL_QUERY);
            if ($query) {
                $newPath .= '?' . $query;
            }
            
            $fragment = parse_url($referer, PHP_URL_FRAGMENT);
            if ($fragment) {
                $newPath .= '#' . $fragment;
            }
            
            return new RedirectResponse($newPath);
        }

        return new RedirectResponse('/' . $locale . '/');
    }
}
