<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191127013501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7B2124AA2C');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7B5BC02885');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7B8ED9AC6D');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7BA44D72BE');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7BBD7D4984');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7BD7A446D4');
        $this->addSql('DROP INDEX IDX_6DC99D7BBD7D4984 ON eid_patient');
        $this->addSql('DROP INDEX IDX_6DC99D7B2124AA2C ON eid_patient');
        $this->addSql('DROP INDEX IDX_6DC99D7B5BC02885 ON eid_patient');
        $this->addSql('DROP INDEX IDX_6DC99D7B8ED9AC6D ON eid_patient');
        $this->addSql('DROP INDEX IDX_6DC99D7BD7A446D4 ON eid_patient');
        $this->addSql('DROP INDEX IDX_6DC99D7BA44D72BE ON eid_patient');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6213AFEA8');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6DE0C217E');
        $this->addSql('DROP INDEX IDX_331ACCC6213AFEA8 ON eid_test');
        $this->addSql('DROP INDEX IDX_331ACCC6DE0C217E ON eid_test');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7B2124AA2C FOREIGN KEY (infant_arv) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7B5BC02885 FOREIGN KEY (mother_hiv_status) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7B8ED9AC6D FOREIGN KEY (stopped_breastfeeding) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7BA44D72BE FOREIGN KEY (type_of_clinic) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7BBD7D4984 FOREIGN KEY (feeding_type) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7BD7A446D4 FOREIGN KEY (mother_regimen) REFERENCES eid_dictionary (id)');
        $this->addSql('CREATE INDEX IDX_6DC99D7BBD7D4984 ON eid_patient (feeding_type)');
        $this->addSql('CREATE INDEX IDX_6DC99D7B2124AA2C ON eid_patient (infant_arv)');
        $this->addSql('CREATE INDEX IDX_6DC99D7B5BC02885 ON eid_patient (mother_hiv_status)');
        $this->addSql('CREATE INDEX IDX_6DC99D7B8ED9AC6D ON eid_patient (stopped_breastfeeding)');
        $this->addSql('CREATE INDEX IDX_6DC99D7BD7A446D4 ON eid_patient (mother_regimen)');
        $this->addSql('CREATE INDEX IDX_6DC99D7BA44D72BE ON eid_patient (type_of_clinic)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6213AFEA8 FOREIGN KEY (second_pcr_test_reason) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6DE0C217E FOREIGN KEY (rejected_reason) REFERENCES eid_dictionary (id)');
        $this->addSql('CREATE INDEX IDX_331ACCC6213AFEA8 ON eid_test (second_pcr_test_reason)');
        $this->addSql('CREATE INDEX IDX_331ACCC6DE0C217E ON eid_test (rejected_reason)');
    }
}
