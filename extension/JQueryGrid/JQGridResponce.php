<?php
/**
 * File: JQGridResponce.php.
 * User: gron
 * Date: 3/25/12
 * Time: 11:55 PM
 */
namespace JQueryGrid;

/**
 * Structure (class with no methods and only public fields).
 * JQGrid return object.
 */
class JQGridResponce
{
    public $page=0;
    public $total=0;
    public $records=0;
    public $rows=array();
}
