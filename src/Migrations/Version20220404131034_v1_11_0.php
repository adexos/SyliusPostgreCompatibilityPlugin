<?php

declare(strict_types=1);

namespace Adexos\SyliusPostgreCompatibilityPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404131034_v1_11_0 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sylius_catalog_promotion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_catalog_promotion_action_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_catalog_promotion_scope_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_catalog_promotion_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sylius_catalog_promotion (id INT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, enabled BOOLEAN NOT NULL, priority INT DEFAULT 0 NOT NULL, exclusive BOOLEAN DEFAULT \'false\' NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1055865077153098 ON sylius_catalog_promotion (code)');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_channels (catalog_promotion_id INT NOT NULL, channel_id INT NOT NULL, PRIMARY KEY(catalog_promotion_id, channel_id))');
        $this->addSql('CREATE INDEX IDX_48E9AE7622E2CB5A ON sylius_catalog_promotion_channels (catalog_promotion_id)');
        $this->addSql('CREATE INDEX IDX_48E9AE7672F5A1AA ON sylius_catalog_promotion_channels (channel_id)');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_action (id INT NOT NULL, catalog_promotion_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F529624722E2CB5A ON sylius_catalog_promotion_action (catalog_promotion_id)');
        $this->addSql('COMMENT ON COLUMN sylius_catalog_promotion_action.configuration IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_scope (id INT NOT NULL, promotion_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_584AA86A139DF194 ON sylius_catalog_promotion_scope (promotion_id)');
        $this->addSql('COMMENT ON COLUMN sylius_catalog_promotion_scope.configuration IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_translation (id INT NOT NULL, translatable_id INT NOT NULL, label VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BA065D3C2C2AC5D3 ON sylius_catalog_promotion_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX sylius_catalog_promotion_translation_uniq_trans ON sylius_catalog_promotion_translation (translatable_id, locale)');
        $this->addSql('CREATE TABLE sylius_channel_pricing_catalog_promotions (channel_pricing_id INT NOT NULL, catalog_promotion_id INT NOT NULL, PRIMARY KEY(channel_pricing_id, catalog_promotion_id))');
        $this->addSql('CREATE INDEX IDX_9F52FF513EADFFE5 ON sylius_channel_pricing_catalog_promotions (channel_pricing_id)');
        $this->addSql('CREATE INDEX IDX_9F52FF5122E2CB5A ON sylius_channel_pricing_catalog_promotions (catalog_promotion_id)');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_channels ADD CONSTRAINT FK_48E9AE7622E2CB5A FOREIGN KEY (catalog_promotion_id) REFERENCES sylius_catalog_promotion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_channels ADD CONSTRAINT FK_48E9AE7672F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_action ADD CONSTRAINT FK_F529624722E2CB5A FOREIGN KEY (catalog_promotion_id) REFERENCES sylius_catalog_promotion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_scope ADD CONSTRAINT FK_584AA86A139DF194 FOREIGN KEY (promotion_id) REFERENCES sylius_catalog_promotion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_translation ADD CONSTRAINT FK_BA065D3C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_catalog_promotion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_channel_pricing_catalog_promotions ADD CONSTRAINT FK_9F52FF513EADFFE5 FOREIGN KEY (channel_pricing_id) REFERENCES sylius_channel_pricing (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_channel_pricing_catalog_promotions ADD CONSTRAINT FK_9F52FF5122E2CB5A FOREIGN KEY (catalog_promotion_id) REFERENCES sylius_catalog_promotion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_channel_pricing ADD minimum_price INT DEFAULT 0');
        $this->addSql('CREATE INDEX created_at_index ON sylius_customer (created_at)');
        $this->addSql('ALTER TABLE sylius_order DROP created_by_guest');
        $this->addSql('ALTER TABLE sylius_order_item ADD original_unit_price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_promotion ADD applies_to_discounted BOOLEAN DEFAULT \'true\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_catalog_promotion_channels DROP CONSTRAINT FK_48E9AE7622E2CB5A');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_action DROP CONSTRAINT FK_F529624722E2CB5A');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_scope DROP CONSTRAINT FK_584AA86A139DF194');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_translation DROP CONSTRAINT FK_BA065D3C2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_channel_pricing_catalog_promotions DROP CONSTRAINT FK_9F52FF5122E2CB5A');
        $this->addSql('DROP SEQUENCE sylius_catalog_promotion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_catalog_promotion_action_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_catalog_promotion_scope_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_catalog_promotion_translation_id_seq CASCADE');
        $this->addSql('DROP TABLE sylius_catalog_promotion');
        $this->addSql('DROP TABLE sylius_catalog_promotion_channels');
        $this->addSql('DROP TABLE sylius_catalog_promotion_action');
        $this->addSql('DROP TABLE sylius_catalog_promotion_scope');
        $this->addSql('DROP TABLE sylius_catalog_promotion_translation');
        $this->addSql('DROP TABLE sylius_channel_pricing_catalog_promotions');
        $this->addSql('ALTER TABLE sylius_order ADD created_by_guest BOOLEAN DEFAULT \'true\' NOT NULL');
        $this->addSql('DROP INDEX created_at_index');
        $this->addSql('ALTER TABLE sylius_order_item DROP original_unit_price');
        $this->addSql('ALTER TABLE sylius_channel_pricing DROP minimum_price');
        $this->addSql('ALTER TABLE sylius_promotion DROP applies_to_discounted');
    }
}
