<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191127081521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC69393F8FE');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC698260155');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6B08FA272');
        $this->addSql('ALTER TABLE eid_test DROP FOREIGN KEY FK_331ACCC6F6BD1646');
        $this->addSql('DROP INDEX IDX_331ACCC6B08FA272 ON eid_test');
        $this->addSql('DROP INDEX IDX_331ACCC69393F8FE ON eid_test');
        $this->addSql('DROP INDEX IDX_331ACCC6F6BD1646 ON eid_test');
        $this->addSql('DROP INDEX IDX_331ACCC698260155 ON eid_test');
        $this->addSql('ALTER TABLE eid_test ADD plateforme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site ADD region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_694309E498260155 ON site (region_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eid_test DROP plateforme_id');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC69393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC698260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6B08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE eid_test ADD CONSTRAINT FK_331ACCC6F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_331ACCC6B08FA272 ON eid_test (district_id)');
        $this->addSql('CREATE INDEX IDX_331ACCC69393F8FE ON eid_test (partner_id)');
        $this->addSql('CREATE INDEX IDX_331ACCC6F6BD1646 ON eid_test (site_id)');
        $this->addSql('CREATE INDEX IDX_331ACCC698260155 ON eid_test (region_id)');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E498260155');
        $this->addSql('DROP INDEX IDX_694309E498260155 ON site');
        $this->addSql('ALTER TABLE site DROP region_id');
    }
}
