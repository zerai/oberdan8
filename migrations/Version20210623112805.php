<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623112805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bkg_book (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', reservation_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', isbn VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL, volume VARCHAR(255) DEFAULT NULL, INDEX IDX_3B4E7B65B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bkg_reservation (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, classe VARCHAR(255) NOT NULL, registration_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', reservation_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', isbn VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL, volume VARCHAR(255) DEFAULT NULL, INDEX IDX_CBE5A331B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, classe VARCHAR(255) NOT NULL, registration_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bkg_book ADD CONSTRAINT FK_3B4E7B65B83297E7 FOREIGN KEY (reservation_id) REFERENCES bkg_reservation (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bkg_book DROP FOREIGN KEY FK_3B4E7B65B83297E7');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331B83297E7');
        $this->addSql('DROP TABLE bkg_book');
        $this->addSql('DROP TABLE bkg_reservation');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE reservation');
    }
}
