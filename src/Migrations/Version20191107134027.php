<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107134027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('insert into region values (1,\'AGNEBY-TIASSA-ME\', \'\', \'xWtNXMJvZls\', 1)');
        $this->addSql('insert into region values (2,\'GBOKLE-NAWA-SAN PEDRO\', \'\', \'qF6xjc6BeFi\', 1)');
        $this->addSql('insert into region values (3,\'KABADOUGOU-BAFING-FOLON\', \'\', \'gsQNaHt0oUW\', 1)');
        $this->addSql('insert into region values (4,\'HAUT-SASSANDRA\', \'\', \'XZaPAzso7Hf\', 1)');
        $this->addSql('insert into region values (5,\'GOH\', \'\', \'CQNFlCBAutz\', 1)');
        $this->addSql('insert into region values (6,\'BELIER\', \'\', \'Xg6Lgk1bCt0\', 1)');
        $this->addSql('insert into region values (7,\'ABIDJAN 1-GRANDS PONTS\', \'\', \'ouvLM5iBp2U\', 1)');
        $this->addSql('insert into region values (8,\'ABIDJAN 2\', \'\', \'VmeP98Y7Ob0\', 1)');
        $this->addSql('insert into region values (9,\'MARAHOUE\', \'\', \'darR7PaS1At\', 1)');
        $this->addSql('insert into region values (10,\'TONKPI\', \'\', \'QAvyrYDx54a\', 1)');
        $this->addSql('insert into region values (11,\'CAVALLY-GUEMON\', \'\', \'dlKygFFZdv3\', 1)');
        $this->addSql('insert into region values (12,\'N\\\'ZI-IFOU\', \'\', \'uhdeYtg5mzU\', 1)');
        $this->addSql('insert into region values (13,\'INDENIE-DJUABLIN\', \'\', \'bfjeJlr9a0H\', 1)');
        $this->addSql('insert into region values (14,\'PORO-TCHOLOGO-BAGOUE\', \'\', \'y7mG2j0VDi1\', 1)');
        $this->addSql('insert into region values (15,\'LOH-DJIBOUA\', \'\', \'ud2hyDehnZs\', 1)');
        $this->addSql('insert into region values (16,\'SUD-COMOE\', \'\', \'yEDQW7ekVrO\', 1)');
        $this->addSql('insert into region values (17,\'GBEKE\', \'\', \'Ry4fZiNgHqs\', 1)');
        $this->addSql('insert into region values (18,\'HAMBOL\', \'\', \'dndrrL0zj7f\', 1)');
        $this->addSql('insert into region values (19,\'WORODOUGOU-BERE\', \'\', \'k0YgiUwf6v2\', 1)');
        $this->addSql('insert into region values (20,\'BOUNKANI-GONTOUGO\', \'\', \'hyRzs0vha4A\', 1)');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('delete from region where id between 1 and 20');

    }
}
