<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328030802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_option (id INT AUTO_INCREMENT NOT NULL, constant VARCHAR(255) NOT NULL, value LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', label VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_74E3149CB6AD5D8 (constant), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, full_name VARCHAR(255) NOT NULL, bio LONGTEXT NOT NULL, avatar VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, github VARCHAR(255) DEFAULT NULL, stackoverflow VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, is_approved TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_BDAFD8C88D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coding_language (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8E373BFEA750E8 (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, post_id INT DEFAULT NULL, lesson_id INT DEFAULT NULL, created_at DATETIME NOT NULL, content LONGTEXT NOT NULL, full_name VARCHAR(255) NOT NULL, is_visible TINYINT(1) DEFAULT NULL, INDEX IDX_9474526C166D1F9C (project_id), INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CCDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, coding_language_id INT DEFAULT NULL, author INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, video_url VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, repository VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, INDEX IDX_F87474F39552E5E2 (coding_language_id), INDEX IDX_F87474F3BDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson_lesson_category (lesson_id INT NOT NULL, lesson_category_id INT NOT NULL, INDEX IDX_A6FD5744CDF80196 (lesson_id), INDEX IDX_A6FD5744E39F76E9 (lesson_category_id), PRIMARY KEY(lesson_id, lesson_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson_category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4E0B53D8989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, main_image VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, INDEX IDX_5A8A6C8DBDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_post_category (post_id INT NOT NULL, post_category_id INT NOT NULL, INDEX IDX_A6D02E734B89032C (post_id), INDEX IDX_A6D02E73FE0617CD (post_category_id), PRIMARY KEY(post_id, post_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_B9A19060989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, headline VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, main_image VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, repository VARCHAR(255) NOT NULL, featured TINYINT(1) DEFAULT NULL, completed_on DATETIME DEFAULT NULL, gallery LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_2FB3D0EEBDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_project_category (project_id INT NOT NULL, project_category_id INT NOT NULL, INDEX IDX_86875173166D1F9C (project_id), INDEX IDX_86875173DA896A19 (project_category_id), PRIMARY KEY(project_id, project_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_coding_language (project_id INT NOT NULL, coding_language_id INT NOT NULL, INDEX IDX_30D2064F166D1F9C (project_id), INDEX IDX_30D2064F9552E5E2 (coding_language_id), PRIMARY KEY(project_id, coding_language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3B02921A989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_author TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C88D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CCDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F39552E5E2 FOREIGN KEY (coding_language_id) REFERENCES coding_language (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3BDAFD8C8 FOREIGN KEY (author) REFERENCES author (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE lesson_lesson_category ADD CONSTRAINT FK_A6FD5744CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesson_lesson_category ADD CONSTRAINT FK_A6FD5744E39F76E9 FOREIGN KEY (lesson_category_id) REFERENCES lesson_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DBDAFD8C8 FOREIGN KEY (author) REFERENCES author (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE post_post_category ADD CONSTRAINT FK_A6D02E734B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_post_category ADD CONSTRAINT FK_A6D02E73FE0617CD FOREIGN KEY (post_category_id) REFERENCES post_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEBDAFD8C8 FOREIGN KEY (author) REFERENCES author (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE project_project_category ADD CONSTRAINT FK_86875173166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_project_category ADD CONSTRAINT FK_86875173DA896A19 FOREIGN KEY (project_category_id) REFERENCES project_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_coding_language ADD CONSTRAINT FK_30D2064F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_coding_language ADD CONSTRAINT FK_30D2064F9552E5E2 FOREIGN KEY (coding_language_id) REFERENCES coding_language (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3BDAFD8C8');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DBDAFD8C8');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEBDAFD8C8');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F39552E5E2');
        $this->addSql('ALTER TABLE project_coding_language DROP FOREIGN KEY FK_30D2064F9552E5E2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CCDF80196');
        $this->addSql('ALTER TABLE lesson_lesson_category DROP FOREIGN KEY FK_A6FD5744CDF80196');
        $this->addSql('ALTER TABLE lesson_lesson_category DROP FOREIGN KEY FK_A6FD5744E39F76E9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE post_post_category DROP FOREIGN KEY FK_A6D02E734B89032C');
        $this->addSql('ALTER TABLE post_post_category DROP FOREIGN KEY FK_A6D02E73FE0617CD');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C166D1F9C');
        $this->addSql('ALTER TABLE project_project_category DROP FOREIGN KEY FK_86875173166D1F9C');
        $this->addSql('ALTER TABLE project_coding_language DROP FOREIGN KEY FK_30D2064F166D1F9C');
        $this->addSql('ALTER TABLE project_project_category DROP FOREIGN KEY FK_86875173DA896A19');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C88D93D649');
        $this->addSql('DROP TABLE admin_option');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE coding_language');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE lesson_lesson_category');
        $this->addSql('DROP TABLE lesson_category');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_post_category');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_project_category');
        $this->addSql('DROP TABLE project_coding_language');
        $this->addSql('DROP TABLE project_category');
        $this->addSql('DROP TABLE user');
    }
}
