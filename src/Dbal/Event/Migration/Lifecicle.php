<?php

declare(strict_types=1);

namespace App\Dbal\Event\Migration;

use App\Dbal\EnumTypes\BlogStatus;
use App\Dbal\Types\PGSQL\BlogStatusType;
use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Exception;
use Doctrine\Migrations\Event\MigrationsEventArgs;
use Doctrine\Migrations\Events;
use Doctrine\ORM\EntityManagerInterface;

class Lifecicle implements EventSubscriber
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onMigrationsMigrating,
            Events::onMigrationsVersionExecuting,
        ];
    }

    /**
     * @param MigrationsEventArgs $args
     * @return void
     * @throws Exception
     */
    public function onMigrationsMigrating(MigrationsEventArgs $args): void
    {
        $type = (new BlogStatusType())->getName();
        $types = array_column(BlogStatus::cases(), 'name');
        $types = "'" . implode("', '", $types) . "'";

        $conn = $this->entityManager->getConnection();

        $sqlType = "DROP TYPE IF EXISTS $type";
        $conn->executeQuery($sqlType);

        $sqlCommon = "DO
$$
    BEGIN
        CREATE TYPE $type AS ENUM ($types);
    EXCEPTION
        WHEN duplicate_object THEN null;
    END
$$";
        $conn->executeQuery($sqlCommon);
    }
}
