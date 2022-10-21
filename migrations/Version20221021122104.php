<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021122104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, full_name VARCHAR(255) NOT NULL, bio LONGTEXT NOT NULL, avatar VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, github VARCHAR(255) DEFAULT NULL, stackoverflow VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, is_approved TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_BDAFD8C88D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coding_language (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8E373BFEA750E8 (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, project INT DEFAULT NULL, post INT DEFAULT NULL, lesson INT DEFAULT NULL, created_at DATETIME NOT NULL, content LONGTEXT NOT NULL, full_name VARCHAR(255) NOT NULL, is_visible TINYINT(1) DEFAULT NULL, INDEX IDX_9474526C2FB3D0EE (project), INDEX IDX_9474526C5A8A6C8D (post), INDEX IDX_9474526CF87474F3 (lesson), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_item (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, item VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, INDEX IDX_8C040D92166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, coding_language_id INT DEFAULT NULL, author INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, headline VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, video_url VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, repository VARCHAR(255) DEFAULT NULL, INDEX IDX_F87474F39552E5E2 (coding_language_id), INDEX IDX_F87474F3BDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson_lesson_category (lesson_id INT NOT NULL, lesson_category_id INT NOT NULL, INDEX IDX_A6FD5744CDF80196 (lesson_id), INDEX IDX_A6FD5744E39F76E9 (lesson_category_id), PRIMARY KEY(lesson_id, lesson_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson_category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4E0B53D8989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, main_image VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, INDEX IDX_5A8A6C8DBDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_post_category (post_id INT NOT NULL, post_category_id INT NOT NULL, INDEX IDX_A6D02E734B89032C (post_id), INDEX IDX_A6D02E73FE0617CD (post_category_id), PRIMARY KEY(post_id, post_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_B9A19060989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, headline VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, thumbnail VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, repository VARCHAR(255) DEFAULT NULL, featured TINYINT(1) DEFAULT NULL, completed_on DATETIME DEFAULT NULL, INDEX IDX_2FB3D0EEBDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_project_category (project_id INT NOT NULL, project_category_id INT NOT NULL, INDEX IDX_86875173166D1F9C (project_id), INDEX IDX_86875173DA896A19 (project_category_id), PRIMARY KEY(project_id, project_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_coding_language (project_id INT NOT NULL, coding_language_id INT NOT NULL, INDEX IDX_30D2064F166D1F9C (project_id), INDEX IDX_30D2064F9552E5E2 (coding_language_id), PRIMARY KEY(project_id, coding_language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_technology (project_id INT NOT NULL, technology_id INT NOT NULL, INDEX IDX_ECC5297F166D1F9C (project_id), INDEX IDX_ECC5297F4235D463 (technology_id), PRIMARY KEY(project_id, technology_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_tool (project_id INT NOT NULL, tool_id INT NOT NULL, INDEX IDX_1962F6C9166D1F9C (project_id), INDEX IDX_1962F6C98F7B22CC (tool_id), PRIMARY KEY(project_id, tool_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_category (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3B02921A989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_author TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C88D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C2FB3D0EE FOREIGN KEY (project) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5A8A6C8D FOREIGN KEY (post) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF87474F3 FOREIGN KEY (lesson) REFERENCES lesson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gallery_item ADD CONSTRAINT FK_8C040D92166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
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
        $this->addSql('ALTER TABLE project_technology ADD CONSTRAINT FK_ECC5297F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_technology ADD CONSTRAINT FK_ECC5297F4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tool ADD CONSTRAINT FK_1962F6C9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tool ADD CONSTRAINT FK_1962F6C98F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_option CHANGE constant constant VARCHAR(255) NOT NULL, CHANGE value value JSON DEFAULT NULL, CHANGE label label VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE help help VARCHAR(255) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE file file VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE is_uploadable is_uploadable TINYINT(1) DEFAULT NULL, CHANGE is_required is_required TINYINT(1) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74E3149CB6AD5D8 ON admin_option (constant)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3BDAFD8C8');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DBDAFD8C8');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEBDAFD8C8');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F39552E5E2');
        $this->addSql('ALTER TABLE project_coding_language DROP FOREIGN KEY FK_30D2064F9552E5E2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF87474F3');
        $this->addSql('ALTER TABLE lesson_lesson_category DROP FOREIGN KEY FK_A6FD5744CDF80196');
        $this->addSql('ALTER TABLE lesson_lesson_category DROP FOREIGN KEY FK_A6FD5744E39F76E9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C5A8A6C8D');
        $this->addSql('ALTER TABLE post_post_category DROP FOREIGN KEY FK_A6D02E734B89032C');
        $this->addSql('ALTER TABLE post_post_category DROP FOREIGN KEY FK_A6D02E73FE0617CD');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C2FB3D0EE');
        $this->addSql('ALTER TABLE gallery_item DROP FOREIGN KEY FK_8C040D92166D1F9C');
        $this->addSql('ALTER TABLE project_project_category DROP FOREIGN KEY FK_86875173166D1F9C');
        $this->addSql('ALTER TABLE project_coding_language DROP FOREIGN KEY FK_30D2064F166D1F9C');
        $this->addSql('ALTER TABLE project_technology DROP FOREIGN KEY FK_ECC5297F166D1F9C');
        $this->addSql('ALTER TABLE project_tool DROP FOREIGN KEY FK_1962F6C9166D1F9C');
        $this->addSql('ALTER TABLE project_project_category DROP FOREIGN KEY FK_86875173DA896A19');
        $this->addSql('ALTER TABLE project_technology DROP FOREIGN KEY FK_ECC5297F4235D463');
        $this->addSql('ALTER TABLE project_tool DROP FOREIGN KEY FK_1962F6C98F7B22CC');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C88D93D649');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE coding_language');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE gallery_item');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE lesson_lesson_category');
        $this->addSql('DROP TABLE lesson_category');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_post_category');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_project_category');
        $this->addSql('DROP TABLE project_coding_language');
        $this->addSql('DROP TABLE project_technology');
        $this->addSql('DROP TABLE project_tool');
        $this->addSql('DROP TABLE project_category');
        $this->addSql('DROP TABLE technology');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE tool');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_74E3149CB6AD5D8 ON admin_option');
        $this->addSql('ALTER TABLE admin_option CHANGE constant constant INT NOT NULL, CHANGE value value INT NOT NULL, CHANGE label label INT NOT NULL, CHANGE type type INT NOT NULL, CHANGE help help INT NOT NULL, CHANGE is_active is_active INT NOT NULL, CHANGE file file INT NOT NULL, CHANGE updated_at updated_at INT NOT NULL, CHANGE is_uploadable is_uploadable INT NOT NULL, CHANGE is_required is_required INT NOT NULL');
    }
}
