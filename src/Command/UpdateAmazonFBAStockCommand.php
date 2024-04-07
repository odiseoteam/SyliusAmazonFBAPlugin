<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Command;

use Doctrine\ORM\EntityManagerInterface;
use Odiseo\SyliusAmazonFBAPlugin\Api\AmazonFBAManager;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class UpdateAmazonFBAStockCommand extends Command
{
    public function __construct(
        private AmazonFBAManager $amazonFBAManager,
        private EntityManagerInterface $productVariantManager,
        private ProductVariantRepositoryInterface $productVariantRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('odiseo:amazon-fba:update-stock')
            ->setDescription('Update Amazon FBA stock')
            ->setHelp('This command allows you to update Amazon FBA stock')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Update Amazon FBA stock');

        $skus = [];

        /** @var array $variants */
        $variants = $this->productVariantRepository->getCodesOfAllVariants();
        foreach ($variants as $variant) {
            $skus[] = $variant['code'];
        }

        if (count($skus) === 0) {
            $io->info('Products not found');

            return Command::SUCCESS;
        }

        $io->section('Updating ' . count($skus) . ' skus');

        $inventorySummaries = $this->amazonFBAManager->getInventorySummaries($skus);

        try {
            foreach ($inventorySummaries as $inventorySummary) {
                $sku = $inventorySummary['sellerSku'] ?? null;
                $quantity = $inventorySummary['inventoryDetails']['fulfillableQuantity'] ?? null;
                if (null === $sku || null === $quantity) {
                    continue;
                }

                $variant = $this->productVariantRepository->findOneBy(['code' => $sku]);
                if (!$variant instanceof ProductVariantInterface) {
                    continue;
                }

                $variant->setOnHand($quantity);

                $this->productVariantManager->flush();

                $io->success(sprintf(
                    'The stock for the sku %s has been updated',
                    $sku,
                ));
            }
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
        }

        return Command::SUCCESS;
    }
}
