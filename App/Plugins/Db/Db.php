<?php

namespace App\Plugins\Db;

use App\Plugins\Db\Connection\IConnection;
use PDO;
use PDOException;
use PDOStatement;

class Db implements IDb
{
    /** @var PDO|null */
    private $connection = null;
    /** @var PDOStatement */
    private $stmt;

    /**
     * Constructor of this class
     * @param IConnection $connectionImplementation
     */
    public function __construct(IConnection $connectionImplementation)
    {
        $this->connection = $this->connect($connectionImplementation);
    }

    /**
     * Function to start a transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Function to roll back the transaction
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    /**
     * Function to commit a transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * @param string $query
     * @param array $bind
     * @return bool
     */
    public function executeQuery(string $query, array $bind = []): bool
    {
        $this->stmt = $this->connection->prepare($query);
        if ($bind) {
            return $this->stmt->execute($bind);
        }
        return $this->stmt->execute();
    }

    /**
     * Function to get last inserted id
     * @param mixed $name
     * @return int
     */
    public function getLastInsertedId($name = null): int
    {
        $id = $this->connection->lastInsertId($name);
        return ($id ?: false);
    }

    /**
     * Function to get the connection
     * @return null|PDO
     */
    public function getConnection(): ?PDO
    {
        return $this->connection;
    }

    /**
     * Function to connect the db.
     * @return PDO
     * @throws PDOException
     */
    private function connect(IConnection $connectionImplementation)
    {
        try {
            return new PDO(
                $connectionImplementation->getDsn(),
                $connectionImplementation->getUsername(),
                $connectionImplementation->getPassword(),
            );
        } catch (PDOException $e) {
            // Just throw it:
            throw $e;
        }
    }

    /**
     * Function to return the last executed statement if any
     * @return null|PDOStatement
     */
    public function getStatement(): ?PDOStatement
    {
        return $this->stmt;
    }

    /**
     * Function to return the tabe data in array
     * @return array
     */
    public function executeQueryFetchData(string $query, array $bind = []): array
    {
        $this->stmt = $this->connection->prepare($query);
        $this->stmt->execute($bind);
        $results = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($results)) {
            $results = [];
        }
        return $results;
    }

    /**
     * Function to return the tabe data single Record in array
     * @return array
     */
    public function executeQueryFetchSingleData(string $query, array $bind = []): array
    {
        $this->stmt = $this->connection->prepare($query);
        $this->stmt->execute($bind);
        $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            $result = [];
        }
        return $result;
    }

    /**
     * Search filter permeter
     *
     * @param string $sql
     * @param string $string
     * @return array
     */
    function searchqueryLike(string $sql, string $string): array
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':q', $string, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
