<?php
/**
 * Table Definition for document
 */

class DataObjects_Innydb_document extends AudDataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'innydb_document';                            // table name
    public $id_document;                     // int(10)  not_null primary_key unsigned auto_increment group_by
    public $public_id;                       // varchar(10)  not_null multiple_key
    public $collection_name;                 // varchar(100)  not_null multiple_key
    public $site_name;                       // varchar(40)  not_null multiple_key
    public $position;                        // int(10)  not_null unsigned group_by
    public $status;                          // int(1)  not_null multiple_key unsigned zerofill group_by
    public $field1;                          // blob(4294967295)  blob
    public $field2;                          // blob(4294967295)  blob
    public $field3;                          // blob(4294967295)  blob
    public $field4;                          // blob(4294967295)  blob
    public $field5;                          // blob(4294967295)  blob
    public $field6;                          // blob(4294967295)  blob
    public $field7;                          // blob(4294967295)  blob
    public $field8;                          // blob(4294967295)  blob
    public $field9;                          // blob(4294967295)  blob
    public $field10;                         // blob(4294967295)  blob
    public $field11;                         // blob(4294967295)  blob
    public $field12;                         // blob(4294967295)  blob
    public $field13;                         // blob(4294967295)  blob
    public $field14;                         // blob(4294967295)  blob
    public $field15;                         // blob(4294967295)  blob
    public $field16;                         // blob(4294967295)  blob
    public $field17;                         // blob(4294967295)  blob
    public $field18;                         // blob(4294967295)  blob
    public $field19;                         // blob(4294967295)  blob
    public $field20;                         // blob(4294967295)  blob
    public $files;                           // blob(16777215)  blob
    public $aud_ins_date;                    // datetime(19)  not_null
    public $aud_upd_date;                    // datetime(19)  not_null
    public $aud_ins_user;                    // varchar(100)  not_null
    public $aud_upd_user;                    // varchar(100)  not_null
    public $draft;                           // blob(4294967295)  blob

    /* Static get */
    static function staticGet($k, $v = null) { return parent::staticGet('DataObjects_Innydb_document',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    /**
     * @access public
     * @return integer|boolean:FALSE
     */
    public function insert(){
        if($this->collection_name){
            $daoDocument = Denko::daoFactory('innydb_document');
            $daoDocument->collection_name = $this->collection_name;
            $count = $daoDocument->find();
            $this->position = $count + 1;
            $daoDocument->free();
        }
        return parent::insert();
    }

    /**
     * @access public
     * @return integer|boolean:FALSE
     */
    public function delete($useWhere = false){
        $collectionName = $this->collection_name;
        $position = $this->position;
        InnyCMS::updateBucketQuantities($this->public_id,$this->collection_name,json_decode($this->files,true),array());
        $delete = parent::delete();
        if($delete && !empty($position)){
            $daoDocument = Denko::daoFactory('innydb_document');
            $daoDocument->query("update innydb_document set position=position-1 where collection_name='$collectionName' and position > '$position'");
        }
        return $delete;
    }
}
################################################################################
