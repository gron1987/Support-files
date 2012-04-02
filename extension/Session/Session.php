<?php
/**
 * File: Session.php.
 * User: gron
 * Date: 3/25/12
 * Time: 10:38 PM
 */
namespace Session;

use Core\SL;
use JQueryGrid\JQGridResponse;
use JQueryGrid\JQGrid;

/**
 * Class for Session to render JQGrid
 */
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
     * Mapper get from SL by 'SessionMapper' name.
     * return json of JQGridResponse object
     */
    public function getData()
    {
        $this->_init();

        /**
         * @var SessionMapper $mapper
         */
        $mapper = SL::create('SessionMapper');
        $mapper->init();
        $this->_count = $mapper->getCount();

        if ($this->_count > 0) {
            $this->_totalPages = ceil($this->_count / $this->_limit);
        } else {
            $this->_totalPages = 0;
        }
        if ($this->_page > $this->_totalPages) {
            $this->_page = $this->_totalPages;
        }
        $start = $this->_limit * $this->_page - $this->_limit;

        $rows = $mapper->getRows($start, $this->_limit, $this->_sort, $this->_order);

        $response = $this->_createJQGridResponceObject($rows);

        echo json_encode($response);
    }

    /**
     * Create JQGridResponse object by array with fields
     * @param array $data
     * @return \JQueryGrid\JQGridResponse
     */
    protected function _createJQGridResponceObject(array $data)
    {
        $response = SL::create('JQGridResponse');
        $response->page = $this->_page;
        $response->total = $this->_totalPages;
        $response->records = $this->_count;
        foreach ($data as $item) {
            $object = new \stdClass();
            $object->key_id = $item['key_id'];
            $object->user_id = $item['user_id'];
            $object->last_ip = $item['last_ip'];
            $object->last_login = $item['last_login'];
            $response->rows[] = $object;
        }

        return $response;
    }
}
