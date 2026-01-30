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