<?php

/**
 * File: JQGrid.php.
 * User: gron
 * Date: 3/25/12
 * Time: 8:06 PM
 */

namespace JQueryGrid;

use JQueryGrid\JQGridIF;

abstract class JQGrid implements JQGridIF
{

    protected $_page;
    protected $_limit;
    protected $_sort;
    protected $_order;

    /**
     * Initialize class data (page,limit,sort,order) from $_GET.
     * If none - set dafault constant values
     */
    protected function _init()
    {
        $this->_page = (!empty($_GET['page'])) ? $_GET['page'] : static::DEFAULT_PAGE;
        $this->_limit = (!empty($_GET['rows'])) ? $_GET['rows'] : static::DEFAULT_LIMIT;
        $this->_sort = (!empty($_GET['sidx'])) ? $_GET['sidx'] : static::DEFAULT_SORT;
        $this->_order = (!empty($_GET['sord'])) ? $_GET['sord'] : static::DEFAULT_ORDER;
    }

    /**
     * Get Data by Entities getCount() and getRows() methods
     * Mapper get from DI by name.
     * return json of JQGridResponce object
     */
    abstract public function getData();

    /**
     * Create JQGridResponce object by array with fields
     * @param array $data
     * @return \JQueryGrid\JQGridResponce 
     */
    abstract protected function _createJQGridResponceObject(array $data);
}
