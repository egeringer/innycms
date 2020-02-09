<?php
/**
 * AudDataObject abstraction for DB_DataObject
 */
require_once 'DB/DataObject.php';

class AudDataObject extends DB_DataObject {

    ///////////////////////////////////////////////////////////////////////////

    public function insert(){
        $currentDate = date('Y-m-d').' '.date('H:i:s');
        $this->aud_ins_date = $currentDate;
        $this->aud_upd_date = $currentDate;
        $this->aud_ins_user = InnyCMS::getUserProperty('username');
        $this->aud_upd_user = InnyCMS::getUserProperty('username');
        return parent::insert();
    }

    ///////////////////////////////////////////////////////////////////////////

    public function update($dataObject = false){
        $currentDate = date('Y-m-d').' '.date('H:i:s');
        $this->aud_upd_date = $currentDate;
        $this->aud_upd_user = InnyCMS::getUserProperty('username');
        return parent::update();
    }

    ///////////////////////////////////////////////////////////////////////////

}