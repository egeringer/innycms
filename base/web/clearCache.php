<?php
require_once 'common.php';

function DELETE_RECURSIVE_DIRS($dirname){ // recursive function to delete
    if(!is_dir($dirname)){
        return false;
    }
    $dir_handle=opendir($dirname);
    while($file=readdir($dir_handle)){
        if($file!="." && $file!=".." && $file!=".git" && $file!=".gitignore"){
            if(!is_dir($dirname."/".$file)){
                unlink ($dirname."/".$file);
            } else {
                DELETE_RECURSIVE_DIRS($dirname."/".$file);
            }
        }
    }
    closedir($dir_handle);
    @rmdir($dirname);
    return true;
}

################################################################################
DELETE_RECURSIVE_DIRS('cache');
DELETE_RECURSIVE_DIRS('movie');
DELETE_RECURSIVE_DIRS('thumb');
DELETE_RECURSIVE_DIRS('image');
DELETE_RECURSIVE_DIRS('download');
DELETE_RECURSIVE_DIRS('templates_c');

Denko::redirect($_SERVER['HTTP_REFERER']);
