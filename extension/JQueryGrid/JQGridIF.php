<?php
/**
 * File: JQGridIF.php.
 * User: gron
 * Date: Mar 27, 2012
 * Time: 3:30:35 PM
 */

namespace JQueryGrid;

/**
 * Interface for creating JQGrid resonse object and JQGrid itself
 */
interface JQGridIF
{
    /**
     * Default page, if nothing in $_GET['page'] was given
     */

    const DEFAULT_PAGE = 1;
    /**
     * Default limit, if nothing in $_GET['limit'] was given
     */
    const DEFAULT_LIMIT = 20;
    /**
     * Default sort fields, if nothing in $_GET['sidx'] was given  
     */
    const DEFAULT_SORT = '1';
    /**
     * Default sort order, if nothing in $_GET['sord'] was given
     */
    const DEFAULT_ORDER = '';

    /**
     * Return json of JQGridResponse object
     */
    public function getData();
}

?>
