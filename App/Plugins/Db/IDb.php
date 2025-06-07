<?php

namespace App\Plugins\Db;

interface IDb {
    /**
     * Function to execute a query
     * @param string $query
     * @param array $bind
     * @return bool
     */
    public function executeQuery(string $query, array $bind = []): bool;

    /**
     * Function to retrieve the last inserted id
     * @return int
     */
    public function getLastInsertedId(): int;

    /**
     * Function to retrieve the last executed statement
     * @return null|\PDOStatement
     */
    public function getStatement(): ?\PDOStatement;

    /**
     * Function to get the connection
     * @return null|\PDO
     */
    public function getConnection(): ?\PDO;

    /**
     * Function to retrieve the data from table
     * @return array
     */
    public function executeQueryFetchData(string $query, array $bind = []): array;

    /**
     * Function to retrieve the single Reord
     * @return array
     */
    public function executeQueryFetchSingleData(string $query, array $bind = []): array;

    /**
     * Function to retrive search query
     *
     * @param string $sql
     * @param string $string
     * @return array
     */
    public function searchqueryLike(string $sql,string $string): array;

    
}
