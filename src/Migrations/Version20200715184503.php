<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200715184503 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note (
          id INT AUTO_INCREMENT NOT NULL,
          user_id INT DEFAULT NULL,
          title VARCHAR(64) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          updated_at DATETIME NOT NULL,
          body LONGTEXT DEFAULT NULL,
          INDEX IDX_CFBDFA14A76ED395 (user_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE share (
          id INT AUTO_INCREMENT NOT NULL,
          note_id INT DEFAULT NULL,
          user_id INT DEFAULT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          updated_at DATETIME NOT NULL,
          access VARCHAR(64) NOT NULL,
          INDEX IDX_EF069D5A26ED0855 (note_id),
          INDEX IDX_EF069D5AA76ED395 (user_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (
          id INT AUTO_INCREMENT NOT NULL,
          username VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          updated_at DATETIME NOT NULL,
          fullname VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          note
        ADD
          CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE
          share
        ADD
          CONSTRAINT FK_EF069D5A26ED0855 FOREIGN KEY (note_id) REFERENCES note (id)');
        $this->addSql('ALTER TABLE
          share
        ADD
          CONSTRAINT FK_EF069D5AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE share DROP FOREIGN KEY FK_EF069D5A26ED0855');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('ALTER TABLE share DROP FOREIGN KEY FK_EF069D5AA76ED395');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE share');
        $this->addSql('DROP TABLE `user`');
    }
}
