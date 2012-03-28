<?php
/**
 * File: JQGridEntityIF.php
 * User: gron
 * Date: Mar 27, 2012
 * Time: 3:29:31 PM
 */
namespace JQueryGrid;

interface JQGridEntityIF
{
    /**
     * You need override this constant in your entity class
     */
    const TABLE_NAME = '';
    
    public function init();
}

?>
