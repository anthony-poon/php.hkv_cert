<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180604072916 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE doc_data (id INT AUTO_INCREMENT NOT NULL, template_name VARCHAR(64) NOT NULL, doc_id VARCHAR(64) NOT NULL, recipient_name VARCHAR(64) NOT NULL, recipient_email VARCHAR(64) NOT NULL, course_code VARCHAR(64) NOT NULL, json_data JSON NOT NULL, create_date DATETIME DEFAULT NULL, last_modified_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_F4E944B5895648BC (doc_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_roles (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(128) DEFAULT NULL, role_name VARCHAR(128) DEFAULT NULL, UNIQUE INDEX UNIQ_54FCD59F6DE44026 (description), UNIQUE INDEX UNIQ_54FCD59FE09C0C92 (role_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(128) DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C2502824F85E0677 (username), UNIQUE INDEX UNIQ_C2502824E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_roles_mapping (user_id INT NOT NULL, user_role_id INT NOT NULL, INDEX IDX_9D36F721A76ED395 (user_id), INDEX IDX_9D36F7218E0E3CA6 (user_role_id), PRIMARY KEY(user_id, user_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_roles_mapping ADD CONSTRAINT FK_9D36F721A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles_mapping ADD CONSTRAINT FK_9D36F7218E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_roles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_roles_mapping DROP FOREIGN KEY FK_9D36F7218E0E3CA6');
        $this->addSql('ALTER TABLE user_roles_mapping DROP FOREIGN KEY FK_9D36F721A76ED395');
        $this->addSql('DROP TABLE doc_data');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE user_roles_mapping');
    }
}
