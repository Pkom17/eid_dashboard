<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191125102408 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7B30ADFD1');
        $this->addSql('ALTER TABLE eid_patient DROP FOREIGN KEY FK_6DC99D7BC7470A42');
        $this->addSql('DROP INDEX IDX_6DC99D7B30ADFD1 ON eid_patient');
        $this->addSql('DROP INDEX IDX_6DC99D7BC7470A42 ON eid_patient');
        $this->addSql('ALTER TABLE eid_patient DROP infant_regimen');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC67F8D1622');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6DE96EDF4');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6E96D3F8C');
        $this->addSql('DROP INDEX IDX_331ACCC67F8D1622 ON eid_test');
        $this->addSql('DROP INDEX IDX_331ACCC6DE96EDF4 ON eid_test');
        $this->addSql('DROP INDEX IDX_331ACCC6E96D3F8C ON eid_test');
        $this->addSql('ALTER TABLE eid_test CHANGE infant_gender infant_gender INT NOT NULL, CHANGE pcr_result pcr_result VARCHAR(60) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_patient ADD infant_regimen INT DEFAULT NULL');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7B30ADFD1 FOREIGN KEY (infant_regimen) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_patient ADD CONSTRAINT FK_6DC99D7BC7470A42 FOREIGN KEY (gender) REFERENCES eid_dictionary (id)');
        $this->addSql('CREATE INDEX IDX_6DC99D7B30ADFD1 ON eid_patient (infant_regimen)');
        $this->addSql('CREATE INDEX IDX_6DC99D7BC7470A42 ON eid_patient (gender)');
        $this->addSql('ALTER TABLE eid_test CHANGE infant_gender infant_gender INT DEFAULT NULL, CHANGE pcr_result pcr_result INT DEFAULT NULL');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC67F8D1622 FOREIGN KEY (which_pcr) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6DE96EDF4 FOREIGN KEY (infant_gender) REFERENCES eid_dictionary (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6E96D3F8C FOREIGN KEY (pcr_result) REFERENCES eid_dictionary (id)');
        $this->addSql('CREATE INDEX IDX_331ACCC67F8D1622 ON eid_test (which_pcr)');
        $this->addSql('CREATE INDEX IDX_331ACCC6DE96EDF4 ON eid_test (infant_gender)');
        $this->addSql('CREATE INDEX IDX_331ACCC6E96D3F8C ON eid_test (pcr_result)');
    }
}
