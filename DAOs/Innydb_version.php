<?php
/**
 * Table Definition for innydb_version
 */
require_once 'DB/DataObject.php';

class DataObjects_Innydb_version extends DB_DataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'innydb_version';                  // table name
    public $version;                         // int(11)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Innydb_version',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
