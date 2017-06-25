<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Forum'          				=> 'system/modules/forum/classes/Forum.php',
	'Formular'                      => 'system/modules/forum/libraries/Formular.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'forum_threads'          		=> 'system/modules/forum/templates',
	'forum_topics'					=> 'system/modules/forum/templates',
));
