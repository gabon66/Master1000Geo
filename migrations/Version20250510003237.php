<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510003237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE players ADD ability SMALLINT DEFAULT NULL COMMENT '(DC2Type:ability)', CHANGE strength strength SMALLINT DEFAULT NULL COMMENT '(DC2Type:strength)', CHANGE velocity velocity SMALLINT DEFAULT NULL COMMENT '(DC2Type:velocity)', CHANGE reaction reaction SMALLINT DEFAULT NULL COMMENT '(DC2Type:reaction)', CHANGE age age SMALLINT NOT NULL COMMENT '(DC2Type:age)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE players DROP ability, CHANGE strength strength SMALLINT DEFAULT NULL, CHANGE velocity velocity SMALLINT DEFAULT NULL, CHANGE reaction reaction SMALLINT DEFAULT NULL, CHANGE age age INT NOT NULL
        SQL);
    }
}
