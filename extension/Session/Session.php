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

class Session
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 20;
    const DEFAULT_SORT = '1';
    const DEFAULT_ORDER = '';

    private $page;
    private $limit;
    private $sort;
    private $order;

    private $totalPages;
    private $count;

    public function index()
    {
        include "Session/index.php";
    }

    public function init()
    {
        $this->page = (!empty($_GET['page'])) ? $_GET['page'] : self::DEFAULT_PAGE;
        $this->limit = (!empty($_GET['rows'])) ? $_GET['rows'] : self::DEFAULT_LIMIT;
        $this->sort = (!empty($_GET['sidx'])) ? $_GET['sidx'] : self::DEFAULT_SORT;
        $this->order = (!empty($_GET['sord'])) ? $_GET['sord'] : self::DEFAULT_ORDER;

    }

    public function getData()
    {
        $this->init();

        /**
         * @var SessionEntity $entity
         */
        $entity = DI::create('SessionEntity');
        $this->count = $entity->getCount();

        if ($this->count > 0) {
            $this->totalPages = ceil($this->count / $this->limit);
        } else {
            $this->totalPages = 0;
        }
        if ($this->page > $this->totalPages) {
            $this->page = $this->totalPages;
        }
        $start = $this->limit * $this->page - $this->limit;

        $rows = $entity->getRows($start, $this->limit, $this->sort, $this->order);

        $responce = $this->createJQGridResponceObject($rows);

        echo json_encode($responce);
    }

    private function createJQGridResponceObject(array $data)
    {
        $responce = new JQGridResponce();
        $responce->page = $this->page;
        $responce->total = $this->totalPages;
        $responce->records = $this->count;
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
