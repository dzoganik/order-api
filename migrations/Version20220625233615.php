<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220625233615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item ADD entity_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0981257D5D FOREIGN KEY (entity_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F0981257D5D ON order_item (entity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0981257D5D');
        $this->addSql('DROP INDEX IDX_52EA1F0981257D5D ON order_item');
        $this->addSql('ALTER TABLE order_item DROP entity_id');
    }
}
