<?php
/**
 * File: JQGridMapperIF.php
 * User: gron
 * Date: Mar 27, 2012
 * Time: 3:29:31 PM
 */
namespace JQueryGrid;

/**
 * Mapper interface for JQGrid
 */
interface JQGridMapperIF
{
    /**
     * You need override this constant in your mapper class
     * This is table name in DB
     */
    const TABLE_NAME = '';
    
    /**
     * Initialize class data (page,limit,sort,order) from $_GET.
     * If none - set dafault constant values
     */
    public function init();
}

?>
