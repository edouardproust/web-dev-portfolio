<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415210846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_tool (project_id INT NOT NULL, tool_id INT NOT NULL, INDEX IDX_1962F6C9166D1F9C (project_id), INDEX IDX_1962F6C98F7B22CC (tool_id), PRIMARY KEY(project_id, tool_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_tool ADD CONSTRAINT FK_1962F6C9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tool ADD CONSTRAINT FK_1962F6C98F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE repository repository VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_tool DROP FOREIGN KEY FK_1962F6C98F7B22CC');
        $this->addSql('DROP TABLE project_tool');
        $this->addSql('DROP TABLE tool');
        $this->addSql('ALTER TABLE project CHANGE url url VARCHAR(255) NOT NULL, CHANGE repository repository VARCHAR(255) NOT NULL');
    }
}
