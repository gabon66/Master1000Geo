<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509001035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE players CHANGE strength strength SMALLINT NOT NULL COMMENT '(DC2Type:skill)', CHANGE velocity velocity SMALLINT NOT NULL COMMENT '(DC2Type:skill)', CHANGE reaction reaction SMALLINT NOT NULL COMMENT '(DC2Type:skill)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE players CHANGE strength strength INT NOT NULL, CHANGE velocity velocity INT NOT NULL, CHANGE reaction reaction INT NOT NULL
        SQL);
    }
}
