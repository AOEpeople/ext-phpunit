<?php
 /***************************************************************
 * Copyright notice
 *
 * (c) 2005-2010 Robert Lemke (robert@typo3.org)
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once('PHPUnit/Framework.php');

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * Class tx_phpunit_testcase for the "phpunit" extension.
 *
 * This class provides helper functions that might be convenient when testing in
 * TYPO3. It extends PHPUnit_Framework_TestCase, so you have access to all of
 * that class too.
 *
 * @package TYPO3
 * @subpackage tx_phpunit
 *
 * @author Robert Lemke <robert@typo3.org>
 * @author Kasper Ligaard <kasperligaard@gmail.com>
 * @author Soren Soltveit <sso@systime.dk>
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class tx_phpunit_testcase extends PHPUnit_Framework_TestCase {
	/**
	 * @var boolean
	 */
	protected $backupGlobals = FALSE;

	/**
	 * @var boolean
	 */
	protected $backupStaticAttributes = FALSE;

	/**
	 * Constructs a test case with the given name.
	 *
	 * @param string $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = '') {
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Roughly simulates the front end although being in the back end.
	 *
	 * @todo	This is a quick hack, needs proper implementation.
	 */
	protected function simulateFrontendEnviroment() {
		if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
			// avoids some memory leaks
			unset(
				$GLOBALS['TSFE']->tmpl, $GLOBALS['TSFE']->sys_page, $GLOBALS['TSFE']->fe_user,
				$GLOBALS['TSFE']->TYPO3_CONF_VARS, $GLOBALS['TSFE']->config, $GLOBALS['TSFE']->TCAcachedExtras,
				$GLOBALS['TSFE']->imagesOnPage, $GLOBALS['TSFE']->cObj, $GLOBALS['TSFE']->csConvObj,
				$GLOBALS['TSFE']->pagesection_lockObj, $GLOBALS['TSFE']->pages_lockObj
			);
			$GLOBALS['TSFE'] = NULL;
			$GLOBALS['TT'] = NULL;
		}

		$GLOBALS['TT'] = t3lib_div::makeInstance('t3lib_TimeTrackNull');
		$frontEnd = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);

		// simulates a normal FE without any logged-in FE or BE user
		$frontEnd->beUserLogin = FALSE;
		$frontEnd->workspacePreview = '';
		$frontEnd->gr_list = '0,-1';

		$frontEnd->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$frontEnd->sys_page->init(TRUE);
		$frontEnd->initTemplate();

		// $frontEnd->getConfigArray() doesn't work here because the dummy FE
		// is not required to have a template.
		$frontEnd->config = array();

		$GLOBALS['TSFE'] = $frontEnd;
	}
}

/**
 * This class is provided for backwards compatibility with the extension t3unit
 * t3unit is based on PHPUnit version 2 (known as PHPUnit2)
 */
class tx_t3unit_testcase extends PHPUnit_Framework_TestCase {
}
?>