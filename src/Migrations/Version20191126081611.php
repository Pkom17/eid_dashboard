<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191126081611 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_patient CHANGE dbs_code dbs_code VARCHAR(20) DEFAULT NULL, CHANGE patient_code patient_code VARCHAR(30) DEFAULT NULL, CHANGE birth_date birth_date DATE DEFAULT NULL, CHANGE infant_symptomatic infant_symptomatic TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE eid_test CHANGE infant_gender infant_gender INT DEFAULT NULL, CHANGE pcr_result pcr_result VARCHAR(60) DEFAULT NULL, CHANGE infant_age_month infant_age_month INT DEFAULT NULL, CHANGE infant_age_week infant_age_week INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_patient CHANGE dbs_code dbs_code VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE patient_code patient_code VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE birth_date birth_date DATE NOT NULL, CHANGE infant_symptomatic infant_symptomatic TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE eid_test CHANGE infant_age_month infant_age_month INT NOT NULL, CHANGE infant_age_week infant_age_week INT NOT NULL, CHANGE infant_gender infant_gender INT NOT NULL, CHANGE pcr_result pcr_result VARCHAR(60) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
