<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225075602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE relase_date relase_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE creator ADD work_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE creator ADD CONSTRAINT FK_BC06EA63BB3453DB FOREIGN KEY (work_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_BC06EA63BB3453DB ON creator (work_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE relase_date relase_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE creator DROP FOREIGN KEY FK_BC06EA63BB3453DB');
        $this->addSql('DROP INDEX IDX_BC06EA63BB3453DB ON creator');
        $this->addSql('ALTER TABLE creator DROP work_id');
    }
}
