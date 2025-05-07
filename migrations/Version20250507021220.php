<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507021220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, player1 INT NOT NULL, player2 INT NOT NULL, tournament_id INT NOT NULL, player_win INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_62615BABE9C631A (player1), INDEX IDX_62615BA279532A0 (player2), INDEX IDX_62615BA33D1A3E7 (tournament_id), INDEX IDX_62615BACDEB7A55 (player_win), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE players (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, gender TINYINT(1) NOT NULL, strength INT NOT NULL, velocity INT NOT NULL, reaction INT NOT NULL, age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tournaments (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date INT NOT NULL, gender_tournament TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tournament_players (tournament_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_4D41B8AC33D1A3E7 (tournament_id), INDEX IDX_4D41B8AC99E6F5DF (player_id), PRIMARY KEY(tournament_id, player_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches ADD CONSTRAINT FK_62615BABE9C631A FOREIGN KEY (player1) REFERENCES players (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches ADD CONSTRAINT FK_62615BA279532A0 FOREIGN KEY (player2) REFERENCES players (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches ADD CONSTRAINT FK_62615BA33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches ADD CONSTRAINT FK_62615BACDEB7A55 FOREIGN KEY (player_win) REFERENCES players (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tournament_players ADD CONSTRAINT FK_4D41B8AC33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tournament_players ADD CONSTRAINT FK_4D41B8AC99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE matches DROP FOREIGN KEY FK_62615BABE9C631A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches DROP FOREIGN KEY FK_62615BA279532A0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches DROP FOREIGN KEY FK_62615BA33D1A3E7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matches DROP FOREIGN KEY FK_62615BACDEB7A55
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tournament_players DROP FOREIGN KEY FK_4D41B8AC33D1A3E7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tournament_players DROP FOREIGN KEY FK_4D41B8AC99E6F5DF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE matches
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE players
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tournaments
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tournament_players
        SQL);
    }
}
