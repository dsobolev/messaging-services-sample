<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241223000936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `order` (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                qty INT UNSIGNED NOT NULL,
                amount DECIMAL(15,2) UNSIGNED NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `order`');
    }
}
