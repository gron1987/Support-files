<?php
/**
 * File: SessionEntity.php.
 * User: gron
 * Date: 3/25/12
 * Time: 10:00 PM
 */
namespace Session;

use Core\DbConnection as DB;

class SessionEntity
{
    const TABLE_NAME = 'phpbb3_sessions_keys';

    private $where_str = '';
    private $where_arr = array();

    public function getCount(){
        $sql = "
            SELECT COUNT(*) as `kol`
            FROM " . self::TABLE_NAME . "
            " . $this->where_str ."
        ";

        $query = DB::getInstance()->execute($sql,$this->where_arr);
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        return $result['kol'];
    }

    public function getRows($start,$limit,$sidx,$sord){
        $sql = "
            SELECT *
            FROM " . self::TABLE_NAME . "
            " . $this->where_str ."
            ORDER BY $sidx $sord
            LIMIT $start,$limit
        ";

        $query = DB::getInstance()->execute($sql,$this->where_arr);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
