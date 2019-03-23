<?php

/**
 * Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 *
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
use Contao\Controller;

/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
if(file_exists('../../../initialize.php')) require('../../../initialize.php');
else require('../../../../system/initialize.php');


/**
 * Class Erinnerung
 *
 */
class Erinnerung 
{
	public function __construct()
	{
	}

	public function run()
	{
		$zeit = time();
		$tag = 3600 * 24;
		$start = $zeit - ($tag * 2);
		$url = 'http://leichtgewicht.swifteliblue.de/';

    	$objMember = \Database::getInstance()->prepare('SELECT * FROM tl_member')
    									     ->execute();
		
		while($objMember->next()) 
		{
			if($objMember->currentLogin < $start)
			//if($objMember->username == 'Swifti')
			{
				// Tage errechnen
				//$tage = bcdiv($zeit - $objMember->lastLogin, 86400, 0);
				$tage = sprintf("%01d", ($zeit - $objMember->currentLogin) / 86400);
				// Email verschicken
				$objEmail = new \Email();
				$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
				$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
				$objEmail->subject = 'Swifteliblue vermisst Dich!';
				$mails = array($objMember->firstname.' '.$objMember->lastname.' <'.$objMember->email.'>');
				// Text zusammenbauen
				$objEmail->html = '<p>Liebe/s '.$objMember->username.',</p>';
				$objEmail->html .= '<p>Swifteliblue vermisst Dich! Du hast mich das letzte Mal <b>vor ueber '.$tage.' Tag(en)</b> besucht!</p>';
				$objEmail->html .= '<p>Bitte komm\' doch wieder...</p>';
				$objEmail->html .= '<p><img src="'.$url.'files/tiere/hund.jpg"></p>';
				$objEmail->html .= '<p><a href="'.$url.'">'.$url.'</a></p>';
				$objEmail->sendTo($mails);  
			}
		} 


	}
}

/**
 * Instantiate controller
 */
$go = new Erinnerung();
$go->run();

