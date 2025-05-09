<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509022541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE players CHANGE strength strength SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE players CHANGE velocity velocity SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE players CHANGE reaction reaction SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE players CHANGE strength strength SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE players CHANGE velocity velocity SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE players CHANGE reaction reaction SMALLINT NOT NULL');
    }
}
