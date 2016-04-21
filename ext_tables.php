<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if ( !defined( 'TYPO3_MODE' ) ) {
	die( 'Access denied.' );
}

// Create array with columns
$tempColumns = [
	'tx_batwittercard_type'        => [
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type',
		'config'  => [
			'type'       => 'select',
			'renderType' => 'selectSingle',
			'items'      => [
				[
					'LLL:EXT:' . $_EXTKEY .
					'/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_disabled',
					'disabled',
				],
				[
					'LLL:EXT:' . $_EXTKEY .
					'/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_summary_large',
					'summary_large_image',
				],
				[
					'LLL:EXT:' . $_EXTKEY .
					'/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_summary',
					'summary',
				],
				[
					'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_app',
					'app',
				],
				[
					'LLL:EXT:' . $_EXTKEY .
					'/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_player',
					'player',
				],
			],
			'size'       => 1,
			'maxitems'   => 1,
		],
	],
	'tx_batwittercard_title'       => [
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_title',
		'config'  => [
			'type' => 'input',
			'size' => '160',
		],
	],
	'tx_batwittercard_description' => [
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY .
		             '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_description',
		'config'  => [
			'type' => 'input',
			'size' => '30',
			'max'  => '200',
		],
	],
	'tx_batwittercard_site'        => [
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY .
		             '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_site',
		'config'  => [
			'type' => 'input',
			'size' => '30',
			'max'  => '200',
		],
	],
	'tx_batwittercard_creator'     => [
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY .
		             '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_creator',
		'config'  => [
			'type' => 'input',
			'size' => '30',
			'max'  => '200',
		],
	],
	'tx_batwittercard_image'       => [
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_image',
		'config'  => [
			'type'          => 'group',
			'internal_type' => 'file',
			'allowed'       => $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'GFX' ][ 'imagefile_ext' ],
			'max_size'      => $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'BE' ][ 'maxFileSize' ],
			'uploadfolder'  => 'uploads/tx_batwittercard',
			'show_thumbs'   => 1,
			'size'          => 4,
			'minitems'      => 0,
			'maxitems'      => 6,
		],
	],
	'tx_batwittercard_falimages'   => [
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_image',
		'config'  => ExtensionManagementUtility::getFileFieldTCAConfig(
			'tx_batwittercard_falimages',
			[
				'appearance'    => [
					'enableControls' => [
						'sort' => true,
					],
				],
				'foreign_types' => [
					'0'                                                 => [
						'showitem' => '
						--palette--;;opengraphprotocolPalette,
						--palette--;;filePalette',
					],
					\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE       => [
						'showitem' => '
						--palette--;;opengraphprotocolPalette,
						--palette--;;filePalette',
					],
					\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
						'showitem' => '
						--palette--;;opengraphprotocolPalette,
						--palette--;;filePalette',
					],
				],
			],
			$GLOBALS[ 'TYPO3_CONF_VARS' ][ 'GFX' ][ 'imagefile_ext' ]
		),
	],
];

// Add columns to TCA of pages and pages_language_overlay
ExtensionManagementUtility::addTCAcolumns( 'pages', $tempColumns, 1 );
ExtensionManagementUtility::addToAllTCAtypes( 'pages', '--div--;LLL:EXT:' . $_EXTKEY .
                                                       '/Resources/Private/Language/locallang.xml:pages.tab_title, 
                                                                               tx_batwittercard_type,
                                                                               tx_batwittercard_title;;;;1-1-1, 
                                                                               tx_batwittercard_description, 
                                                                               tx_batwittercard_site, 
                                                                               tx_batwittercard_creator, 
                                                                               tx_batwittercard_falimages' );

ExtensionManagementUtility::addTCAcolumns( 'pages_language_overlay', $tempColumns, 1 );
ExtensionManagementUtility::addToAllTCAtypes( 'pages_language_overlay',
	'--div--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tab_title,tx_batwittercard_type,
                                                                               tx_batwittercard_title;;;;1-1-1, 
                                                                               tx_batwittercard_description, 
                                                                               tx_batwittercard_site, 
                                                                               tx_batwittercard_creator, 
                                                                               tx_batwittercard_falimages\'' );

// Add static file
ExtensionManagementUtility::addStaticFile( $_EXTKEY, 'Configuration/TypoScript',
	'Twitter Card' );

// Add new palette
$GLOBALS[ 'TCA' ][ 'sys_file_reference' ][ 'palettes' ][ 'opengraphprotocolPalette' ]               =
	$GLOBALS[ 'TCA' ][ 'sys_file_reference' ][ 'palettes' ][ 'basicoverlayPalette' ];
$GLOBALS[ 'TCA' ][ 'sys_file_reference' ][ 'palettes' ][ 'opengraphprotocolPalette' ][ 'showitem' ] = '';

$TCA[ 'pages' ][ 'ctrl' ][ 'requestUpdate' ] = 'tx_batwittercard_type';
