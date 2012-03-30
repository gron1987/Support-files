<?php
/**
 * File: JQGridMapper.php
 * User: gron
 * Date: Mar 27, 2012
 * Time: 12:07:15 PM
 * @version 1.0
 */
namespace JQueryGrid;

use JQueryGrid\JQGridMapperIF;

/**
 * Abstract mapper for JQGrid mapper object
 */
abstract class JQGridMapper implements JQGridMapperIF
{
    protected $_whereStr = '';
    protected $_whereArr = array();
    
    /**
     * Create object and create WHERE statement.
     * DO NOT OVERRIDE THIS IF YOU WANT CHANGE WHERE STATEMENT ! 
     * LOOK FOR addWhereToParams() function !
     * @throws \LogicException 
     */
    public function init()
    {
        if(static::TABLE_NAME === ''){
            throw new \LogicException('TABLE_NAME constant must be overrided in your mapper class. It can\'t be empty string. In class ' . get_class($this));
        }
        
        if (!empty($_GET['filters'])) {
            $_GET['filters'] = str_replace('\"', '"', $_GET['filters']);
            $this->_getWhereClauseFromRequest($_GET['filters']);
        } else {
            $this->_addWhereToParams('', array());
        }
    }
    
    /**
     * Form where clause from JQGrid reqest
     * @param string(JSON) $request
     * @return array
     */
    protected function _getWhereClauseFromRequest($request)
    {
        $where = '';
        $whereParams = array();

        $filter = json_decode($request);
        
        if (json_last_error() > 0) {
            return false;
        }

        $where_action = $filter->groupOp; // AND | OR
        $rules = $filter->rules; // array of rules
        foreach ($rules as $item) {
            if ($where != '') {
                $where .= ' ' . $where_action;
            }

            switch ($item->op) {
                case 'bw':
                    $where .= '`' . $item->field . '` LIKE "' . $item->data .'%"';
                    $item->data = '';
                    break;
                case 'eq':
                    $where .= '`' . $item->field . '` = ?';
                    break;
                case 'ne':
                    $where .= '`' . $item->field . '` <> ?';
                    break;
                case 'lt':
                    $where .= '`' . $item->field . '` < ?';
                    break;
                case 'le':
                    $where .= '`' . $item->field . '` <= ?';
                    break;
                case 'gt':
                    $where .= '`' . $item->field . '` > ?';
                    break;
                case 'ge':
                    $where .= '`' . $item->field . '` >= ?';
                    break;
                case 'ew':
                    $where .= '`' . $item->field . '` LIKE "%' . $item->data . '"';
                    $item->data = '';
                    break;
                case 'cn':
                    $where .= '`' . $item->field . '` LIKE "%' . $item->data . '%"';
                    $item->data = '';
                    break;
                case 'nc':
                    $where .= '`' . $item->field . '` NOT LIKE "%' . $item->data . '%"';
                    $item->data = '';
                    break;
                case 'nu':
                    $where .= '`' . $item->field . '` IS NULL';
                    $item->data = '';
                    break;
                case 'nn':
                    $where .= '`' . $item->field . '` IS NOT NULL';
                    $item->data = '';
                    break;
                case 'in':
                    $data = explode(",", $item->data);
                    $where .= '`' . $item->field . '` IN(';
                    foreach ($data as $in) {
                        $where .= '?,';
                        $whereParams[] = $in;
                    }
                    $where = trim($where, ',') . ')';
                    $item->data = '';
                    break;
                case 'ni':
                    $data = explode(",", $item->data);
                    $where .= '`' . $item->field . '` NOT IN(';
                    foreach ($data as $in) {
                        $where .= '?,';
                        $whereParams[] = $in;
                    }
                    $where = trim($where, ',') . ')';
                    $item->data = '';
                    break;
            }
            $whereParams[] = $item->data;
        }

        $this->_addWhereToParams($where, $whereParams);

        return true;
    }
    
    /**
     * Add WHERE clause to query.
     * If you need modify where query - override THIS function
     * @param string $query
     * @param array $params 
     */
    protected function _addWhereToParams($query, $params)
    {
        if (!empty($query)) {
            $this->_whereStr = 'WHERE ' . $query;
            $this->_whereArr = $params;
        }
    }
}

?>
