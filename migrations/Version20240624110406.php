<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240624110406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit ADD unitereference INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CF069760 FOREIGN KEY (unitereference) REFERENCES unite (unitereference)');
        $this->addSql('CREATE INDEX IDX_29A5EC27CF069760 ON produit (unitereference)');
        $this->addSql('ALTER TABLE unite MODIFY unite_reference INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON unite');
        $this->addSql('ALTER TABLE unite ADD unitereference INT NOT NULL, DROP unite_reference');
        $this->addSql('ALTER TABLE unite ADD PRIMARY KEY (unitereference)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27CF069760');
        $this->addSql('DROP INDEX IDX_29A5EC27CF069760 ON produit');
        $this->addSql('ALTER TABLE produit DROP unitereference');
        $this->addSql('ALTER TABLE unite ADD unite_reference INT AUTO_INCREMENT NOT NULL, DROP unitereference, DROP PRIMARY KEY, ADD PRIMARY KEY (unite_reference)');
    }
}
