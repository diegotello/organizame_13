<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121027132106 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        $this->addSql("UPDATE activitytype SET name='route' WHERE id=2");
        $this->addSql("UPDATE activitytype SET name='individual' WHERE id=3");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(4,'block-time')");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(5,'block-count')");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(6,'block-time-count')");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(7,'route-time')");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(8,'route-count')");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(9,'route-time-count')");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(10,'individual-time')");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(11,'individual-count')");
        $this->addSql("INSERT INTO activitytype(id,name)VALUES(12,'individual-time-count')");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        $this->addSql("UPDATE activitytype SET name='time' WHERE id=2");
        $this->addSql("UPDATE activitytype SET name='count' WHERE id=3");
        $this->addSql("DELETE FROM activitytype WHERE id>3 AND id<13");
    }
}
