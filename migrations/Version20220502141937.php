<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502141937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, campaign_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, age_range VARCHAR(255) NOT NULL, INDEX IDX_D34A04ADF639F774 (campaign_id), INDEX IDX_D34A04ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question_answer CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD name_company VARCHAR(255) DEFAULT NULL, ADD postal_address VARCHAR(255) NOT NULL, CHANGE age age INT DEFAULT NULL, CHANGE height height INT DEFAULT NULL, CHANGE weight weight INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE question_answer CHANGE name name VARCHAR(255) DEFAULT \'\' NOT NULL');
        $this->addSql('ALTER TABLE user DROP name_company, DROP postal_address, CHANGE age age INT NOT NULL, CHANGE height height INT NOT NULL, CHANGE weight weight INT NOT NULL');
    }
}
