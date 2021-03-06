<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121024005120 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE activity (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, activitytype_id BIGINT NOT NULL, name VARCHAR(45) NOT NULL, description LONGTEXT DEFAULT NULL, isdependent INT NOT NULL, INDEX IDX_AC74095AA76ED395 (user_id), INDEX IDX_AC74095A6E098B10 (activitytype_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE activitydependence (id BIGINT AUTO_INCREMENT NOT NULL, dependent_activity_id BIGINT DEFAULT NULL, independent_activity_id BIGINT DEFAULT NULL, INDEX IDX_91DD8FE0780ECCD6 (dependent_activity_id), INDEX IDX_91DD8FE05E2C5CC7 (independent_activity_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE activitytype (id BIGINT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE measure (id BIGINT AUTO_INCREMENT NOT NULL, activity_id BIGINT NOT NULL, start BIGINT NOT NULL, end BIGINT NOT NULL, INDEX IDX_8007192581C06096 (activity_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE place (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, description VARCHAR(45) DEFAULT NULL, address VARCHAR(45) NOT NULL, second_address VARCHAR(45) DEFAULT NULL, lat VARCHAR(45) NOT NULL, lng VARCHAR(45) NOT NULL, INDEX IDX_741D53CDA76ED395 (user_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE profile (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, place_id BIGINT NOT NULL, firstname VARCHAR(45) NOT NULL, middlename VARCHAR(45) DEFAULT NULL, lastname VARCHAR(45) NOT NULL, UNIQUE INDEX UNIQ_8157AA0FA76ED395 (user_id), INDEX IDX_8157AA0FDA6A219 (place_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE role (id BIGINT NOT NULL, name VARCHAR(45) NOT NULL, UNIQUE INDEX UNIQ_57698A6A5E237E06 (name), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user (id BIGINT AUTO_INCREMENT NOT NULL, role_id BIGINT NOT NULL, username VARCHAR(45) NOT NULL, email VARCHAR(45) NOT NULL, salt VARCHAR(128) NOT NULL, password VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE activity ADD CONSTRAINT FK_AC74095AA76ED395 FOREIGN KEY (user_id) REFERENCES user(id)");
        $this->addSql("ALTER TABLE activity ADD CONSTRAINT FK_AC74095A6E098B10 FOREIGN KEY (activitytype_id) REFERENCES activitytype(id)");
        $this->addSql("ALTER TABLE activitydependence ADD CONSTRAINT FK_91DD8FE0780ECCD6 FOREIGN KEY (dependent_activity_id) REFERENCES activity(id)");
        $this->addSql("ALTER TABLE activitydependence ADD CONSTRAINT FK_91DD8FE05E2C5CC7 FOREIGN KEY (independent_activity_id) REFERENCES activity(id)");
        $this->addSql("ALTER TABLE measure ADD CONSTRAINT FK_8007192581C06096 FOREIGN KEY (activity_id) REFERENCES activity(id)");
        $this->addSql("ALTER TABLE place ADD CONSTRAINT FK_741D53CDA76ED395 FOREIGN KEY (user_id) REFERENCES user(id)");
        $this->addSql("ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FA76ED395 FOREIGN KEY (user_id) REFERENCES user(id)");
        $this->addSql("ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FDA6A219 FOREIGN KEY (place_id) REFERENCES place(id)");
        $this->addSql("ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role(id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE activitydependence DROP FOREIGN KEY FK_91DD8FE0780ECCD6");
        $this->addSql("ALTER TABLE activitydependence DROP FOREIGN KEY FK_91DD8FE05E2C5CC7");
        $this->addSql("ALTER TABLE measure DROP FOREIGN KEY FK_8007192581C06096");
        $this->addSql("ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A6E098B10");
        $this->addSql("ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FDA6A219");
        $this->addSql("ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC");
        $this->addSql("ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AA76ED395");
        $this->addSql("ALTER TABLE place DROP FOREIGN KEY FK_741D53CDA76ED395");
        $this->addSql("ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FA76ED395");
        $this->addSql("DROP TABLE activity");
        $this->addSql("DROP TABLE activitydependence");
        $this->addSql("DROP TABLE activitytype");
        $this->addSql("DROP TABLE measure");
        $this->addSql("DROP TABLE place");
        $this->addSql("DROP TABLE profile");
        $this->addSql("DROP TABLE role");
        $this->addSql("DROP TABLE user");
    }
}
