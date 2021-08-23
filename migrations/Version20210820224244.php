<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210820224244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrating Database AvaiBook';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad (id INT NOT NULL, typology VARCHAR(20) NOT NULL, description VARCHAR(255) NOT NULL, house_size INT NOT NULL, garden_size INT DEFAULT NULL, score INT DEFAULT NULL, irrelevant_since DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT NOT NULL, ad_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, quality VARCHAR(2) NOT NULL, INDEX IDX_16DB4F894F34D596 (ad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F894F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F894F34D596');
        $this->addSql('DROP TABLE ad');
        $this->addSql('DROP TABLE picture');
    }
}
