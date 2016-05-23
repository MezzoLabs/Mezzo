<?php


namespace MezzoLabs\Mezzo\Core\Database;


use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Traits\IsShared;

class Reader
{
    use IsShared;

    /**
     * @var DatabaseManager
     */
    private $manager;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    private $schemaManager;


    /**
     * Create a new database reader to analyse the database schema.
     *
     * @param DatabaseManager $manager
     * @internal param Connection $connection
     */
    public function __construct(DatabaseManager $manager)
    {
        $this->manager = $manager;
        $this->connection = $manager->connection();
        $this->schemaManager = $this->connection->getDoctrineSchemaManager();


    }

    /**
     * Get the column listing for a given table.
     *
     * @param Table|string $table
     * @return array
     */
    public function getColumns($table)
    {
        if ($table instanceof Table)
            $table = $table->name();

        return Singleton::get(
            'database.columns.' . $table,
            function () use ($table) {
                return $this->schemaManager->listTableColumns($table);
            });
    }

    /**
     * Checks if the given column is migrated.
     *
     * @param $table string
     * @param $column string
     * @return bool
     */
    public function columnIsPersisted($column, $table)
    {
        $columns = $this->getColumns($table);

        return isset($columns[$column]);
    }

    /**
     * Checks if the given table is migrated.
     *
     * @param $table
     * @return bool
     */
    public function tableIsPersisted($table)
    {
        return !empty($this->getColumns($table));
    }
} 