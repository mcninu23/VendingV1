<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930155849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'First data base tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coin (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, value NUMERIC(4, 2) NOT NULL, quantity INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, value NUMERIC(4, 2) NOT NULL, quantity INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO coin (name, value, quantity) VALUES ("5 Cent", 0.05, 50),("10 Cent", 0.10, 40),("25 Cent", 0.25, 40),("1 €", 1.00, 30)');
        $this->addSql('INSERT INTO item (name, value, quantity) VALUES ("Water", 0.65, 30),("Juice", 1.00, 30),("Soda", 1.50, 30)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE coin');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE sale');
    }
}
