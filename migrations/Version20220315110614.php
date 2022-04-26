<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220315110614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_E98F2859A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement_job (id INT AUTO_INCREMENT NOT NULL, job_id INT NOT NULL, departement_id INT DEFAULT NULL, INDEX IDX_6ACF4CE8BE04EA9 (job_id), INDEX IDX_6ACF4CE8CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documentation (id INT AUTO_INCREMENT NOT NULL, link VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE effective_work_days (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, startlog DATETIME DEFAULT NULL, startlunch DATETIME DEFAULT NULL, endlunch DATETIME DEFAULT NULL, endlog DATETIME DEFAULT NULL, hoursworked TIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8E534382A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, grade VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payslip (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9A13CDF0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planned_work_days (id INT AUTO_INCREMENT NOT NULL, startshift DATETIME NOT NULL, endshift DATETIME NOT NULL, startlunch DATETIME NOT NULL, endlunch DATETIME NOT NULL, hoursplanned TIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, job_id INT DEFAULT NULL, departement_id INT DEFAULT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, emailpro VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phonenumber VARCHAR(255) NOT NULL, phonenumberpro VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, rib VARCHAR(255) NOT NULL, status TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, date_of_birth DATETIME DEFAULT NULL, INDEX IDX_8D93D649D60322AC (role_id), INDEX IDX_8D93D649BE04EA9 (job_id), INDEX IDX_8D93D649CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_documentation (user_id INT NOT NULL, documentation_id INT NOT NULL, INDEX IDX_9D5B046BA76ED395 (user_id), INDEX IDX_9D5B046BC703EEC9 (documentation_id), PRIMARY KEY(user_id, documentation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_planned_work_days (user_id INT NOT NULL, planned_work_days_id INT NOT NULL, INDEX IDX_6F7C3271A76ED395 (user_id), INDEX IDX_6F7C327139907D21 (planned_work_days_id), PRIMARY KEY(user_id, planned_work_days_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE departement_job ADD CONSTRAINT FK_6ACF4CE8BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE departement_job ADD CONSTRAINT FK_6ACF4CE8CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE effective_work_days ADD CONSTRAINT FK_8E534382A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE payslip ADD CONSTRAINT FK_9A13CDF0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE user_documentation ADD CONSTRAINT FK_9D5B046BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_documentation ADD CONSTRAINT FK_9D5B046BC703EEC9 FOREIGN KEY (documentation_id) REFERENCES documentation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_planned_work_days ADD CONSTRAINT FK_6F7C3271A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_planned_work_days ADD CONSTRAINT FK_6F7C327139907D21 FOREIGN KEY (planned_work_days_id) REFERENCES planned_work_days (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE departement_job DROP FOREIGN KEY FK_6ACF4CE8CCF9E01E');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649CCF9E01E');
        $this->addSql('ALTER TABLE user_documentation DROP FOREIGN KEY FK_9D5B046BC703EEC9');
        $this->addSql('ALTER TABLE departement_job DROP FOREIGN KEY FK_6ACF4CE8BE04EA9');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649BE04EA9');
        $this->addSql('ALTER TABLE user_planned_work_days DROP FOREIGN KEY FK_6F7C327139907D21');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859A76ED395');
        $this->addSql('ALTER TABLE effective_work_days DROP FOREIGN KEY FK_8E534382A76ED395');
        $this->addSql('ALTER TABLE payslip DROP FOREIGN KEY FK_9A13CDF0A76ED395');
        $this->addSql('ALTER TABLE user_documentation DROP FOREIGN KEY FK_9D5B046BA76ED395');
        $this->addSql('ALTER TABLE user_planned_work_days DROP FOREIGN KEY FK_6F7C3271A76ED395');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE departement_job');
        $this->addSql('DROP TABLE documentation');
        $this->addSql('DROP TABLE effective_work_days');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE payslip');
        $this->addSql('DROP TABLE planned_work_days');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_documentation');
        $this->addSql('DROP TABLE user_planned_work_days');
    }
}
