<?php
if ( ! defined( 'TYPO3_MODE' ) ) {
	die( 'Access denied.' );
}

// Create array with columns
$tempColumns = array(
	'tx_batwittercard_type'        => array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type',
		'config'  => array(
			'type'       => 'select',
			'renderType' => 'selectSingle',
			'items'      => [
				[ 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_summary_large', 'summary-large-image' ],
				[ 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_summary', 'summary' ],
				[ 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_app', 'app' ],
				[ 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_type_player', 'player' ],
			],
			'size'       => 1,
			'maxitems'   => 1,
		)
	),
	'tx_batwittercard_title'       => array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_title',
		'config'  => array(
			'type' => 'input',
			'size' => '160',
		)
	),
	'tx_batwittercard_description' => array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_description',
		'config'  => array(
			'type' => 'input',
			'size' => '30',
			'max'  => '200',
		)
	),
	'tx_batwittercard_image'       => array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_image',
		'config'  => array(
			'type'          => 'group',
			'internal_type' => 'file',
			'allowed'       => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
			'max_size'      => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
			'uploadfolder'  => 'uploads/tx_batwittercard',
			'show_thumbs'   => 1,
			'size'          => 4,
			'minitems'      => 0,
			'maxitems'      => 6,
		)
	),
	'tx_batwittercard_falimages'   => array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tx_batwittercard_image',
		'config'  => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
			'tx_batwittercard_falimages',
			array(
				'appearance'    => array(
					'enableControls' => array(
						'sort' => true,
					),
				),
				'foreign_types' => array(
					'0'                                                 => array(
						'showitem' => '
						--palette--;;opengraphprotocolPalette,
						--palette--;;filePalette'
					),
					\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE       => array(
						'showitem' => '
						--palette--;;opengraphprotocolPalette,
						--palette--;;filePalette'
					),
					\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => array(
						'showitem' => '
						--palette--;;opengraphprotocolPalette,
						--palette--;;filePalette'
					),
				),
			),
			$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
		),
	),
);

// Add columns to TCA of pages and pages_language_overlay
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns( 'pages', $tempColumns, 1 );
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes( 'pages', '--div--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tab_title,tx_batwittercard_title;;;;1-1-1, tx_batwittercard_type, tx_batwittercard_falimages, tx_batwittercard_description' );

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns( 'pages_language_overlay', $tempColumns, 1 );
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes( 'pages_language_overlay', '--div--;LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:pages.tab_title,tx_batwittercard_title;;;;1-1-1, tx_batwittercard_type, tx_batwittercard_falimages, tx_batwittercard_description' );

// Add static file
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile( $_EXTKEY, 'Configuration/TypoScript', 'Twitter Card' );

// Add new palette
$GLOBALS['TCA']['sys_file_reference']['palettes']['opengraphprotocolPalette']             = $GLOBALS['TCA']['sys_file_reference']['palettes']['basicoverlayPalette'];
$GLOBALS['TCA']['sys_file_reference']['palettes']['opengraphprotocolPalette']['showitem'] = '';

$TCA[ 'pages' ]['ctrl']['requestUpdate'] = 'tx_batwittercard_type';
