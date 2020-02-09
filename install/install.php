<?php
ini_set("display_errors",true);
ini_set("debug_level",E_ALL);
require_once '../commons/common.php';
header('content-type: text/html; charset=UTF-8');
define('DENKO_WEB_FOLDER','/install');

/**
 * Copy a file, or recursively copy a folder and its contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       int      $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry", $permissions);
    }

    // Clean up
    $dir->close();
    return true;
}

$installed = file_exists("templates_c/installed") ? true : false;
if($installed) Denko::redirect("./");

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['action']) && $_REQUEST['action'] == "install"){
    // First validate all params and then start full install
    $valid = true;
    if(empty($_REQUEST['username'])) $valid = false;
    if(empty($_REQUEST['databasename'])) $valid = false;
    if(empty($_REQUEST['adminusername'])) $valid = false;
    if(empty($_REQUEST['adminpassword'])) $valid = false;
    if(empty($_REQUEST['confirmadminpassword'])) $valid = false;
    if($_REQUEST['adminpassword'] != $_REQUEST['confirmadminpassword']) $valid = false;
    if(!$valid) Denko::redirect("./?error");
    $dbUser = $_REQUEST['username'];
    $dbPass = $_REQUEST['password'];
    $dbName = $_REQUEST['databasename'];
    $dbHost = $_REQUEST['databasehost'];
    echo "<html><head><title>Installing InnyCMS</title><style>.text-danger { color: orangered } .text-success { color:forestgreen } .text-warning { color: gold } .text-disabled { color: lightgray }</style></head><body><pre id='pre'>";

    echo "<h2>Installing InnyCMS...</h2>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<br/>";
    //
    // DB CREATION
    //

    echo "Creating Database ($dbName) SQL Script ";
    $data = file_get_contents('templates/database.sql.tpl');
    $data = str_replace('@@DB_NAME@@',$dbName, $data);
    $response = true;
    if(!is_writable(dirname(__FILE__).'/templates_c/')){
        $response = false;
    }else{
        $response = file_put_contents("templates_c/".$dbName.'.sql',$data);
    }

    if($response !== FALSE) echo "<span class='text-success'> &#10004; </span><br/>";
    else {
        echo "<span class='text-danger'> &#10008; </span><br/>";
        exit;
    }

    if(!empty($dbPass))
        $cmd = "mysql -u $dbUser -p$dbPass < templates_c/$dbName.sql";
    else
        $cmd = "mysql -u $dbUser < templates_c/$dbName.sql";

    system($cmd);
    unlink("templates_c/".$dbName.'.sql');

    echo "Imported Database ($dbName) SQL Script <span class='text-success'> &#10004; </span><br/>";

    //
    // DB.ini.local CREATION
    //

    echo "Creating DB.ini.local File";
    $data = file_get_contents('templates/DB.ini.local.tpl');
    $data = str_replace('@@DB_NAME@@',$dbName, $data);
    $data = str_replace('@@DB_USER@@',$dbUser, $data);
    $data = str_replace('@@DB_PASS@@',empty($dbPass)?'':":$dbPass", $data);
    $data = str_replace('@@DB_HOST@@',$dbHost, $data);
    if(!is_writable(dirname(__FILE__).'/../')){
        echo "<span class='text-danger'> &#10008; </span><br/>";
        exit;
    }else{
        file_put_contents(dirname(__FILE__).'/../DB.ini.local',$data);
        Denko::openDB(dirname(__FILE__).'/../DB.ini.local');
        echo "<span class='text-success'> &#10004; </span><br/>";
    }

    //
    // Create Symlinks
    //

    if($dbName != "inny_cms"){
        if(!is_writable(dirname(__FILE__).'/../DAOs/')){
            echo "Cannot Create Symlinks <span class='text-danger'> &#10008; </span><br/>";
        }else{
            if(!file_exists(dirname(__FILE__).'/../DAOs/'.$dbName.'.ini')){
                echo "Creating Symlinks ";
                $response = symlink('inny_cms.ini',dirname(__FILE__).'/../DAOs/'.$dbName.'.ini');
                $response2 = symlink('inny_cms.links.ini',dirname(__FILE__).'/../DAOs/'.$dbName.'.links.ini');
                if($response && $response2){
                    echo "<span class='text-success'> &#10004; </span><br/>";
                }else{
                    echo "<span class='text-danger'> &#10008; </span><br/>";
                }
            }
        }
    }

    //
    // Megaupdater
    //
    echo "Running MegaUpdater...<br/><br/>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<span class='text-disabled'>";
    system('cd "'.dirname(__FILE__).'/../megaUpdater"; php update.php');
    echo "</span>";
    echo "<br/>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<span class='text-warning'>============================================================</span><br/>";
    echo "<br/>";

    //
    // Create Admin User
    //

    $username = $_REQUEST['adminusername'];
    $password = $_REQUEST['adminpassword'];

    echo "Creating Admin User: $username ";

    $daoUser = Denko::daoFactory('innydb_user');
    $daoUser->username = $username;
    $daoUser->password = $password;
    $daoUser->name = "Sys";
    $daoUser->lastname = "Admin";
    $daoUser->enabled = "1";
    $daoUser->role = "sysadmin";
    $daoUser->deleted = "0";
    $response = $daoUser->insert();
    if($response)
        echo "<span class='text-success'> &#10004; </span><br/>";
    else
        echo "<span class='text-danger'> &#10008; </span><br/>";

    //
    // Mode
    //

    if($_REQUEST['mode'] === "website"){
        echo "Creating Base Project ";
        $home = dirname(__FILE__).'/../..';
        $base = dirname(__FILE__).'/../base';
        if(!is_writable(dirname(__FILE__).'/../..')){
            echo "<span class='text-danger'> &#10008; </span><br/>";
        }else{
            $response = xcopy($base,$home);
            if($response)
                echo "<span class='text-success'> &#10004; </span><br/>";
            else
                echo "<span class='text-danger'> &#10008; </span><br/>";
        }
        echo "Creating admin symlinks ";
        $response = symlink(dirname(__FILE__).'/../admin',dirname(__FILE__).'/../cms/admin');
        if($response)
            echo "<span class='text-success'> &#10004; </span><br/>";
        else
            echo "<span class='text-danger'> &#10008; </span><br/>";

        echo "Creating cms symlinks ";
        $response = symlink(dirname(__FILE__).'/../cms',dirname(__FILE__).'/../../web/cms');
        if($response)
            echo "<span class='text-success'> &#10004; </span><br/>";
        else
            echo "<span class='text-danger'> &#10008; </span><br/>";

    }
    //
    // Finished
    //
    file_put_contents("templates_c/installed","");

    echo "<br/>";
    echo "<h2>Installed InnyCMS...</h2>";
    echo "<span class='text-danger'>============================================================</span><br/>";
    echo "<span class='text-danger'>============================================================</span><br/>";
    echo "<span class='text-danger'>============================================================</span><br/>";
    echo "<br/>";
    echo "<b class='text-danger'>Instalation Completed. Remember to:</b><br/>";
    echo "<br/>";
    echo "&bullet; chmod+w admin folder<br/>";
    echo "&bullet; chmod+w admin/templates_c folder<br/>";
    echo "&bullet; chmod+w cms folder<br/>";
    echo "&bullet; chmod+w cms/templates_c folder<br/>";
    echo "&bullet; chmod+w web folder<br/>";
    echo "&bullet; chmod+w web/templates_c folder<br/>";
    echo "&bullet; update HOST.ini file<br/>";
    echo "&bullet; create a new site and use public_id in innycms.json file<br/>";
    echo "<br/>";
    echo "<span class='text-danger'>============================================================</span><br/>";
    echo "<span class='text-danger'>============================================================</span><br/>";
    echo "<span class='text-danger'>============================================================</span><br/>";
    echo "<a href='../admin'>Go to Admin Panel</a> or wait 60 seconds";
    echo "<script>setInterval(function(){var z = document.createElement('span');z.innerHTML = '.';document.getElementById('pre').appendChild(z);},1000);setTimeout(function(){window.location.href = '../admin'},60000);</script>";
    echo "</pre></body></html>";
}else{
    Denko::redirect("./");
}
