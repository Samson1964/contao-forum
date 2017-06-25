<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   Schachbundesliga
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2016
 */

class Forum extends \Module
{

	protected $strTemplate = 'forum_threads';
	protected $subTemplate = 'forum_topics';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FORUM ###';
			$objTemplate->title = $this->name;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}
		else
		{
			// FE-Modus: URL mit allen möglichen Parametern auflösen
			\Input::setGet('thread', \Input::get('thread')); // ID des Threads
		}

		return parent::generate(); // Weitermachen mit dem Modul
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;		

		$this->import('FrontendUser', 'User');

		// Forum ausgeben
		if(\Input::get('thread'))
		{
			// Titel des Threads laden
			$objThread = \Database::getInstance()->prepare('SELECT title FROM tl_forum_threads WHERE id = ?')
							   				     ->execute(\Input::get('thread'));
			// Topics des aktuellen Threads laden
			$objTopics = \Database::getInstance()->prepare('SELECT t.text, m.username, t.topicdate FROM tl_forum_topics t INNER JOIN tl_member m ON (t.name = m.id) WHERE published = ? AND pid = ? ORDER BY topicdate ASC')
 						   				         ->execute(1, \Input::get('thread'));
			
			$topics = array();
			if($objTopics->numRows > 0)
			{
				// Datensätze anzeigen
				while($objTopics->next()) 
				{
					$class = ($class == 'odd') ? 'even' : 'odd';
					$topics[] = array
					(
						'text' 		=> $objTopics->text,
						'name'	 	=> $objTopics->username,
						'topicdate'	=> date("d.m.Y H:i", $objTopics->topicdate),
						'class' 	=> $class,
					);
				}
			}

			// Template füllen
			$this->Template = new \FrontendTemplate($this->subTemplate);
			$this->Template->threadname = $objThread->title;
			$this->Template->category = $this->forum_category;
			$this->Template->thread = \Input::get('thread');
			$this->Template->topics = $topics;
			$this->Template->form = $this->SendTopicForm();
			$this->Template->username = $this->User->username;
		}
		else
		{
			// Threads der aktuellen Kategorie laden
			$objThreads = \Database::getInstance()->prepare('SELECT t.id, t.title, m.username, t.actdate, t.initdate FROM tl_forum_threads t INNER JOIN tl_member m ON (t.name = m.id) WHERE published = ? AND pid = ? ORDER BY actdate DESC')
							   				      ->execute(1, $this->forum_category);
			
			$threads = array();
			if($objThreads->numRows > 0)
			{
				// Datensätze anzeigen
				while($objThreads->next()) 
				{
					$class = ($class == 'odd') ? 'even' : 'odd';
					$threads[] = array
					(
						'title' 	=> $objThreads->title,
						'link'  	=> \Controller::generateFrontendUrl($objPage->row(), '/thread/'.$objThreads->id),
						'name'	 	=> $objThreads->username,
						'actdate'	=> date("d.m.Y H:i", $objThreads->actdate),
						'initdate'	=> date("d.m.Y H:i", $objThreads->initdate),
						'class' 	=> $class,
					);
				}
			}

			// Template füllen
			$this->Template->category = $this->forum_category;
			$this->Template->threads = $threads;
			$this->Template->form = $this->SendThreadForm();
			$this->Template->username = $this->User->username;
		}

	}

	protected function SendThreadForm()
	{
		global $objPage;
		
		$this->import('FrontendUser', 'User');
		
		$dca = array
		(
			'category' => array
			(
				'inputType' => 'hidden',
				'default'   => $this->forum_category,
			),
			'member' => array
			(
				'inputType' => 'hidden',
				'default'   => $this->User->id,
			),
			'title' => array
			(
				'label'		=> 'Titel',
				'inputType' => 'text',
				'eval'		=> array('mandatory'=>true, 'class'=>'form-control')
			),
			'text' => array
			(
				'label'		=> 'Text',
				'inputType' => 'textarea',
				'eval'		=> array('mandatory'=>true, 'rte'=>'tinyMCE', 'class'=>'form-control')
			),
			'submit' => array
			(
				'label' 	=> 'Anlegen',
				'eval'		=> array('class'=>'btn btn-primary'),
				'inputType' => 'submit'
			)
		);
		
		$frm = new Formular('linkform');
		$frm->setDCA($dca);	
		$frm->setConfig('generateFormat','<div>%label %field %error </div>');
		$frm->setConfig('attributes',array('tableless'=>true));
		if($frm->isSubmitted() && $frm->validate())
		{
			$this->saveNewThread($frm->getData());
			header('Location:'.\Controller::generateFrontendUrl($objPage->row()));
			return '<div class="notice">'.$GLOBALS['TL_LANG']['MSC']['forum_confirm'].'</div>';
		}
		else
		{
			return $frm->parse();
		}

	}

	protected function saveNewThread($data)
	{
		//print_r($data);
		$zeit = time();

		// Threads-Tabelle aktualisieren
		$set = array
		(
			'pid' 		=> $data['category'],
			'name' 		=> $data['member'],
			'tstamp' 	=> $zeit,
			'initdate' 	=> $zeit,
			'actdate' 	=> $zeit,
			'title' 	=> $data['title'],
			'published' => 1,
		);
		$objThread = \Database::getInstance()->prepare('INSERT INTO tl_forum_threads %s')
										     ->set($set)
										     ->execute();

		// Topics-Tabelle aktualisieren
		$set = array
		(
			'pid' 		=> $objThread->insertId,
			'tstamp' 	=> $zeit,
			'topicdate'	=> $zeit,
			'name' 		=> $data['member'],
			'title' 	=> $data['title'],
			'text' 		=> $data['text'],
			'published' => 1,
		);
		$objTopic = \Database::getInstance()->prepare('INSERT INTO tl_forum_topics %s')
										    ->set($set)
										    ->execute();

		// Mailinfo erstellen, zuerst Mails aus Topics auslesen
		$objTopics = \Database::getInstance()->prepare('SELECT t.name, m.email AS adresse, m.firstname, m.lastname, m.username FROM tl_forum_topics t INNER JOIN tl_member m ON (t.name = m.id) WHERE published = ? AND pid = ?')
 						   				     ->execute(1, $objTopic->insertId);
			
		$mails = array();
		if($objTopics->numRows > 0)
		{
			// Datensätze auswerten
			while($objTopics->next()) 
			{
				$mails[] = $objTopics->firstname.' '.$objTopics->lastname.' <'.$objTopics->adresse.'>';
			}
		}

		$mails = array_unique($mails); // Doppelte Adressen entfernen
		
		// Email verschicken
		$objEmail = new \Email();
		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = 'Neues Thema in Swifteliblue\'s Leichtgewichte-Blog';
        
		// Kommentar zusammenbauen
		$objEmail->text = 'Titel: '.$data['text']."\n\nText: ".$data['text']."\n\nhttp://leichtgewicht.swifteliblue.de/";
		$objEmail->sendTo(array($GLOBALS['TL_ADMIN_NAME'].' <'.$GLOBALS['TL_ADMIN_EMAIL'].'>'));  
  	}
	
	protected function SendTopicForm()
	{
		global $objPage;
		
		$this->import('FrontendUser', 'User');
		
		$dca = array
		(
			'category' => array
			(
				'inputType' => 'hidden',
				'default'   => $this->forum_category,
			),
			'thread' => array
			(
				'inputType' => 'hidden',
				'default'   => \Input::get('thread'),
			),
			'member' => array
			(
				'inputType' => 'hidden',
				'default'   => $this->User->id,
			),
			'text' => array
			(
				'label'		=> 'Text',
				'inputType' => 'textarea',
				'eval'		=> array('mandatory'=>true, 'rte'=>'tinyMCE', 'class'=>'form-control')
			),
			'submit' => array
			(
				'label' 	=> 'Absenden',
				'eval'		=> array('class'=>'btn btn-primary'),
				'inputType' => 'submit'
			)
		);
		
		$frm = new Formular('linkform');
		$frm->setDCA($dca);	
		$frm->setConfig('generateFormat','<div>%label %field %error </div>');
		$frm->setConfig('attributes',array('tableless'=>true));
		if($frm->isSubmitted() && $frm->validate())
		{
			$this->saveNewTopic($frm->getData());
			header('Location:'.$this->Environment->requestUri);
			return '<div class="notice">'.$GLOBALS['TL_LANG']['MSC']['forum_confirm'].'</div>';
		}
		else
		{
			return $frm->parse();
		}

	}

	protected function saveNewTopic($data)
	{
		//print_r($data);
		$zeit = time();

		// Threads-Tabelle aktualisieren
		$set = array
		(
			'actdate' 	=> $zeit,
		);
		$objThread = \Database::getInstance()->prepare('UPDATE tl_forum_threads %s WHERE id = ?')
										     ->set($set)
										     ->execute($data['thread']);

		// Topics-Tabelle aktualisieren
		$set = array
		(
			'pid' 		=> $data['thread'],
			'tstamp' 	=> $zeit,
			'topicdate'	=> $zeit,
			'name' 		=> $data['member'],
			'text' 		=> $data['text'],
			'published' => 1,
		);
		$objTopic = \Database::getInstance()->prepare('INSERT INTO tl_forum_topics %s')
										    ->set($set)
										    ->execute();

		// Mailinfo erstellen, zuerst Mails aus Topics auslesen
		$objTopics = \Database::getInstance()->prepare('SELECT t.name, m.email AS adresse, m.firstname, m.lastname, m.username FROM tl_forum_topics t INNER JOIN tl_member m ON (t.name = m.id) WHERE published = ? AND pid = ?')
 						   				     ->execute(1, $data['thread']);
			
		$mails = array();
		if($objTopics->numRows > 0)
		{
			// Datensätze auswerten
			while($objTopics->next()) 
			{
				$mails[] = $objTopics->firstname.' '.$objTopics->lastname.' <'.$objTopics->adresse.'>';
			}
		}

		$mails[] = $GLOBALS['TL_ADMIN_NAME'].' <'.$GLOBALS['TL_ADMIN_EMAIL'].'>';  
		$mails = array_unique($mails); // Doppelte Adressen entfernen
		
		// Email verschicken
		$objEmail = new \Email();
		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = 'Neuer Beitrag in Swifteliblue\'s Leichtgewichte-Blog';
        
		// Kommentar zusammenbauen
		$objEmail->text = 'Text: '.$data['text']."\n\nhttp://leichtgewicht.swifteliblue.de/";
		$objEmail->sendTo($mails);  
  	}
	
}
