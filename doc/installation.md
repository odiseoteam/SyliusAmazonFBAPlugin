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

```php
<?php
// src/Repository/ShippingMethodRepository.php

// ...
use Odiseo\SyliusAmazonFBAPlugin\Repository\ShippingMethodRepositoryInterface;
use Odiseo\SyliusAmazonFBAPlugin\Repository\ShippingMethodRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ShippingMethodRepository as BaseShippingMethodRepository;

class ShippingMethodRepository extends BaseShippingMethodRepository implements ShippingMethodRepositoryInterface
{
    use ShippingMethodRepositoryTrait;

    // ...
}
```

```yml
# config/packages/_sylius.yaml
sylius_shipping:
    resources:
        shipping_method:
            classes:
                repository: App\Repository\ShippingMethodRepository
```

6. Add the environment variables

```yml
ODISEO_AMAZON_FBA_MARKETPLACE_ID=EDITME
```

7. Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:migrations:migrate
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```
