<?php

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if ( !defined( 'TYPO3_MODE' ) ) {
	die( 'Access denied.' );
}

$TYPO3_CONF_VARS[ 'FE' ][ 'pageOverlayFields' ] .= ',tx_batwittercard_type,tx_batwittercard_title,
tx_batwittercard_description,tx_batwittercard_site,tx_batwittercard_creator,tx_batwittercard_falimages';

if ( TYPO3_MODE === 'FE' ) {
	$GLOBALS[ 'TYPO3_CONF_VARS' ][ 'SC_OPTIONS' ][ 't3lib/class.t3lib_pagerenderer.php' ][ 'render-postProcess' ][ 'tx_batwittercard' ] =
		'BeyondAgentur\Twittercard\Hooks\Frontend\PageRenderer->execute';
}

// Add update script to install tool
$GLOBALS[ 'TYPO3_CONF_VARS' ][ 'SC_OPTIONS' ][ 'ext/install' ][ 'update' ][ 'ba_twittercard_fal' ] =
	'BeyondAgentur\\Twittercard\\Updates\\FalUpdateWizard';
