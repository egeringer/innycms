<?php
/* Mega Updater - UpdateFunctions
 * Versión 0.1
 * Propiedad de DokkoGrpup
 *
 * En este archivo se encuentran las funciones
 * de actualización específicas de cada proyecto.
 *
 * By. FBricker
 */

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
// Funciones de actualizacion

function updateV1(){
    report("InnyCMS 2.0 begins Here!");
    return true;
}

function updateV2(){
    return execQuery('ALTER TABLE `item` DROP FOREIGN KEY `FK_collection_name`;',false,[1091]);
}

function updateV3(){
    report('Adding status support to InnyCMS 2.0 item table',R_ACTION);
    return execQuery("ALTER TABLE `item`
                            CHANGE COLUMN `public_id` `public_id` VARCHAR(10) NOT NULL AFTER `id_item`,
                            CHANGE COLUMN `collection_name` `collection_name` VARCHAR(100) NOT NULL AFTER `public_id`,
                            CHANGE COLUMN `site_name` `site_name` VARCHAR(40) NOT NULL AFTER `collection_name`,
                            CHANGE COLUMN `position` `position` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `site_name`,
                            ADD COLUMN `status` INT(1) UNSIGNED ZEROFILL NOT NULL DEFAULT '1' AFTER `position`,
                            ADD COLUMN `draft` LONGTEXT NULL DEFAULT NULL AFTER `aud_upd_user`,
                            ADD INDEX `status_INDEX` (`status` ASC);",false,[1091]);
}

function updateV4(){
    $tables = array("bucket","bucket_chunk","collection","item","site","user","user_site");
    $done = true;
    foreach ($tables as $key => $table){
        report("Adding innydb_ prefix to table $table",R_ACTION);
        $result = execQuery("ALTER TABLE `$table` RENAME TO `innydb_$table`;",false,[1091]);
        if($result) report("Added innydb_ prefix to table $table",R_ACTION);
        else {
            $done = false;
            report("Cant add innydb_ prefix to table $table",R_ACTION);
        }
    }
    return $done;
}


function updateV5(){
    report("Renaming innydb_item to innydb_document",R_ACTION);
    $result = execQuery("ALTER TABLE `innydb_item` CHANGE COLUMN `id_item` `id_document` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, RENAME TO `innydb_document`;",false,[1091]);
    if($result) report("Renamed innydb_document to innydb_document",R_ACTION);
    else report("Cant rename innydb_document to innydb_document",R_ACTION);
    return $result;
}

function updateV6(){
    report("Removing address and phone fields from innydb_user table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_user` DROP COLUMN `phone`, DROP COLUMN `address`, CHANGE COLUMN `email` `email` VARCHAR(100) NOT NULL ;",false,[1091]);
}

function updateV7(){
    report("Removing enabled and deleted and adding status fields at innydb_user table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_user` DROP COLUMN `deleted`, CHANGE COLUMN `enabled` `status` CHAR(1) NOT NULL DEFAULT '1' ;",false,[1091]);
}

function updateV8(){
    report("Removing enabled, maintenance and deleted and adding status fields at innydb_site table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_site` DROP COLUMN `maintenance`, DROP COLUMN `deleted`, CHANGE COLUMN `aud_ins_user` `aud_ins_user` VARCHAR(100) NOT NULL AFTER `aud_upd_date`, CHANGE COLUMN `aud_upd_user` `aud_upd_user` VARCHAR(100) NOT NULL AFTER `aud_ins_user`, CHANGE COLUMN `enabled` `status` CHAR(1) NOT NULL DEFAULT '1' ;",false,[1091]);
}

function updateV9(){
    report("Renaming deleted to status fields at innydb_user_site table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_user_site` CHANGE COLUMN `deleted` `status` CHAR(1) NOT NULL DEFAULT '1' ;",false,[1091]);
}

function updateV10(){
    report("Adding FK to innydb_document table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_document` DROP FOREIGN KEY `FK_collection_name_item`",false,[1091])
        && execQuery("ALTER TABLE `innydb_document` DROP FOREIGN KEY `FK_site_name`",false,[1091])
        && execQuery("ALTER TABLE `innydb_document` ADD CONSTRAINT `FK_site_name` FOREIGN KEY (`site_name`) REFERENCES `innydb_site` (`public_id`) ON DELETE RESTRICT ON UPDATE CASCADE, ADD CONSTRAINT `FK_collection_name` FOREIGN KEY (`collection_name`) REFERENCES `innydb_collection` (`name`) ON DELETE RESTRICT ON UPDATE CASCADE;",false,[1091]);
}

function updateV11(){
    report("Changing FK to innydb_user table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_user` DROP INDEX `unique_username` , ADD UNIQUE INDEX `username_UNIQUE` (`username` ASC), ADD INDEX `username_INDEX` (`username` ASC);",false,[1091]);
}

function updateV12(){
    report("Dropping Constraints in innydb_user_site table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_user_site` DROP FOREIGN KEY `FK_id_user`, DROP FOREIGN KEY `FK_id_site`, DROP INDEX `FK_id_site_idx` , DROP INDEX `FK_id_user_idx` , DROP INDEX `unique_relation` ;",false,[1091]);
}

function updateV13(){
    report("Altering username field in innydb_user table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_user` CHANGE COLUMN `username` `username` VARCHAR(40) NOT NULL ;",false,[1091]);
}

function updateV14(){
    report("Altering id_user and id_site field in innydb_user_site table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_user_site` CHANGE COLUMN `id_user` `id_user` VARCHAR(40) NOT NULL , CHANGE COLUMN `id_site` `id_site` VARCHAR(40) NOT NULL ;",false,[1091]);
}

function updateV15(){
    report("Update id_user field in innydb_user_site table",R_ACTION);
    return execQuery("UPDATE `innydb_user_site` c set c.`id_user` = (select `username` from `innydb_user` s where c.`id_user` = s.`id_user`);",false,[1091]);
}

function updateV16(){
    report("Update id_site field in innydb_user_site table",R_ACTION);
    return execQuery("UPDATE `innydb_user_site` c set c.`id_site` = (select `public_id` from `innydb_site` s where c.`id_site` = s.`id_site`);",false,[1091]);
}

function updateV17(){
    report("Restoring constraints to innydb_user_site table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_user_site` ADD INDEX `id_user_INDEX` (`id_user` ASC), ADD INDEX `id_site_INDEX` (`id_site` ASC), ADD INDEX `user_site_INDEX` (`id_site` ASC, `id_user` ASC), ADD UNIQUE INDEX `user_site_UNIQUE` (`id_site` ASC, `id_user` ASC), ADD CONSTRAINT `id_user_FK` FOREIGN KEY (`id_user`) REFERENCES `innydb_user` (`username`) ON DELETE RESTRICT ON UPDATE CASCADE, ADD CONSTRAINT `id_site_FK` FOREIGN KEY (`id_site`) REFERENCES `innydb_site` (`public_id`) ON DELETE RESTRICT ON UPDATE CASCADE;",false,[1091]);
}

function updateV18(){
    report("Dropping dse_category table",R_ACTION);
    return execQuery("DROP TABLE `dse_category`;",false,[1091]);
}

function updateV19(){
    report("Dropping dse_document_category table",R_ACTION);
    return execQuery("DROP TABLE `dse_document_category`;",false,[1091]);
}

function updateV20(){
    report("Dropping dse_synonim table",R_ACTION);
    return execQuery("DROP TABLE `dse_synonim`;",false,[1091]);
}

function updateV21(){
    report("Dropping dse indexes on innydb_document table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_document` 
                                DROP INDEX `CATEGORY_NAMES_INDEX` ,
                                DROP INDEX `CATEGORY_IDS_INDEX` ,
                                DROP INDEX `FULL_TEXT_INDEX` ,
                                DROP INDEX `FIELD_3_TEXT_INDEX` ,
                                DROP INDEX `FIELD_2_TEXT_INDEX` ,
                                DROP INDEX `FIELD_1_TEXT_INDEX` ;
                                ;",false,[1091]);
}

function updateV22(){
    report("Dropping dse columns on innydb_document table",R_ACTION);
    return execQuery("ALTER TABLE `innydb_document` 
                                DROP COLUMN `so_field_1`,
                                DROP COLUMN `so_field_0`,
                                DROP COLUMN `category_names`,
                                DROP COLUMN `category_ids`,
                                DROP COLUMN `field_2`,
                                DROP COLUMN `field_1`,
                                DROP COLUMN `field_0`;",false,[1091]);
}