<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210704082854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_sale_detail (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', reservation_package_id VARCHAR(255) DEFAULT NULL, general_notes LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bkg_reservation ADD sale_detail_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE bkg_reservation ADD CONSTRAINT FK_EAAEA293EECD3DA1 FOREIGN KEY (sale_detail_id) REFERENCES reservation_sale_detail (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EAAEA293EECD3DA1 ON bkg_reservation (sale_detail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bkg_reservation DROP FOREIGN KEY FK_EAAEA293EECD3DA1');
        $this->addSql('DROP TABLE reservation_sale_detail');
        $this->addSql('DROP INDEX UNIQ_EAAEA293EECD3DA1 ON bkg_reservation');
        $this->addSql('ALTER TABLE bkg_reservation DROP sale_detail_id');
    }
}
