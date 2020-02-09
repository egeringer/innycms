<?php
/**
 * Project: Inny CMS
 * File: common.php
 *
 * @copyright 2007-2008 Dokko Group
 * @author Dokko Group <info at dokkogroup dot com dot ar>
 */
################################################################################
define('SMARTY_RESOURCE_CHAR_SET', 'UTF-8');
require_once dirname(__FILE__).'/../commons/password_compat.php';
require_once dirname(__FILE__).'/../libs/Smarty.class.php';
require_once dirname(__FILE__).'/../denko/dk.denko.php';
require_once dirname(__FILE__).'/../denko/L10N.php';
require_once dirname(__FILE__).'/../commons/inny/InnyCMS.php';
################################################################################

$availableLangs = array('es','en');
if(empty($_COOKIE['lang']) || !in_array($_COOKIE['lang'], $availableLangs)){
    setCookie('lang','en');
    $_COOKIE['lang'] = 'en';
}

L10N::setLang($_COOKIE['lang']);

# Seteo el charset UTF-8 (para request de AJAX)
header('content-type: text/html; charset=utf-8');

# Seteo el locale
setlocale(LC_ALL,'es_AR');

# Se corrigen URLs
Denko::fixUrl();

# Se abre la DB
$dbConfFile=dirname(__FILE__).'/../config.json.local';
if(!file_exists($dbConfFile)) $dbConfFile=dirname(__FILE__).'/../DB.ini.local';
if(!file_exists($dbConfFile)) $dbConfFile=dirname(__FILE__).'/../config.json';
if(!file_exists($dbConfFile)) $dbConfFile=dirname(__FILE__).'/../DB.ini';
if(!file_exists($dbConfFile)) $dbConfFile=null;

Denko::openDB($dbConfFile);

# Verifico si se debe abrir la session
if(!isset($INNY_START_SESSION) || $INNY_START_SESSION === true){
    Denko::sessionStart();
}
