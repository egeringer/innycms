<?php
/**
 * Table Definition for bucket_chunk
 */
require_once dirname(__FILE__).'/AudDataObject.php';

class DataObjects_Innydb_bucket_chunk extends AudDataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'innydb_bucket_chunk';                    // table name
    public $id_bucket_chunk;                 // int(10)  not_null primary_key unsigned auto_increment group_by
    public $id_bucket;                       // int(10)  not_null multiple_key unsigned group_by
    public $next_chunk;                      // int(10)  unsigned group_by
    public $data;                            // blob(16777215)  not_null blob
    public $aud_ins_date;                    // datetime(19)  not_null
    public $aud_upd_date;                    // datetime(19)  not_null
    public $aud_ins_user;                    // varchar(100)  not_null
    public $aud_upd_user;                    // varchar(100)  not_null

    /* Static get */
    static function staticGet($k, $v = null) { return parent::staticGet('DataObjects_Innydb_bucket_chunk',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
