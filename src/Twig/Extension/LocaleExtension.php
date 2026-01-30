<?php
namespace OSW3\LocaleTools\Twig\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use OSW3\LocaleTools\Twig\Runtime\LocaleExtensionRuntime;

class LocaleExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('locale_current', [LocaleExtensionRuntime::class, 'getCurrent']),
            new TwigFunction('locale_default', [LocaleExtensionRuntime::class, 'getDefault']),
            new TwigFunction('locale_available', [LocaleExtensionRuntime::class, 'getAvailable']),
        ];
    }
}