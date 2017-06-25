<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   bdf
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

/**
 * Backend-Modul BdF anlegen und einfügen
 */

$GLOBALS['BE_MOD']['content']['forum'] = array
(
	'tables'         => array('tl_forum', 'tl_forum_threads', 'tl_forum_topics'),
	'icon'           => 'system/modules/forum/assets/images/icon.png',
);

$GLOBALS['FE_MOD']['forum'] = array
(
	'forum'          => 'Forum',
);  

// Standard-CSS einbinden
if(TL_MODE == 'FE') $GLOBALS['TL_CSS'][] = 'system/modules/forum/assets/css/style.css'; 
if(TL_MODE == 'BE') $GLOBALS['TL_CSS'][] = 'system/modules/forum/assets/css/be.css'; 
 