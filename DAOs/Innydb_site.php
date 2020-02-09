<?php
/**
 * Table Definition for site
 */
require_once dirname(__FILE__).'/AudDataObject.php';

class DataObjects_Innydb_site extends AudDataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'innydb_site';                            // table name
    public $id_site;                         // int(10)  not_null primary_key unsigned auto_increment group_by
    public $name;                            // varchar(100)  not_null
    public $public_id;                       // varchar(40)  not_null unique_key multiple_key
    public $url;                             // varchar(200)  not_null
    public $metadata;                        // blob(4294967295)  blob
    public $configs;                         // blob(4294967295)  blob
    public $status;                          // char(1)  not_null
    public $aud_ins_date;                    // datetime(19)  not_null
    public $aud_upd_date;                    // datetime(19)  not_null
    public $aud_ins_user;                    // varchar(100)  not_null
    public $aud_upd_user;                    // varchar(100)  not_null

    /* Static get */
    static function staticGet($k, $v = null) { return parent::staticGet('DataObjects_Innydb_site',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
