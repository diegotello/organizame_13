<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121115122817 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE route_measure (id BIGINT AUTO_INCREMENT NOT NULL, start_place_id BIGINT DEFAULT NULL, end_place_id BIGINT DEFAULT NULL, cronogram_id BIGINT NOT NULL, start BIGINT NOT NULL, end BIGINT NOT NULL, status VARCHAR(45) NOT NULL, INDEX IDX_785444BD128CE42B (start_place_id), INDEX IDX_785444BDD4E53794 (end_place_id), INDEX IDX_785444BDC33497D9 (cronogram_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE route_measure ADD CONSTRAINT FK_785444BD128CE42B FOREIGN KEY (start_place_id) REFERENCES place(id)");
        $this->addSql("ALTER TABLE route_measure ADD CONSTRAINT FK_785444BDD4E53794 FOREIGN KEY (end_place_id) REFERENCES place(id)");
        $this->addSql("ALTER TABLE route_measure ADD CONSTRAINT FK_785444BDC33497D9 FOREIGN KEY (cronogram_id) REFERENCES cronogram(id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("DROP TABLE route_measure");
    }
}