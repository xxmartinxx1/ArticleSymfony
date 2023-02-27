<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225081200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_creator (article_id INT NOT NULL, creator_id INT NOT NULL, INDEX IDX_51D4AED47294869C (article_id), INDEX IDX_51D4AED461220EA6 (creator_id), PRIMARY KEY(article_id, creator_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_creator ADD CONSTRAINT FK_51D4AED47294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_creator ADD CONSTRAINT FK_51D4AED461220EA6 FOREIGN KEY (creator_id) REFERENCES creator (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_creator DROP FOREIGN KEY FK_51D4AED47294869C');
        $this->addSql('ALTER TABLE article_creator DROP FOREIGN KEY FK_51D4AED461220EA6');
        $this->addSql('DROP TABLE article_creator');
    }
}
