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
 * Table tl_forum
 */
$GLOBALS['TL_DCA']['tl_forum'] = array
(

	// Config
	'config' => array
	(
		'label'                       => $GLOBALS['TL_LANG']['tl_forum']['maintitle'],
		'dataContainer'               => 'Table',
        'switchToEdit'                => true,
		'ctable'                      => array('tl_forum_threads'),
		'enableVersioning'            => true,
		'onload_callback'             => array
		(
			array('tl_forum', 'addBreadcrumb')
		),
		'sql'                         => array
		(
			'keys'                    => array
			(
				'id'                  => 'primary',
				'pid'                 => 'index',
				'alias'               => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'fields'                  => array('sorting'),
			'icon'                    => 'pagemounts.gif',
			'panelLayout'             => 'filter,search',
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s',
			'label_callback'          => array('tl_forum', 'addIcon')
		),
		'global_operations' => array
		(
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleAll'],
				'href'                => 'ptg=all',
				'class'               => 'header_toggle',
				'showOnSelect'        => true 
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_forum']['edit'],
				'href'                => 'table=tl_forum_threads',
				'icon'                => 'edit.gif',
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_forum']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
			), 
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_forum']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
			),
			//'delete' => array
			//(
			//	'label'               => &$GLOBALS['TL_LANG']['tl_forum']['delete'],
			//	'href'                => 'act=delete',
			//	'icon'                => 'delete.gif',
			//	'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			//),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_forum']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_forum', 'toggleIcon') 
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_forum']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('protected'), 
		'default'                     => '{title_legend},title,alias;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{published_legend},published,start,stop'
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
			'label'                   => array('ID'),
			'search'                  => true,
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('rgxp'=>'folderalias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varbinary(128) NOT NULL default ''",
			'save_callback' => array
			(
				array('tl_forum', 'generateAlias')
			), 
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['protected'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true),
			'sql'                     => "blob NULL",
			'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['guests'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'space' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['space'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'start' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['start'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_forum']['stop'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		) 
	)
);

/**
 * Class tl_forum
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class tl_forum extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Add the breadcrumb menu
	 */
	public function addBreadcrumb()
	{

		// Knoten in Session speichern
		if (isset($_GET['node']))
		{
			$this->Session->set('tl_forum_node', $this->Input->get('node'));
			$this->redirect(preg_replace('/&node=[^&]*/', '', $this->Environment->request));
		}
		$cat = $this->Session->get('tl_forum_node');

		// Breadcrumb-Navigation erstellen
		$breadcrumb = array();
		if($cat) // Nur bei Unterkategorien
		{
			// Kategorienbaum einschränken
			$GLOBALS['TL_DCA']['tl_forum']['list']['sorting']['root'] = array($cat);
		
			// Infos zur aktuellen Kategorie laden
			$objActual = \Database::getInstance()->prepare('SELECT * FROM tl_forum WHERE published = ? AND id = ?')
							   				     ->execute(1, $cat);
			$breadcrumb[] = '<img src="system/modules/forum/assets/images/category.png" width="18" height="18" alt=""> ' . $objActual->title;
			
			// Navigation vervollständigen
			$pid = $objActual->pid;
			while($pid > 0)
			{
				$objTemp = \Database::getInstance()->prepare('SELECT * FROM tl_forum WHERE published = ? AND id = ?')
								   			       ->execute(1, $pid);
				$breadcrumb[] = '<img src="system/modules/forum/assets/images/category.png" width="18" height="18" alt=""> <a href="' . \Controller::addToUrl('node='.$objTemp->id) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $objTemp->title . '</a>';
				$pid = $objTemp->pid;
			}
			$breadcrumb[] = '<img src="' . TL_FILES_URL . 'system/themes/' . \Backend::getTheme() . '/images/pagemounts.gif" width="18" height="18" alt=""> <a href="' . \Controller::addToUrl('node=0') . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectAllNodes']).'">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';
		}
		$breadcrumb = array_reverse($breadcrumb);

		// Insert breadcrumb menu
		if($breadcrumb)
		{
			$GLOBALS['TL_DCA']['tl_forum']['list']['sorting']['breadcrumb'] .= '
			<ul id="tl_breadcrumb">
  				<li>' . implode(' &gt; </li><li>', $breadcrumb) . '</li>
			</ul>';
		}
	}

	/**
	 * Add an image to each page in the tree
	 *
	 * @param array         $row
	 * @param string        $label
	 * @param DataContainer $dc
	 * @param string        $imageAttribute
	 * @param boolean       $blnReturnImage
	 * @param boolean       $blnProtected
	 *
	 * @return string
	 */
	public function addIcon($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false, $blnProtected=false)
	{
		if ($blnProtected)
		{
			$row['protected'] = true;
		}

		$image = 'system/modules/forum/assets/images/category.png';
		$imageAttribute = trim($imageAttribute . ' data-icon="category.png" data-icon-disabled="category.png"');

		// Return the image only
		if ($blnReturnImage)
		{
			return \Image::getHtml($image, '', $imageAttribute);
		}

		// Markiere Root-Kategorien
		if ($row['pid'] == '0')
		{
			$label = '<strong>' . $label . '</strong>';
		}

		// Rückgabe der Zeile
		return \Image::getHtml($image, '', $imageAttribute) . '<a href="' . \Controller::addToUrl('node='.$row['id']) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'"> ' . $label . '</a> 
		(<b>'.$row['links_self'].'</b>/'.$row['links_all'].')';

	}

	/**
	 * Auto-generate a page alias if it has not been set yet
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize(StringUtil::restoreBasicEntities($dc->activeRecord->title));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_forum WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_forum::published', 'alexf'))
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_forum::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_forum toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	
		$this->createInitialVersion('tl_forum', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_forum']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_forum']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
	
		// Update the database
		$this->Database->prepare("UPDATE tl_forum SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
			->execute($intId);
		$this->createNewVersion('tl_forum', $intId);
	}
}

