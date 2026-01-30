<?php
namespace OSW3\LocaleTools\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;
use OSW3\LocaleTools\Services\LocaleToolsService;

class LocaleExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private LocaleToolsService $localeService
    ){}

    public function getCurrent(): string
    {
        return $this->localeService->getCurrent();
    }

    public function getDefault(): string
    {
        return $this->localeService->getDefault();
    }

    public function getAvailable(): array
    {
        return $this->localeService->getAvailable();
    }
}