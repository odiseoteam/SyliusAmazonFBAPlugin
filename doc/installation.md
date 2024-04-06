## Installation

1. Run `composer require odiseoteam/sylius-amazon-fba-plugin --no-scripts`

2. Enable the plugin in bundles.php

```php
<?php
// config/bundles.php

return [
    // ...
    Odiseo\SyliusAmazonFBAPlugin\OdiseoSyliusAmazonFBAPlugin::class => ['all' => true],
];
```

3. Import the plugin configurations

```yml
# config/packages/_sylius.yaml
imports:
    # ...
    - { resource: "@OdiseoSyliusAmazonFBAPlugin/Resources/config/config.yaml" }
```

4. Add the admin routes

```yml
# config/routes.yaml
odiseo_sylius_amazon_fba_plugin_admin:
    resource: "@OdiseoSyliusAmazonFBAPlugin/Resources/config/routing/admin.yaml"
    prefix: /admin
```

5. Include traits and override the resources

```php
<?php
// src/Entity/Shipping/Shipment.php

// ...
use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAAwareInterface;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBATrait;
use Sylius\Component\Core\Model\Shipment as BaseShipment;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_shipment")
 */
class Shipment extends BaseShipment implements AmazonFBAAwareInterface
{
    use AmazonFBATrait;

    // ...
}
```

6. Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:migrations:migrate
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```
