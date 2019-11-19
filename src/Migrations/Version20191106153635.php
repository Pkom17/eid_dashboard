<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191106153635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE district ADD active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE partner ADD active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE plateforme CHANGE lab_desc lab_desc VARCHAR(200) DEFAULT NULL, CHANGE lab_location lab_location VARCHAR(200) DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD active TINYINT(1) DEFAULT NULL, CHANGE diis_code diis_code VARCHAR(10) DEFAULT NULL, CHANGE datim_code datim_code VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE site CHANGE diis_code diis_code VARCHAR(10) DEFAULT NULL, CHANGE vl_test vl_test TINYINT(1) DEFAULT NULL, CHANGE eid_test eid_test TINYINT(1) DEFAULT NULL, CHANGE hiv_followup hiv_followup TINYINT(1) DEFAULT NULL, CHANGE latitude latitude NUMERIC(12, 8) DEFAULT NULL, CHANGE longitude longitude NUMERIC(12, 8) DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE district DROP active');
        $this->addSql('ALTER TABLE partner DROP active');
        $this->addSql('ALTER TABLE plateforme CHANGE lab_desc lab_desc VARCHAR(200) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE lab_location lab_location VARCHAR(200) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE region DROP active, CHANGE diis_code diis_code VARCHAR(10) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE datim_code datim_code VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE site CHANGE diis_code diis_code VARCHAR(10) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE vl_test vl_test TINYINT(1) NOT NULL, CHANGE eid_test eid_test TINYINT(1) NOT NULL, CHANGE hiv_followup hiv_followup TINYINT(1) NOT NULL, CHANGE latitude latitude NUMERIC(12, 8) NOT NULL, CHANGE longitude longitude NUMERIC(12, 8) NOT NULL, CHANGE active active TINYINT(1) NOT NULL');
    }
}
