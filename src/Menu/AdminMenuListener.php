<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        /** @var ItemInterface $item */
        $item = $menu->getChild('configuration');
        if (null == $item) {
            $item = $menu;
        }

        $item
            ->addChild('amazonFBA', ['route' => 'odiseo_sylius_amazon_fba_plugin_admin_amazon_fba_configuration_index'])
            ->setLabel('odiseo_sylius_amazon_fba_plugin.menu.admin.amazon_fba')
            ->setLabelAttribute('icon', 'cog')
        ;
    }
}
