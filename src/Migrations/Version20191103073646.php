<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191103073646 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE district (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, diis_code VARCHAR(10) DEFAULT NULL, datim_code VARCHAR(30) DEFAULT NULL, INDEX IDX_31C1548798260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eid_age_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, age_min INT NOT NULL, age_max INT NOT NULL, level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eid_dictionary (id INT AUTO_INCREMENT NOT NULL, entry_name VARCHAR(30) NOT NULL, entry_trans VARCHAR(60) NOT NULL, domain VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eid_import (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, file_size NUMERIC(12,2) DEFAULT NULL, date_import DATETIME NOT NULL, file_name VARCHAR(100) NOT NULL, rows_number INT DEFAULT NULL, INDEX IDX_8934C81CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eid_patient (id INT AUTO_INCREMENT NOT NULL, gender INT DEFAULT NULL, infant_regimen INT DEFAULT NULL, mother_regimen INT DEFAULT NULL, mother_hiv_status INT DEFAULT NULL, type_of_clinic INT DEFAULT NULL, feeding_type INT DEFAULT NULL, stopped_breastfeeding INT DEFAULT NULL, infant_arv INT DEFAULT NULL, dbs_code VARCHAR(20) NOT NULL, patient_code VARCHAR(30) NOT NULL, birth_date DATE NOT NULL, infant_ptme TINYINT(1) DEFAULT NULL, infant_symptomatic TINYINT(1) NOT NULL, infant_cotrimoxazole TINYINT(1) DEFAULT NULL, last_test DATE NOT NULL, date_updated DATETIME NOT NULL, INDEX IDX_6DC99D7BC7470A42 (gender), INDEX IDX_6DC99D7B30ADFD1 (infant_regimen), INDEX IDX_6DC99D7BD7A446D4 (mother_regimen), INDEX IDX_6DC99D7B5BC02885 (mother_hiv_status), INDEX IDX_6DC99D7BA44D72BE (type_of_clinic), INDEX IDX_6DC99D7BBD7D4984 (feeding_type), INDEX IDX_6DC99D7B8ED9AC6D (stopped_breastfeeding), INDEX IDX_6DC99D7B2124AA2C (infant_arv), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eid_test (id INT AUTO_INCREMENT NOT NULL, infant_gender INT DEFAULT NULL, which_pcr INT DEFAULT NULL, second_pcr_test_reason INT DEFAULT NULL, rejected_reason INT DEFAULT NULL, pcr_result INT DEFAULT NULL, patient_id INT DEFAULT NULL, site_id INT DEFAULT NULL, district_id INT DEFAULT NULL, region_id INT DEFAULT NULL, partner_id INT DEFAULT NULL, labno VARCHAR(20) NOT NULL, collected_date DATETIME NOT NULL, received_date DATETIME NOT NULL, completed_date DATETIME NOT NULL, released_date DATETIME NOT NULL, dispatched_date DATETIME DEFAULT NULL, infant_age_month INT NOT NULL, infant_age_week INT NOT NULL, date_updated DATETIME NOT NULL, INDEX IDX_331ACCC6DE96EDF4 (infant_gender), INDEX IDX_331ACCC67F8D1622 (which_pcr), INDEX IDX_331ACCC6213AFEA8 (second_pcr_test_reason), INDEX IDX_331ACCC6DE0C217E (rejected_reason), INDEX IDX_331ACCC6E96D3F8C (pcr_result), INDEX IDX_331ACCC66B899279 (patient_id), INDEX IDX_331ACCC6F6BD1646 (site_id), INDEX IDX_331ACCC6B08FA272 (district_id), INDEX IDX_331ACCC698260155 (region_id), INDEX IDX_331ACCC69393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lab_prefix (id INT AUTO_INCREMENT NOT NULL, plateforme_id INT DEFAULT NULL, eid_prefix VARCHAR(8) NOT NULL, vl_prefix VARCHAR(8) NOT NULL, date_updated DATETIME NOT NULL, INDEX IDX_66900DA391E226B (plateforme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_visited (id INT AUTO_INCREMENT NOT NULL, visitor_id INT DEFAULT NULL, page VARCHAR(200) NOT NULL, visited_count INT NOT NULL, first_visited_date DATETIME NOT NULL, last_visited_date DATETIME NOT NULL, INDEX IDX_3EBE57D170BEE6D (visitor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plateforme (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, lab_desc VARCHAR(200) NOT NULL, lab_location VARCHAR(200) NOT NULL, INDEX IDX_3C020C11F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, diis_code VARCHAR(10) NOT NULL, datim_code VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, district_id INT DEFAULT NULL, partner_id INT DEFAULT NULL, diis_code VARCHAR(10) NOT NULL, name VARCHAR(100) NOT NULL, datim_code VARCHAR(30) NOT NULL, datim_name VARCHAR(200) NOT NULL, priority TINYINT(1) NOT NULL, vl_test TINYINT(1) NOT NULL, eid_test TINYINT(1) NOT NULL, hiv_followup TINYINT(1) NOT NULL, latitude NUMERIC(12, 8) NOT NULL, longitude NUMERIC(12, 8) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_694309E4B08FA272 (district_id), INDEX IDX_694309E49393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_contact (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, contact VARCHAR(100) NOT NULL, role VARCHAR(100) NOT NULL, mail VARCHAR(100) NOT NULL, INDEX IDX_B7C604F3F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitor (id INT AUTO_INCREMENT NOT NULL, visited_count INT NOT NULL, ip_address VARCHAR(30) NOT NULL, first_visited_date DATETIME NOT NULL, last_visited_date DATETIME NOT NULL, timestamp INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE district ADD CONSTRAINT FK_31C1548798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE eid_import ADD CONSTRAINT FK_8934C81CA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7BC7470A42 FOREIGN KEY (gender) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7B30ADFD1 FOREIGN KEY (infant_regimen) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7BD7A446D4 FOREIGN KEY (mother_regimen) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7B5BC02885 FOREIGN KEY (mother_hiv_status) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7BA44D72BE FOREIGN KEY (type_of_clinic) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7BBD7D4984 FOREIGN KEY (feeding_type) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7B8ED9AC6D FOREIGN KEY (stopped_breastfeeding) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7B2124AA2C FOREIGN KEY (infant_arv) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6DE96EDF4 FOREIGN KEY (infant_gender) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC67F8D1622 FOREIGN KEY (which_pcr) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6213AFEA8 FOREIGN KEY (second_pcr_test_reason) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6DE0C217E FOREIGN KEY (rejected_reason) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6E96D3F8C FOREIGN KEY (pcr_result) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC66B899279 FOREIGN KEY (patient_id) REFERENCES eid_patient (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6B08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC698260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC69393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE lab_prefix ADD CONSTRAINT FK_66900DA391E226B FOREIGN KEY (plateforme_id) REFERENCES plateforme (id)');
        $this->addSql('ALTER TABLE page_visited ADD CONSTRAINT FK_3EBE57D170BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id)');
        $this->addSql('ALTER TABLE plateforme ADD CONSTRAINT FK_3C020C11F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4B08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E49393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE site_contact ADD CONSTRAINT FK_B7C604F3F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6B08FA272');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4B08FA272');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7BC7470A42');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7B30ADFD1');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7BD7A446D4');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7B5BC02885');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7BA44D72BE');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7BBD7D4984');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7B8ED9AC6D');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7B2124AA2C');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6DE96EDF4');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC67F8D1622');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6213AFEA8');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6DE0C217E');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6E96D3F8C');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC66B899279');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC69393F8FE');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E49393F8FE');
        $this->addSql('ALTER TABLE lab_prefix DROP FOREIGN KEY FK_66900DA391E226B');
        $this->addSql('ALTER TABLE district DROP FOREIGN KEY FK_31C1548798260155');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC698260155');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6F6BD1646');
        $this->addSql('ALTER TABLE plateforme DROP FOREIGN KEY FK_3C020C11F6BD1646');
        $this->addSql('ALTER TABLE site_contact DROP FOREIGN KEY FK_B7C604F3F6BD1646');
        $this->addSql('ALTER TABLE page_visited DROP FOREIGN KEY FK_3EBE57D170BEE6D');
        $this->addSql('DROP TABLE district');
        $this->addSql('DROP TABLE eid_age_category');
        $this->addSql('DROP TABLE eid_dictionary');
        $this->addSql('DROP TABLE eid_import');
        $this->addSql('DROP TABLE eid_patient');
        $this->addSql('DROP TABLE eid_test');
        $this->addSql('DROP TABLE lab_prefix');
        $this->addSql('DROP TABLE page_visited');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE plateforme');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE site_contact');
        $this->addSql('DROP TABLE visitor');
    }
}
