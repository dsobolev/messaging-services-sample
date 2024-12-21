<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241221003643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE income (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                income DECIMAL(30,2) UNSIGNED NOT NULL DEFAULT 0,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE inventory (
                id INT AUTO_INCREMENT NOT NULL,
                qty INT UNSIGNED NOT NULL DEFAULT 0,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE product (
                id UUID NOT NULL,
                inventory_id INT NOT NULL,
                income_id INT NOT NULL,
                price DECIMAL(15,2) UNSIGNED NOT NULL,
                name VARCHAR(30) NOT NULL,
                UNIQUE INDEX UNIQ_D34A04AD9EEA759 (inventory_id),
                UNIQUE INDEX UNIQ_D34A04AD640ED2C0 (income_id),
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD640ED2C0 FOREIGN KEY (income_id) REFERENCES income (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9EEA759');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD640ED2C0');
        $this->addSql('DROP TABLE income');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE product');
    }
}
