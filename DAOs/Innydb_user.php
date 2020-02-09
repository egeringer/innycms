<?php
/**
 * Table Definition for user
 */
require_once dirname(__FILE__).'/AudDataObject.php';

class DataObjects_Innydb_user extends AudDataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'innydb_user';         // table name
    public $id_user;                         // int(10)       not_null primary_key unsigned auto_increment group_by
    public $username;                        // varchar(20)   not_null unique_key
    public $password;                        // varchar(100)  not_null
    public $name;                            // varchar(100)  not_null
    public $lastname;                        // varchar(100)  not_null
    public $email;                           // varchar(100)  not_null
    public $role;                            // varchar(10)   not_null
    public $status;                          // char(1)       not_null
    public $aud_ins_date;                    // datetime(19)  not_null
    public $aud_upd_date;                    // datetime(19)  not_null
    public $aud_ins_user;                    // varchar(100)  not_null
    public $aud_upd_user;                    // varchar(100)  not_null

    /* Static get */
    static function staticGet($k, $v = null) { return parent::staticGet('DataObjects_Innydb_user',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    public function insert(){
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::insert();
    }

    public function update($dataObject = false){
        if($dataObject === false) throw new Exception('Previous DAO User is required to perform an update.');
        if($this->password != $dataObject->password) $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::update($dataObject);
    }
}