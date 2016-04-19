<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "ba_twittercard".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Twitter Cards',
	'description' => 'This Extension adds twitter cards properties in meta-tags to the html-header and supports multilanguage-websites.',
	'category' => 'plugin',
	'version' => '0.0.1',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearcacheonload' => true,
	'author' => 'beyond Agentur UG',
	'author_email' => 'info@beyond-agentur.com',
	'author_company' => 'beyond Agentur UG',
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '7.6.0-8.0.99',
		),
		'suggests' => 
		array (
		),
	),
);