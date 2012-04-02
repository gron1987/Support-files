<?php
/**
 * File: SessionMapper.php.
 * User: gron
 * Date: 3/25/12
 * Time: 10:00 PM
 */
namespace Session;

use Core\DbConnection as DB;
use JQueryGrid\JQGridMapper;

/**
 * Mapper for Session table.
 * Queries for JQGrid
 */
class SessionMapper extends JQGridMapper
{
    const TABLE_NAME = 'phpbb3_sessions_keys';

    /**
     * Get total rows count from table by where clause
     * @return int
     */
    public function getCount(){
        $sql = "
            SELECT COUNT(*) as `kol`
            FROM " . self::TABLE_NAME . "
            " . $this->_whereStr ."
        ";

        $query = DB::getInstance()->execute($sql,$this->_whereArr);
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        return $result['kol'];
    }

    /**
     * Get data from table by where clause and function arguments
     * @param int $start
     * @param int $limit
     * @param int $sidx
     * @param int $sord
     * @return array
     */
    public function getRows($start,$limit,$sidx,$sord){
        $sql = "
            SELECT *
            FROM " . self::TABLE_NAME . "
            " . $this->_whereStr ."
            ORDER BY $sidx $sord
            LIMIT $start,$limit
        ";

        $query = DB::getInstance()->execute($sql,$this->_whereArr);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
