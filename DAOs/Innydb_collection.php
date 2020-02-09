<?php
require_once dirname(__FILE__).'/AudDataObject.php';

/**
 * Table Definition for collection
 */
class DataObjects_Innydb_collection extends AudDataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'innydb_collection';                      // table name
    public $id_collection;                   // int(10)  not_null primary_key unsigned auto_increment group_by
    public $public_id;                       // varchar(10)  not_null multiple_key
    public $name;                            // varchar(100)  not_null multiple_key
    public $site_name;                       // varchar(40)  not_null multiple_key
    public $metadata;                        // blob(4294967295)  blob
    public $aud_ins_date;                    // datetime(19)  not_null
    public $aud_upd_date;                    // datetime(19)  not_null
    public $aud_ins_user;                    // varchar(100)  not_null
    public $aud_upd_user;                    // varchar(100)  not_null

    /* Static get */
    static function staticGet($k, $v = null) { return parent::staticGet('DataObjects_Innydb_collection',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
################################################################################