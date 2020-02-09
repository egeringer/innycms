<?php
/**
 * Table Definition for user_site
 */
require_once dirname(__FILE__).'/AudDataObject.php';

class DataObjects_Innydb_user_site extends AudDataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'innydb_user_site';                       // table name
    public $id_user;                         // int(10)  not_null primary_key multiple_key unsigned group_by
    public $id_site;                         // int(10)  not_null primary_key multiple_key unsigned group_by
    public $status;                          // char(1)  not_null
    public $permission;                      // blob(4294967295)  not_null blob
    public $aud_ins_date;                    // datetime(19)  not_null
    public $aud_upd_date;                    // datetime(19)  not_null
    public $aud_ins_user;                    // varchar(100)  not_null
    public $aud_upd_user;                    // varchar(100)  not_null

    /* Static get */
    static function staticGet($k, $v = null) { return parent::staticGet('DataObjects_Innydb_user_site',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
