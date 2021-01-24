<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120081020 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, manager_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, country_origin VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, popularity VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, special_notes VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, att1 VARCHAR(255) DEFAULT NULL, att2 VARCHAR(255) DEFAULT NULL, INDEX IDX_64C19C1783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, categ_id INT NOT NULL, manager_id INT NOT NULL, name VARCHAR(255) NOT NULL, short_description VARCHAR(255) NOT NULL, long_description LONGTEXT NOT NULL, height DOUBLE PRECISION NOT NULL, width DOUBLE PRECISION NOT NULL, color VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, price INT NOT NULL, quality VARCHAR(255) NOT NULL, tax DOUBLE PRECISION NOT NULL, delivery_charges INT NOT NULL, dicount INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04ADE8175B12 (categ_id), INDEX IDX_D34A04AD783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE8175B12 FOREIGN KEY (categ_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE8175B12');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE product');
    }
}
