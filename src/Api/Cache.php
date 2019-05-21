<?php
namespace App\Api;

use App\Db;

/**
 * Fetches, sets or deletes cache in DB
 * 
 * @since 1.0.0
 * @package API
 * 
 * @todo Change name, as this is only working with DB?
 * @todo move this fuctions into DB class?
 */
class Cache
{

    /**
     * Constant for Cache expiry in mysql format
     *
     * @since 1.0.0
     */
    const CACHE_EXPIRY = '15 MINUTE';

    /**
     * Holds DB object
     *
     * @var object
     * @since 1.0.0
     */
    private $db;

    public function __construct()
    {
        $this->db = new Db;
        $this->check_tables();
    }

    public function createCache()
    {
    }

    public function getCache(array $select, $table, $where = '', array $params)
    {
        $queryBuilder = $this->db->conn->createQueryBuilder();

        $queryBuilder
            ->select($select)
            ->from($table)
            ->where($where)
            ->setParameters($params)
        ;
        return $queryBuilder->execute()->fetchAll();
    }

    public function setCache(array $values, array $params, $table)
    {
        $queryBuilder = $this->db->conn->createQueryBuilder();

        $queryBuilder
            ->insert($table)
            ->values($values)
            ->setParameters($params);

        return $queryBuilder->execute();
    }

    public function deleteCache($where, array $params, $table)
    {
        $queryBuilder = $this->db->conn->createQueryBuilder();

        $queryBuilder
            ->delete($table)
            ->where($where)
            ->setParameters($params);

        return $queryBuilder->execute();
    }

    private function check_tables()
    {
        $this->db->conn->query("CREATE TABLE IF NOT EXISTS `search_issues` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `date_cached` DATETIME NOT NULL,
            `date_expires` DATETIME NOT NULL,
            `term` VARCHAR(64) NOT NULL,
            `score` FLOAT(3,2) NOT NULL,
            PRIMARY KEY (`ID`)
        )
        COLLATE='utf8mb4_general_ci'
        ENGINE=MyISAM;");
    }
}
