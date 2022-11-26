<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118204028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, room_id_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', starts_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ends_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', paid TINYINT(1) NOT NULL, INDEX IDX_42C8495535F83FFC (room_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, room_no INT NOT NULL, floor_no INT NOT NULL, size DOUBLE PRECISION NOT NULL, beds INT NOT NULL, balcony TINYINT(1) NOT NULL, breakfast TINYINT(1) NOT NULL, pets_allowed TINYINT(1) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_photo (id INT AUTO_INCREMENT NOT NULL, room_id_id INT NOT NULL, name VARCHAR(45) NOT NULL, INDEX IDX_5E0B25B335F83FFC (room_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495535F83FFC FOREIGN KEY (room_id_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room_photo ADD CONSTRAINT FK_5E0B25B335F83FFC FOREIGN KEY (room_id_id) REFERENCES room (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495535F83FFC');
        $this->addSql('ALTER TABLE room_photo DROP FOREIGN KEY FK_5E0B25B335F83FFC');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_photo');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
