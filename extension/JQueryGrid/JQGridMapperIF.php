<?php
/**
 * File: JQGridMapperIF.php
 * User: gron
 * Date: Mar 27, 2012
 * Time: 3:29:31 PM
 */
namespace JQueryGrid;

interface JQGridMapperIF
{
    /**
     * You need override this constant in your mapper class
     */
    const TABLE_NAME = '';
    
    /**
     * Initialize class data (page,limit,sort,order) from $_GET.
     * If none - set dafault constant values
     */
    public function init();
}

?>
