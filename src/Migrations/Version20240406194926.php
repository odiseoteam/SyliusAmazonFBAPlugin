<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240406194926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE odiseo_amazon_fba_configuration (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, sandbox TINYINT(1) NOT NULL, client_id VARCHAR(255) NOT NULL, client_secret VARCHAR(255) NOT NULL, refresh_token VARCHAR(500) NOT NULL, country_code VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_3214908D77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_shipment ADD earliest_arrival_date DATETIME DEFAULT NULL, ADD latest_arrival_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE odiseo_amazon_fba_configuration');
        $this->addSql('ALTER TABLE sylius_shipment DROP earliest_arrival_date, DROP latest_arrival_date');
    }
}
