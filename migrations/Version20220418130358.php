<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418130358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario CHANGE usuario usuario CHAR(10) CHARACTER SET latin1 NOT NULL COLLATE `latin1_bin`, CHANGE clave clave VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_bin`, CHANGE ayn ayn VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_bin`, CHANGE email email VARCHAR(150) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_bin`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_bin`');
    }
}
