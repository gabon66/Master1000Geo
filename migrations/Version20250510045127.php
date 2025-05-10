<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510045127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tournaments ADD winner_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC35DFCD4B8 FOREIGN KEY (winner_id) REFERENCES players (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E4BCFAC35DFCD4B8 ON tournaments (winner_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tournaments DROP FOREIGN KEY FK_E4BCFAC35DFCD4B8
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E4BCFAC35DFCD4B8 ON tournaments
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tournaments DROP winner_id
        SQL);
    }
}
