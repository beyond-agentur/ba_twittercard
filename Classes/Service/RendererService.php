<?php

namespace BeyondAgentur\Twittercard\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2016 Jonathan Heilmann <mail@jonathan-heilmann.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author     Jonathan Heilmann <mail@jonathan-heilmann.de>
 * @package    tx_batwittercard
 */
class RendererService
{
	/**
	 * @type \TYPO3\CMS\Core\Page\PageRenderer
	 * @inject
	 */
	protected $pageRenderer;

	/**
	 * content Object
	 */
	public $cObj;

	/**
	 * Main-function to render the Twitter Card protocol content.
	 *
	 * @param    string $content
	 * @param    array  $conf
	 *
	 * @return    string
	 */
	public function main( $content, $conf )
	{
		$extKey  = 'tx_batwittercard';
		$content = '';
		$card    = [ ];

		//if there has been no return, get og properties and render output

		// Get title
		if ( $this->cObj->data[ 'tx_batwittercard_title' ] !== '' ) {
			$card[ 'title' ] = $this->cObj->data[ 'tx_batwittercard_title' ];
		} else {
			$card[ 'title' ] = $GLOBALS[ 'TSFE' ]->page[ 'title' ];
		}

		$card[ 'title' ] = htmlspecialchars( $card[ 'title' ] );

		// Get type
		if ( $this->cObj->data[ 'tx_batwittercard_type' ] !== '' ) {
			$card[ 'type' ] = $this->cObj->data[ 'tx_batwittercard_type' ];
		} else {
			$card[ 'type' ] = $conf[ 'type' ];
		}

		$card[ 'type' ] = htmlspecialchars( $card[ 'type' ] );

		// Get image
		/** @var \TYPO3\CMS\Core\Resource\FileRepository $fileRepository */
		$fileRepository = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Resource\\FileRepository' );
		$fileObjects    =
			$fileRepository->findByRelation( 'pages', 'tx_batwittercard_falimages', $GLOBALS[ 'TSFE' ]->id );
		if ( count( $fileObjects ) ) {
			foreach ( $fileObjects as $key => $fileObject ) {
				/** @var \TYPO3\CMS\Core\Resource\File $fileObject */
				$card[ 'image' ][] = GeneralUtility::locationHeaderUrl( $fileObject->getPublicUrl() );
			}
		} else {
			// check if an image is given in page --> media, if not use default image
			$fileObjects = $fileRepository->findByRelation( 'pages', 'media', $GLOBALS[ 'TSFE' ]->id );
			if ( count( $fileObjects ) ) {
				foreach ( $fileObjects as $key => $fileObject ) {
					/** @var \TYPO3\CMS\Core\Resource\File $fileObject */
					$card[ 'image' ][] = GeneralUtility::locationHeaderUrl( $fileObject->getPublicUrl() );
				}
			} else {
				$imageFileName = $GLOBALS[ 'TSFE' ]->tmpl->getFileName( $conf[ 'image' ] );
				if ( !empty( $imageFileName ) ) {
					$card[ 'image' ][] = GeneralUtility::locationHeaderUrl( $imageFileName );
				}
			}
		}

		// Get site_name
		if ( $conf[ 'sitename' ] !== '' ) {
			$card[ 'site_name' ] = $conf[ 'sitename' ];
		} else {
			$card[ 'site_name' ] = $GLOBALS[ 'TSFE' ]->tmpl->setup[ 'sitetitle' ];
		}

		$card[ 'site_name' ] = htmlspecialchars( $card[ 'site_name' ] );

		// Get description
		if ( $this->cObj->data[ 'tx_batwittercard_description' ] !== '' ) {
			$card[ 'description' ] = $this->cObj->data[ 'tx_batwittercard_description' ];
		} else {
			if ( $GLOBALS[ 'TSFE' ]->page[ 'description' ] !== '' ) {
				$card[ 'description' ] = $GLOBALS[ 'TSFE' ]->page[ 'description' ];
			} else {
				$card[ 'description' ] = $conf[ 'description' ];
			}
		}

		$card[ 'description' ] = htmlspecialchars( $card[ 'description' ] );

		// Get locale
		$localeParts = explode( '.', $GLOBALS[ 'TSFE' ]->tmpl->setup[ 'config.' ][ 'locale_all' ] );

		if ( array_key_exists( 0, $localeParts ) ) {
			$card[ 'locale' ] = str_replace( '-', '_', $localeParts[ 0 ] );
		}

		//add tags to html-header
		$GLOBALS[ 'TSFE' ]->additionalHeaderData[ $extKey ] = $this->renderHeaderLines( $card );

		return $content;
	}

	/**
	 * Render the header lines to be added from array
	 *
	 * @param    array $array
	 *
	 * @return    string
	 */
	private function renderHeaderLines( $array )
	{
		$res = [ ];
		foreach ( $array as $key => $value ) {
			if ( $value !== '' ) { // Skip empty values to prevent from empty og property
				if ( is_array( $value ) ) {
					// A op property with multiple values or child-properties
					if ( array_key_exists( '0', $value ) ) {
						// A og property that accepts more than one value
						foreach ( $value as $multiPropertyValue ) {
							// Render each value to a new og property meta-tag
							$res[] = '<meta property="twitter:' . $key . '" content="' . $multiPropertyValue . '" />';
						}
					} else {
						// A og property with child-properties
						$res .= $this->renderHeaderLines( $this->remapArray( $key, $value ) );
					}
				} else {
					// A singe og property to be rendered
					$res[] = '<meta property="twitter:' . $key . '" content="' . $value . '" />';
				}
			}
		}

		return implode( chr( 10 ), $res );
	}

	/**
	 * Remap an array: Add $prefixKey to keys of $array
	 *
	 * @param    string $prefixKey
	 * @param    array  $array
	 *
	 * @return    array
	 */
	private function remapArray( $prefixKey, $array )
	{
		$res = [ ];
		foreach ( $array as $key => $value ) {
			$res[ $prefixKey . ':' . $key ] = $value;
		}

		return $res;
	}
}
