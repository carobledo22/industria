<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418123721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario CHANGE usuario usuario CHAR(10) NOT NULL, CHANGE clave clave VARCHAR(100) NOT NULL, CHANGE ayn ayn VARCHAR(50) NOT NULL, CHANGE email email VARCHAR(150) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario CHANGE usuario usuario CHAR(10) CHARACTER SET latin1 NOT NULL COLLATE `latin1_spanish_ci`, CHANGE clave clave VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_spanish_ci`, CHANGE ayn ayn VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_spanish_ci`, CHANGE email email VARCHAR(150) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_spanish_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_spanish_ci`, CHANGE roles roles JSON DEFAULT NULL');
    }
}
