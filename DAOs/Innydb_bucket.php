<?php
/**
 * Table Definition for bucket
 */
require_once dirname(__FILE__).'/AudDataObject.php';

class DataObjects_Innydb_bucket extends AudDataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'innydb_bucket';                          // table name
    public $id_bucket;                       // int(10)  not_null primary_key unique_key unsigned auto_increment group_by
    public $size;                            // int(10)  unsigned group_by
    public $name;                            // varchar(200)  
    public $mime;                            // varchar(127)  not_null
    public $type;                            // varchar(45)  
    public $tags;                            // blob(65535)  multiple_key blob
    public $count;                           // int(10)  not_null unsigned group_by
    public $usages;                          // blob(4294967295)  blob
    public $hash;                            // char(128)  not_null
    public $aud_ins_date;                    // datetime(19)  not_null
    public $aud_upd_date;                    // datetime(19)  not_null
    public $aud_ins_user;                    // varchar(100)  not_null
    public $aud_upd_user;                    // varchar(100)  not_null

    /* Static get */
    static function staticGet($k, $v = null) { return parent::staticGet('DataObjects_Innydb_bucket',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
