<p align="center">
    <a href="https://www.adexos.fr/" target="_blank">
        <img src="https://www.adexos.fr/wp-content/themes/adexos/img/adexosLOGO.png" />
    </a>
</p>

<h1 align="center">PostgreSQL Compatibility Plugin</h1>
<p align="center">This plugin provide PostgreSQL compatibility for Sylius</p>
<hr/>

⚠️ Version of the module follow sylius versioning, be sure to use the correct tag for you're installed version.    
We create a new version each time new Sylius release include a new migration.  
Please read Sylius upgrade guides.


## Documentation

1. Run `composer require adexos/sylius-postgre-compatibility-plugin`.

2. Add PostgreCompatibilityPlugin to config/bundles.php.

```php
    return [
        Adexos\SyliusPostgreCompatibilityPlugin\AdexosSyliusPostgreCompatibilityPlugin::class => ['all' => true],
    ];
```

3. Unregister migrations namespace if needed

config/packages/adexos_sylius_postgre_compatibility_plugin.yaml
```yaml
adexos_sylius_postgre_compatibility_plugin:
  excluded_migration_namespaces:
    - Vendor\Namespace\Migrations
```
Then you can re-generate and apply migration for postgre with this command: 
```php
bin/console doctrine:migration:diff
bin/console doctrine:migration:migrate
```
