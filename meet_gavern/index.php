<?php

/**
 *
 * Main file
 *
 * @version             4.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2012 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;
// to avoid the problems
if(!defined('DS')) {
 define('DS', DIRECTORY_SEPARATOR);
}
// enable showing errors in PHP
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors','On');
// include framework classes and files
require_once('lib/gk.framework.php');
// run the framework
$tpl = new GKTemplate($this);

// EOF
