<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427082610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A57ECF78B0');
        $this->addSql('DROP INDEX IDX_6977C7A57ECF78B0 ON presence');
        $this->addSql('ALTER TABLE presence ADD seances_id INT NOT NULL, DROP cours_id, DROP date_cours');
        $this->addSql('ALTER TABLE presence ADD CONSTRAINT FK_6977C7A510F09302 FOREIGN KEY (seances_id) REFERENCES seances (id)');
        $this->addSql('CREATE INDEX IDX_6977C7A510F09302 ON presence (seances_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A510F09302');
        $this->addSql('DROP INDEX IDX_6977C7A510F09302 ON presence');
        $this->addSql('ALTER TABLE presence ADD cours_id INT DEFAULT NULL, ADD date_cours DATE NOT NULL, DROP seances_id');
        $this->addSql('ALTER TABLE presence ADD CONSTRAINT FK_6977C7A57ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('CREATE INDEX IDX_6977C7A57ECF78B0 ON presence (cours_id)');
    }
}
