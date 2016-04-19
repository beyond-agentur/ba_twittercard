<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$TYPO3_CONF_VARS['FE']['pageOverlayFields'] .= ',tx_batwittercard_title,tx_batwittercard_type,tx_batwittercard_falimages,tx_batwittercard_description';

// Add update script to install tool
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['ba_twittercard_fal'] =
    'BeyondAgentur\\Twittercard\\Updates\\FalUpdateWizard';
