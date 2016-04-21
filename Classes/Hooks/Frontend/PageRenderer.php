<?php

namespace BeyondAgentur\Twittercard\Hooks\Frontend;

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Utility\EidUtility;

class PageRenderer
{
	/**
	 * SignalSlotDispatcher
	 *
	 * @var Dispatcher
	 * @inject
	 */
	protected $signalSlotDispatcher;

	/**
	 * @param                                   array Params for hook
	 * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
	 */
	public function execute( $params, $pageRenderer )
	{
		$extKey = 'tx_batwittercard';
		$cObj   = $GLOBALS[ 'TSFE' ]->cObj;
		$card   = [ ];

		if ( $cObj->data[ $extKey . '_type' ] === 'disabled' ) {
			return;
		}

		if ( $this->signalSlotDispatcher == NULL ) {
			/* @var \TYPO3\CMS\Extbase\Object\ObjectManager */
			$objectManager              = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Extbase\\Object\\ObjectManager' );
			$this->signalSlotDispatcher = $objectManager->get( 'TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher' );
		}

		// Get type
		if ( $cObj->data[ $extKey . '_type' ] !== '' ) {
			$card[ 'type' ] = $cObj->data[ $extKey . '_type' ];
		} else {
			$card[ 'type' ] = 'summary';
		}

		$card[ 'type' ] = htmlspecialchars( $card[ 'type' ] );

		// Get title
		if ( $cObj->data[ $extKey . '_title' ] !== '' ) {
			$card[ 'title' ] = $cObj->data[ $extKey . '_title' ];
		} else {
			$card[ 'title' ] = $GLOBALS[ 'TSFE' ]->page[ 'title' ];
		}

		$card[ 'title' ] = htmlspecialchars( $card[ 'title' ] );

		// Get description
		if ( $cObj->data[ $extKey . '_description' ] !== '' ) {
			$card[ 'description' ] = $cObj->data[ $extKey . '_description' ];
		} else {
			if ( $GLOBALS[ 'TSFE' ]->page[ 'description' ] !== '' ) {
				$card[ 'description' ] = $GLOBALS[ 'TSFE' ]->page[ 'description' ];
			} else if ( $GLOBALS[ 'TSFE' ]->page[ 'abstract' ] !== '' ) {
				$card[ 'description' ] = $GLOBALS[ 'TSFE' ]->page[ 'abstract' ];
			}
		}

		$card[ 'description' ] = htmlspecialchars( $card[ 'description' ] );

		// Get site_name
		if ( $cObj->data[ $extKey . '_site' ] !== '' ) {
			$card[ 'site' ] = $cObj->data[ $extKey . '_site' ];
		}

		// Get site_name
		if ( $cObj->data[ $extKey . '_creator' ] !== '' ) {
			$card[ 'creator' ] = $cObj->data[ $extKey . '_creator' ];
		}

		// Get image
		/** @var FileRepository $fileRepository */
		$fileRepository = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Resource\\FileRepository' );
		$fileObjects    =
			$fileRepository->findByRelation( 'pages', $extKey . '_falimages', $GLOBALS[ 'TSFE' ]->id );
		if ( count( $fileObjects ) ) {
			/** @var File $fileObject */
			$fileObject = $fileObjects[ 0 ];

			$card[ 'image' ]     = GeneralUtility::locationHeaderUrl( $fileObject->getPublicUrl() );
			$card[ 'image:alt' ] = $fileObject->getProperty( 'alternative' );
		} else {
			// check if an image is given in page --> media, if not use default image
			$fileObjects = $fileRepository->findByRelation( 'pages', 'media', $GLOBALS[ 'TSFE' ]->id );
			if ( count( $fileObjects ) ) {
				/** @var File $fileObject */
				$fileObject = $fileObjects[ 0 ];

				$card[ 'image' ]     = GeneralUtility::locationHeaderUrl( $fileObject->getPublicUrl() );
				$card[ 'image:alt' ] = $fileObject->getProperty( 'alternative' );
			}
		}

		// Signal to manipulate og-properties before header creation
		$this->signalSlotDispatcher->dispatch(
			__CLASS__,
			'beforeHeaderCreation',
			[ &$card, $cObj ]
		);

		foreach ( $card as $key => $value ) {
			if ( $value ) {
				$pageRenderer->addHeaderData( '<meta property="twitter:' . $key . '" content="' . $value . '" />' );
			}
		}
	}
}