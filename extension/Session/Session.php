<?php
/**
 * File: Session.php.
 * User: gron
 * Date: 3/25/12
 * Time: 10:38 PM
 */
namespace Session;

use Core\DI;
use JQueryGrid\JQGridResponce;
use JQueryGrid\JQGrid;

class Session extends JQGrid
{    
    private $_totalPages;
    private $_count;

    public function index()
    {
        include "Session/index.php";
    }

    /**
     * Get Data by Entities getCount() and getRows() methods
     * Entity get from DI by 'SessionEntity' name.
     * return json of JQGridResponce object
     */
    public function getData()
    {
        $this->_init();

        /**
         * @var SessionEntity $entity
         */
        $entity = DI::create('SessionEntity');
        $entity->init();
        $this->_count = $entity->getCount();

        if ($this->_count > 0) {
            $this->_totalPages = ceil($this->_count / $this->_limit);
        } else {
            $this->_totalPages = 0;
        }
        if ($this->_page > $this->_totalPages) {
            $this->_page = $this->_totalPages;
        }
        $start = $this->_limit * $this->_page - $this->_limit;

        $rows = $entity->getRows($start, $this->_limit, $this->_sort, $this->_order);

        $responce = $this->_createJQGridResponceObject($rows);

        echo json_encode($responce);
    }

    /**
     * Create JQGridResponce object by array with fields
     * @param array $data
     * @return \JQueryGrid\JQGridResponce 
     */
    protected function _createJQGridResponceObject(array $data)
    {
        $responce = new JQGridResponce();
        $responce->page = $this->_page;
        $responce->total = $this->_totalPages;
        $responce->records = $this->_count;
        foreach ($data as $item) {
            $object = new \stdClass();
            $object->key_id = $item['key_id'];
            $object->user_id = $item['user_id'];
            $object->last_ip = $item['last_ip'];
            $object->last_login = $item['last_login'];
            $responce->rows[] = $object;
        }

        return $responce;
    }
}
