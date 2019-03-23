<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Table tl_forum_topics
 */
$GLOBALS['TL_DCA']['tl_forum_topics'] = array
(
 
    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_forum_threads',
		'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id'    	=> 'primary',
                'pid'   	=> 'index',
                'title' 	=> 'index',
                'topicdate'	=> 'index'
            )
        ),
    ),
 
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('topicdate ASC'),
            'headerFields'            => array('title'),
            'panelLayout'             => 'search,limit',
			'disableGrouping'         => true,
			'child_record_callback'   => array('tl_forum_topics', 'listTopics'),
			'child_record_class'      => 'no_padding',
			//'rootPaste'               => false
		),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ),
        ),
        'operations' => array
        (
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_forum_topics']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
			), 
            //'copy' => array
            //(
            //    'label'               => &$GLOBALS['TL_LANG']['tl_forum_topics']['copy'],
            //    'href'                => 'act=paste&mode=copy',
            //    'icon'                => 'copy.gif'
            //),
            'cut' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_forum_topics']['cut'],
                'href'                => 'act=paste&mode=cut',
                'icon'                => 'cut.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_forum_topics']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_forum_topics']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_forum_topics', 'toggleIcon') 
			),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_forum_topics']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),
 
	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('protected'), 
		'default'                     => '{title_legend},title,name,email;{text_legend},text;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{published_legend},published,start,stop'
	), 

	// Subpalettes
	'subpalettes' => array
	(
		'protected'                   => 'groups'
	), 
	
	
    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
		// Erstellungsdatum, wird beim Topic-Posting gesetzt
		'topicdate' => array
		(
    		'flag'					  => 5,
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
 		), 
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>128, 'tl_class'=>'long'),
            'sql'                     => "varchar(128) NOT NULL default ''"
        ),
  		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['text'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'), 
			'sql'                     => "mediumtext NULL" 
		), 
		// Name des Threaderstellers
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['name'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_member.username',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'choosen'=>true),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		), 
		// Email des Threaderstellers
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['email'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'rgxp'=>'emails', 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		), 
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['protected'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true),
			'sql'                     => "blob NULL",
			'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['guests'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'space' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['space'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'default'                 => 1,
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'start' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['start'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum_topics']['stop'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		) 
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array
 */
class tl_forum_topics extends Backend
{

    /**
     * Beim Speichern eines Datensatzes zusätzliche Änderungen vornehmen
     * @param DataContainer
     * @return -
     */
	public function saveRecord(DataContainer $dc)
	{

	}

    /**
     * Generiere eine Zeile als HTML
     * @param array
     * @return string
     */
    public function listTopics($arrRow)
    {
        $line = '';
        $line .= '<div><b>'.$arrRow['title'].'</b></a> - '.$arrRow['topicdate'];
        $line .= "</div>";
        $line .= "\n";

        return($line);

    }

	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
        $this->import('BackendUser', 'User');
 
        if (strlen($this->Input->get('tid')))
        {
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 0));
            $this->redirect($this->getReferer());
        }
 
        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_forum_topics::published', 'alexf'))
        {
            return '';
        }
 
        $href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];
 
        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }
 
        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnPublished)
	{
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_forum_topics::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_forum_topics toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	
		$this->createInitialVersion('tl_forum_topics', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_forum_topics']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_forum_topics']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
	
		// Update the database
		$this->Database->prepare("UPDATE tl_forum_topics SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
			->execute($intId);
		$this->createNewVersion('tl_forum_topics', $intId);
	}

	/**
	 * Eintragsdatum schreiben
	 * @param mixed
	 * @return mixed
	 */
	public function saveInitdate($varValue)
	{
		if(!$varValue) 
		{
			\System::log('[forum] New Topic created: '.\Input::post('title').' ('.\Input::post('url').')', __CLASS__.'::'.__FUNCTION__, TL_CRON); 
			return time();
		}
		else 
		{
			\System::log('[forum] Topic ID '.\Input::post('id').' edited: '.\Input::post('title').' ('.\Input::post('url').')', __CLASS__.'::'.__FUNCTION__, TL_ACCESS); 
			return $varValue;
		}
	}

}

