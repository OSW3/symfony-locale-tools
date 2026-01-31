<?php 
namespace OSW3\LocaleTools\Services;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Intl\Locales;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class LocaleToolsService
{
    public function __construct(
        private ParameterBagInterface $params,
        private RequestStack $request
    ){}

    public function getCurrent(): array
    {
        $code = null;

        // Locale in session
        if ($code === null) {
            $code = $this->request->getCurrentRequest()->getSession()->get('_locale');
        }

        // Request Locale (URL or request parameter)
        if ($code === null) {
            $code = $this->request->getCurrentRequest()->getLocale();
        }

        // Preferred Locale (from browser settings)
        if ($code === null) {
            $code = $this->request->getCurrentRequest()->getPreferredLanguage();
        }

        // Default locale
        if ($code === null) {
            $code = $this->getDefault();
        }

        // Fallback locale if no locale is defined
        if ($code === null) {
            $code = 'en';
        }
        
        return [
            'code' => $code, 
            'name' => $this->getName($code)
        ];
    }

    /**
     * Get default locale of translator
     *
     * @return string
     */
    public function getDefault(): array
    {
        $config = Yaml::parseFile($this->params->get('kernel.project_dir').'/config/packages/translation.yaml');
        $code = $config['framework']['default_locale'] ?? 'en';

        return [
            'code' => $code, 
            'name' => $this->getName($code)
            ];
    }

    /**
     * Get available locales from translator
     *
     * @return array
     */
    public function getAvailable(): array
    {
        $config    = Yaml::parseFile($this->params->get('kernel.project_dir').'/config/packages/translation.yaml');
        $available = $config['framework']['enabled_locales'] ?? [];
        $choices   = [];

        foreach ($available as $code) 
        {
            $name = $this->getName($code);

            $choices[] = [
                'code' => $code, 
                'name' => $name,
            ];
        }

        return $choices;
    }

    private function getName(string $code): ?string
    {
        $name = Locales::getName( $code, $code );
        $name = ucfirst($name);
        return $name;
    }
}