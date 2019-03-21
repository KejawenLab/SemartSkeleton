<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181227074604 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE semart_grup (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, kode VARCHAR(27) NOT NULL, nama VARCHAR(77) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_67AD05FFB2A11877 (kode), INDEX semart_grup_search_idx (kode, nama), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semart_setting (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', parameter VARCHAR(27) NOT NULL, nilai VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX semart_setting_search_idx (parameter), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semart_menu (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', induk_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', menu_order INT DEFAULT NULL, icon_class VARCHAR(27) DEFAULT NULL, nama_rute VARCHAR(77) DEFAULT NULL, showable TINYINT(1) NOT NULL, kode VARCHAR(27) NOT NULL, nama VARCHAR(77) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C8256F7BB2A11877 (kode), INDEX IDX_C8256F7BA67F1D92 (induk_id), INDEX semart_menu_search_idx (kode, nama), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semart_pengguna (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', grup_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', nama_pengguna VARCHAR(12) NOT NULL, kata_sandi VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6B237C07192ACF9E (nama_pengguna), INDEX IDX_6B237C07569AD2DE (grup_id), INDEX semart_pengguna_search_idx (nama_pengguna), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semart_hak_akses (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', grup_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', menu_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', addable TINYINT(1) NOT NULL, editable TINYINT(1) NOT NULL, viewable TINYINT(1) NOT NULL, deletable TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E3561D0B569AD2DE (grup_id), INDEX IDX_E3561D0BCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE semart_menu ADD CONSTRAINT FK_C8256F7BA67F1D92 FOREIGN KEY (induk_id) REFERENCES semart_menu (id)');
        $this->addSql('ALTER TABLE semart_pengguna ADD CONSTRAINT FK_6B237C07569AD2DE FOREIGN KEY (grup_id) REFERENCES semart_grup (id)');
        $this->addSql('ALTER TABLE semart_hak_akses ADD CONSTRAINT FK_E3561D0B569AD2DE FOREIGN KEY (grup_id) REFERENCES semart_grup (id)');
        $this->addSql('ALTER TABLE semart_hak_akses ADD CONSTRAINT FK_E3561D0BCCD7E912 FOREIGN KEY (menu_id) REFERENCES semart_menu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE semart_pengguna DROP FOREIGN KEY FK_6B237C07569AD2DE');
        $this->addSql('ALTER TABLE semart_hak_akses DROP FOREIGN KEY FK_E3561D0B569AD2DE');
        $this->addSql('ALTER TABLE semart_menu DROP FOREIGN KEY FK_C8256F7BA67F1D92');
        $this->addSql('ALTER TABLE semart_hak_akses DROP FOREIGN KEY FK_E3561D0BCCD7E912');
        $this->addSql('DROP TABLE semart_grup');
        $this->addSql('DROP TABLE semart_setting');
        $this->addSql('DROP TABLE semart_menu');
        $this->addSql('DROP TABLE semart_pengguna');
        $this->addSql('DROP TABLE semart_hak_akses');
    }
}
