# Locale Tools


## Install

```sh
composer require OSW3/symfony-locale-tool
```


## Config 

`config/packages/translations.yaml`

```yaml
framework:
    enabled_locales: ['en', 'nl', 'fr', 'it', 'no']
    default_locale: en
    translator:
        default_path: '%kernel.project_dir%/translations'
        fallbacks:
            - en
            - fr
        providers:
```

`config/routes.yaml`

```yaml
controllers:
    resource: routing.controllers
    prefix: '/{_locale}'
    requirements:
        _locale: 'en|nl|fr|it|no' 

```

## Services

```php 
$localeToolsService->getCurrent() // string
$localeToolsService->getDefault() // string
$localeToolsService->getAvailable() // array[code, name]
```


## Twig 

```twig
{{ locale_current() }}
{{ locale_default() }}
{{ locale_available() }}
```