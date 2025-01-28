<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Dbal\EnumTypes\BlogStatus;
use App\Dbal\Types\PGSQL\BlogStatusType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127120020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $type = (new BlogStatusType())->getName();
        $this->addSql("DROP TYPE IF EXISTS $type");
        $types = array_column(BlogStatus::cases(), 'name');
        $types = "'" . implode("', '", $types) . "'";

        $this->addSql("CREATE TYPE $type AS ENUM ($types)");
        $this->addSql("ALTER TABLE blog ADD status $type");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blog DROP status');
    }
}
