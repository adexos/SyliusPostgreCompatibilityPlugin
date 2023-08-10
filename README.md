<p align="center">
    <a href="https://www.adexos.fr/" target="_blank">
        <img src="https://www.adexos.fr/wp-content/themes/adexos/img/adexosLOGO.png" />
    </a>
</p>

<h1 align="center">PostgreSQL Compatibility Plugin</h1>
<p align="center">This plugin provide PostgreSQL compatibility for Sylius</p>
<hr/>

[![Settings Plugin license](https://img.shields.io/github/license/adexos/SyliusPostgreCompatibilityPlugin?public)](https://github.com/adexos/SyliusPostgreCompatibilityPlugin/blob/master/LICENSE)

⚠️ Versions of this module follow Sylius versioning. Be sure to use the tag matching your Sylius version.  
New version will be released for each Sylius release including a migration.  
Please read Sylius upgrading guides.


## Installation

1. Run `composer require adexos/sylius-postgre-compatibility-plugin`.

2. Add PostgreCompatibilityPlugin to config/bundles.php.

```php
    return [
        Adexos\SyliusPostgreCompatibilityPlugin\AdexosSyliusPostgreCompatibilityPlugin::class => ['all' => true],
    ];
```

3. Unregister migrations namespace if needed

In `config/packages/adexos_sylius_postgre_compatibility_plugin.yaml`
```yaml
adexos_sylius_postgre_compatibility_plugin:
  excluded_migration_namespaces:
    - Vendor\Namespace\Migrations
```
Then you can re-generate and apply migrations for PostgreSQL with these commands: 
```php
bin/console doctrine:migration:diff
bin/console doctrine:migration:migrate
```
