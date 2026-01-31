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
        RequestStack $stack
    ): RedirectResponse {
        $stack->getSession()->set('_locale', $locale);

        return new RedirectResponse(
            $request->headers->get('referer') ?? '/'
        );
    }
}
