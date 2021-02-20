<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210219133510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create endpoint and endpoint header table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE endpoint (id INT AUTO_INCREMENT NOT NULL, api_id INT NOT NULL, method VARCHAR(10) NOT NULL, path VARCHAR(255) NOT NULL, status_code INT NOT NULL, response_body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C4420F7B54963938 (api_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE endpoint_header (id INT AUTO_INCREMENT NOT NULL, endpoint_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_53640B3E21AF7E36 (endpoint_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE endpoint ADD CONSTRAINT FK_C4420F7B54963938 FOREIGN KEY (api_id) REFERENCES api (id)');
        $this->addSql('ALTER TABLE endpoint_header ADD CONSTRAINT FK_53640B3E21AF7E36 FOREIGN KEY (endpoint_id) REFERENCES endpoint (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE endpoint_header DROP FOREIGN KEY FK_53640B3E21AF7E36');
        $this->addSql('DROP TABLE endpoint');
        $this->addSql('DROP TABLE endpoint_header');
    }
}
