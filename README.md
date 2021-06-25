<p align="center">
    <a href="https://www.adexos.fr/" target="_blank">
        <img src="https://www.adexos.fr/wp-content/themes/adexos/img/adexosLOGO.png" />
    </a>
</p>

<h1 align="center">PostgreSQL Compatibility Plugin</h1>
<p align="center">This plugin provide PostgreSQL compatibility for Sylius</p>
<hr/>
<h3><center><p>⚠️This is a work in progress plugin</p></center><h3>

## Documentation

1. Run `composer require adexos/sylius-postgre-compatibility-plugin`.

2. Add PostgreCompatibilityPlugin to config/bundles.php.

```php
    return [
        Adexos\SyliusPostgreCompatibilityPlugin\SyliusPostgreCompatibilityPlugin::class => ['all' => true],
    ];
```
