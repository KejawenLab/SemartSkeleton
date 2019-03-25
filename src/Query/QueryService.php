<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class QueryService
{
    private $connections;

    private $registryManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->connections = $managerRegistry->getConnections();
        $this->registryManager = $managerRegistry;
    }

    public function getConnections()
    {
        return array_keys($this->connections);
    }

    public function runQuery(string $query, string $connection = 'default'): array
    {
        $output = [
            'status' => true,
            'columns' => [],
            'records' => [],
            'total' => 0,
        ];

        $sqlRunner = $this->registryManager->getConnection(Str::make($connection)->lowercase()->__toString());
        try {
            /** @var \PDOStatement $statement */
            $statement = $sqlRunner->executeQuery($query);
            $results = $statement->fetchAll();

            $columns = [];
            if (!empty($results)) {
                $columns = array_keys($results[0]);
            }

            $output['columns'] = $columns;
            $output['records'] = $results;
            $output['total'] = count($results);
        } catch (\Exception $e) {
            $messages = explode(':', $e->getMessage());

            $output['status'] = false;
            $output['columns'] = ['error', 'reason', 'solution'];
            $output['records'][] = [Str::make($messages[1])->trim()->__toString(), Str::make($messages[2])->trim()->__toString(), Str::make(explode(';', $messages[3])[1])->trim()->uppercaseFirst()->__toString()];
        }

        return $output;
    }

    public function getTables(string $connection = 'default')
    {
        /** @var AbstractSchemaManager $schemaManager */
        $schemaManager = $this->registryManager->getConnection(Str::make($connection)->lowercase()->__toString())->getSchemaManager();

        $output = [];
        /** @var AbstractAsset[] $tables */
        $tables =array_merge($schemaManager->listTables(), $schemaManager->listViews());
        foreach ($tables as $table) {
            $output[] = $table->getName();
        }

        return $output;
    }
}
