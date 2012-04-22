<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2008-2012 Kasper Ligaard <kasperligaard@gmail.com>
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

/**
 * Back-end module "PHPUnit".
 *
 * @package TYPO3
 * @subpackage tx_phpunit
 *
 * @author Kasper Ligaard <kasperligaard@gmail.com>
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Bastian Waidelich <bastian@typo3.org>
 * @author Carsten Koenig <ck@carsten-koenig.de>
 */
class Tx_Phpunit_BackEnd_Module extends t3lib_SCbase {
	/**
	 * @var string
	 */
	const EXTENSION_KEY = 'phpunit';

	/**
	 * the relative path to this extension
	 *
	 * @var string
	 */
	protected $extensionPath = '';

	/**
	 * @var Tx_Phpunit_Service_TestFinder
	 */
	protected $testFinder = NULL;

	/**
	 * @var Tx_PhpUnit_BackEnd_TestListener
	 */
	protected $testListener = NULL;

	/**
	 * @var Tx_PhpUnit_Service_OutputService
	 */
	protected $outputService = NULL;

	/**
	 * @var Tx_Phpunit_Interface_UserSettingsService
	 */
	protected $userSettingsService = NULL;

	/**
	 * module menu items
	 *
	 * @var array
	 */
	public $MOD_MENU = array(
		'function' => array(),
		'extSel' => '',
		'failure' => '',
		'success' => '',
		'error' => '',
		'codeCoverage' => '',
	);

	/**
	 * the names of classes which cannot be directly run as test cases
	 *
	 * @var array
	 */
	protected $ignoredTestSuitClasses = array(
		'Tx_Phpunit_TestCase',
		'Tx_Phpunit_Database_TestCase',
		'Tx_Phpunit_Selenium_TestCase',
	);

	/**
	 * @var PHP_CodeCoverage
	 */
	protected $coverage = NULL;

	/**
	 * The constructor.
	 */
	public function __construct() {
		$this->init();

		$this->extensionPath = t3lib_extMgm::extRelPath(self::EXTENSION_KEY);
	}

	/**
	 * The destructor.
	 */
	public function __destruct() {
		unset($this->testFinder, $this->coverage, $this->testListener, $this->outputService, $this->userSettingsService);
	}

	/**
	 * Injects the test listener.
	 *
	 * @param Tx_PhpUnit_BackEnd_TestListener $testListener the test listener to inject
	 *
	 * @return void
	 */
	public function injectTestListener(Tx_PhpUnit_BackEnd_TestListener $testListener) {
		$this->testListener = $testListener;
	}

	/**
	 * Injects the output service.
	 *
	 * @param Tx_PhpUnit_Service_OutputService $service the service to inject
	 *
	 * @return void
	 */
	public function injectOutputService(Tx_PhpUnit_Service_OutputService $service) {
		$this->outputService = $service;
	}

	/**
	 * Injects the user settings service.
	 *
	 * @param Tx_Phpunit_Interface_UserSettingsService $service the service to inject
	 *
	 * @return void
	 */
	public function injectUserSettingsService(Tx_Phpunit_Interface_UserSettingsService $service) {
		$this->userSettingsService = $service;
	}

	/**
	 * Creates a and returns the singleton test finder instance.
	 *
	 * @return Tx_Phpunit_Service_TestFinder the test finder instance
	 */
	protected function getTestFinder() {
		if ($this->testFinder === NULL) {
			$this->testFinder = t3lib_div::makeInstance('Tx_Phpunit_Service_TestFinder');
		}

		return $this->testFinder;
	}

	/**
	 * Returns the localized string for the key $key.
	 *
	 * @param string $key the key of the string to retrieve, must not be empty
	 *
	 * @return string the localized string for the key $key
	 */
	protected function translate($key) {
		return $GLOBALS['LANG']->getLL($key);
	}

	/**
	 * Main function of the module. Outputs all content directly instead of
	 * collecting it and doing the output later.
	 *
	 * @return void
	 */
	public function main() {
		if ($GLOBALS['BE_USER']->user['admin']) {
			// Draw the header.
			$this->doc = t3lib_div::makeInstance('bigDoc');
			$this->doc->backPath = $GLOBALS['BACK_PATH'];
			$this->doc->docType = 'xhtml_strict';
			$this->doc->bodyTagAdditions = 'id="doc3"';

			// JavaScript Libraries
			$this->doc->loadJavascriptLib('contrib/prototype/prototype.js');
			$this->doc->loadJavascriptLib($this->extensionPath . 'Resources/Public/YUI/yahoo-dom-event.js');
			$this->doc->loadJavascriptLib($this->extensionPath . 'Resources/Public/YUI/connection-min.js');
			$this->doc->loadJavascriptLib($this->extensionPath . 'Resources/Public/YUI/json-min.js');
			$this->doc->loadJavascriptLib($this->extensionPath . 'Resources/Public/JavaScript/BackEnd.js');

			// Mis-using JScode to insert CSS _after_ skin.
			$this->doc->JScode = '<link rel="stylesheet" type="text/css" href="' . $this->extensionPath .
				'Resources/Public/YUI/reset-fonts-grids.css" />';
			$this->doc->JScode .= '<link rel="stylesheet" type="text/css" href="' . $this->extensionPath .
				'Resources/Public/YUI/base-min.css" />';
			$this->doc->JScode .= '<link rel="stylesheet" type="text/css" href="' . $this->extensionPath .
				'Resources/Public/CSS/BackEnd.css" />';

			t3lib_div::cleanOutputBuffers();
			$this->outputService->output(
				$this->doc->startPage($this->translate('title')) .
				$this->doc->header(PHPUnit_Runner_Version::getVersionString())
			);

			$this->runTests_render();

			$this->outputService->output(
				$this->doc->section(
					'Keyboard shortcuts',
					'<p>Use "a" for running all tests, use "s" for running a single test and
					use "r" to re-run the latest tests; to open phpunit in a new window, use "n".</p>
					<p>Depending on your browser and system you will need to press some
					modifier keys:</p>
					<ul>
					<li>Safari, IE and Firefox 1.x: Use "Alt" button on Windows, "Ctrl" on Macs.</li>
					<li>Firefox 2.x and 3.x: Use "Alt-Shift" on Windows, "Ctrl-Shift" on Macs</li>
					</ul>' .
					$this->doc->section('', $this->openNewWindowLink())
				)
			);
		} else {
			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $GLOBALS['BACK_PATH'];

			$this->outputService->output(
				$this->doc->startPage($this->translate('title')) .
				$this->doc->header($this->translate('title')) .
				$this->translate('admin_rights_needed')
			);
		}

		$this->outputService->output($this->doc->endPage());
	}


	/*********************************************************
	 *
	 * Screen render functions
	 *
	 *********************************************************/

	/**
	 * Renders the screens for function "Run tests".
	 *
	 * @return void
	 */
	protected function runTests_render() {
		$selectedExtensionKey = $this->userSettingsService->getAsString('extSel');

		if (($selectedExtensionKey !== Tx_Phpunit_Testable::ALL_EXTENSIONS)
			&& !$this->getTestFinder()->existsTestableForKey($selectedExtensionKey)
		) {
			// We know that phpunit must be loaded.
			$this->userSettingsService->set('extSel', 'phpunit');
		}
		$command = $this->userSettingsService->getAsString('extSel') ? t3lib_div::_GP('command') : '';
		switch ($command) {
			case 'runalltests':
				// The fallthrough is intentional.
			case 'runTestCaseFile':
				// The fallthrough is intentional.
			case 'runsingletest':
				$this->runTests_renderIntro();
				$this->runTests_renderRunningTest();
				break;
			default:
				$this->runTests_renderIntro();
		}
	}

	/**
	 * Renders the intro screen for the function "Run tests".
	 *
	 * @return void
	 */
	protected function runTests_renderIntro() {
		if (!$this->getTestFinder()->existsTestableForAnything()) {
			/** @var $message t3lib_FlashMessage */
			$message = t3lib_div::makeInstance(
				't3lib_FlashMessage',
				$this->translate('could_not_find_exts_with_tests'),
				'',
				t3lib_FlashMessage::WARNING
			);
			$this->outputService->output($message->render());
			return;
		}

		$output = $this->createExtensionSelector();
		$selectedExtensionKey = $this->userSettingsService->getAsString('extSel');

		if (($selectedExtensionKey !== '') && ($selectedExtensionKey !== Tx_Phpunit_Testable::ALL_EXTENSIONS)) {
			$output .= $this->createTestCaseSelector($selectedExtensionKey) . $this->createTestSelector($selectedExtensionKey);
		}
		$output .= $this->createCheckboxes();

		$this->outputService->output($output);
	}

	/**
	 * Creates the extension drop-down.
	 *
	 * @return string
	 *         HTML code for the drop-down and a surrounding form, will not be empty
	 */
	protected function createExtensionSelector() {
		$selectedExtensionKey = $this->userSettingsService->getAsString('extSel');

		$options = array();
		$options[] = '<option value="" disabled="disabled">' . $this->translate('select_extension') . '</option>';

		$allIsSelected = ($selectedExtensionKey === Tx_Phpunit_Testable::ALL_EXTENSIONS) ? ' selected="selected"' : '';
		$options[] = '<option class="alltests" value="uuall"' . $allIsSelected . '>' .
			$this->translate('all_extensions') . '</option>';

		$selectedExtensionStyle = '';

		$testables = $this->getTestFinder()->getTestablesForEverything();
		/** @var  $testable Tx_Phpunit_Testable */
		foreach ($testables as $testable) {
			$style = 'background: url(' . $testable->getIconPath() . ') no-repeat 3px 50% white; padding: 1px 1px 1px 24px;';
			if ($selectedExtensionKey === $testable->getKey()) {
				$selected = ' selected="selected"';
				$selectedExtensionStyle = $style;
			} else {
				$selected = '';
			}

			$options[] = '<option style="' . $style . '" value="' . htmlspecialchars($testable->getKey()) . '"' . $selected . '>' .
				htmlspecialchars($testable->getKey()) . '</option>';
		}

		$allOptions = implode(LF, $options);

		$output = '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post"><p>' .
				'<select style="' . $selectedExtensionStyle . '"name="SET[extSel]" onchange="jumpToUrl(\'' .
				htmlspecialchars($this->MCONF['_']) .
				'&amp;SET[extSel]=\'+this.options[this.selectedIndex].value,this);">' .
				$allOptions .
				'</select> ' .
				'<button type="submit" name="bingo" value="run" accesskey="a">' .
				$this->translate('run_all_tests') . '</button>' .
				'<input type="hidden" name="command" value="runalltests" />' .
				'</p></form>';

		return $output;
	}

	/**
	 * Renders a drop-down for running single tests cases for the given extension.
	 *
	 * @param string $extensionKey
	 *        keys of the extension for which to render the drop-down
	 *
	 * @return string
	 *         HTML code with the drop-down and a surrounding form, will be empty
	 *         if no loaded single extension is selected
	 */
	protected function createTestCaseSelector($extensionKey) {
		if (!$this->getTestFinder()->existsTestableForKey($extensionKey)) {
			return '';
		}

		$testsPathOfExtension = $this->getTestFinder()->getTestableForKey($extensionKey)->getTestsPath();
		$testSuites = $this->getTestFinder()->findTestCaseFilesDirectory($testsPathOfExtension);

		foreach ($testSuites as $fileName) {
			require_once($testsPathOfExtension . $fileName);
		}

		// Adds all classes to the test suite which end with "testcase" (case-insensitive)
		// or "Test", except the two special classes used as superclasses.
		$testSuite = new PHPUnit_Framework_TestSuite('tx_phpunit_basetestsuite');
		foreach (get_declared_classes() as $class) {
			$classReflection = new ReflectionClass($class);
			if ((strtolower(substr($class, -8, 8)) === 'testcase' || substr($class, -4, 4) === 'Test')
				&& $classReflection->isSubclassOf('PHPUnit_Framework_TestCase')
				&& $this->isAcceptedTestSuitClass($class)
			) {
				$testSuite->addTestSuite($class);
			}
		}

		// testCaseFile
		$testCaseFileOptionsArray = array();
		/** @var $testCase PHPUnit_Framework_TestCase */
		foreach ($testSuite->tests() as $testCase) {
			$selected = ($testCase->toString() === t3lib_div::_GP('testCaseFile')) ? ' selected="selected"' : '';
			$testCaseFileOptionsArray[] = '<option value="' . $testCase->toString() . '"' . $selected . '>' .
				htmlspecialchars($testCase->getName()) . '</option>';
		}

		$currentStyle = $this->createIconStyle($extensionKey);

		return '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">' .
				'<p>' .
					'<select style="' . $currentStyle . '" name="testCaseFile">' .
					'<option value="">' . htmlspecialchars($this->translate('select_tests')) . '</option>' .
					implode(LF, $testCaseFileOptionsArray) . '</select>' .
					'<button type="submit" name="bingo" value="run" accesskey="f">' . $this->translate('runTestCaseFile') . '</button>' .
					'<input type="hidden" name="command" value="runTestCaseFile" />' .
				'</p>' .
			'</form>';
	}

	/**
	 * Renders a drop-down for running single tests for the given extension.
	 *
	 * @param string $extensionKey
	 *        keys of the extension for which to render the drop-down
	 *
	 * @return string
	 *         HTML code with the drop-down and a surrounding form
	 */
	protected function createTestSelector($extensionKey) {
		if (!$this->getTestFinder()->existsTestableForKey($extensionKey)) {
			return '';
		}

		$testSuite = new PHPUnit_Framework_TestSuite('tx_phpunit_basetestsuite');

		$testsPathOfExtension = $this->getTestFinder()->getTestableForKey($extensionKey)->getTestsPath();
		$testSuites = $this->getTestFinder()->findTestCaseFilesDirectory($testsPathOfExtension);

		foreach ($testSuites as $fileName) {
			require_once($testsPathOfExtension . $fileName);
		}

		// Adds all classes to the test suite which end with "testcase" (case-insensitive)
		// or "Test", except the two special classes used as superclasses.
		foreach (get_declared_classes() as $class) {
			$classReflection = new ReflectionClass($class);
			if ((strtolower(substr($class, -8, 8)) === 'testcase' || substr($class, -4, 4) === 'Test')
				&& $classReflection->isSubclassOf('PHPUnit_Framework_TestCase')
				&& $this->isAcceptedTestSuitClass($class)
			) {
				$testSuite->addTestSuite($class);
			}
		}

		// single test case
		$testsOptionsArr = array();
		$testCaseFile = t3lib_div::_GP('testCaseFile');
		/** @var $testCase PHPUnit_Framework_TestSuite */
		foreach ($testSuite->tests() as $testCase) {
			if (!is_null($testCaseFile) && ($testCase->getName() !== $testCaseFile)) {
				continue;
			}
			/** @var $test PHPUnit_Framework_TestCase */
			foreach ($testCase->tests() as $test) {
				if ($test instanceof PHPUnit_Framework_TestSuite) {
					list($testSuiteName, $testName) = explode('::', $test->getName());
					$testIdentifier = $testName . '(' . $testSuiteName . ')';
				} else {
					$testName = $test->getName();
					$testIdentifier = $test->toString();
					$testSuiteName = strstr($testIdentifier, '(');
					$testSuiteName = trim($testSuiteName, '()');
				}
				$selected = ($testIdentifier === t3lib_div::_GP('testname')) ? ' selected="selected"' : '';
				$testsOptionsArr[$testSuiteName][] .= '<option value="' . $testIdentifier . '"' . $selected . '>' .
					htmlspecialchars($testName) . '</option>';
			}
		}

		// builds options for select (including option groups for test suites)
		$testOptionsHtml = '';
		foreach ($testsOptionsArr as $suiteName => $testArr) {
			$testOptionsHtml .= '<optgroup label="' . $suiteName . '">';
			foreach ($testArr as $testHtml) {
				$testOptionsHtml .= $testHtml;
			}
			$testOptionsHtml .= '</optgroup>';
		}

		$currentStyle = $this->createIconStyle($extensionKey);

		return '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">
				<p>
					<select style="' . $currentStyle . '" name="testname">
					<option value="">' . $this->translate('select_tests') . '</option>' . $testOptionsHtml . '</select>
					<button type="submit" name="bingo" value="run" accesskey="s">' . $this->translate('run_single_test') . '</button>
					<input type="hidden" name="command" value="runsingletest" />
					<input type="hidden" name="testCaseFile" value="' . $testCaseFile . '" />
				</p>
			</form>
		';
	}

	/**
	 * Renders the checkboxes for hiding or showing various test results.
	 *
	 * @return string
	 *         HTML code with checkboxes and a surrounding form
	 */
	protected function createCheckboxes() {
		$output = '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">';
		$output .= '<div class="phpunit-controls">';
		$failureState = $this->userSettingsService->getAsBoolean('failure') ? 'checked="checked"' : '';
		$errorState = $this->userSettingsService->getAsBoolean('error') ? 'checked="checked"' : '';
		$skippedState = $this->userSettingsService->getAsBoolean('skipped') ? 'checked="checked"' : '';
		$successState = $this->userSettingsService->getAsBoolean('success') ? 'checked="checked"' : '';
		$incompleteState = $this->userSettingsService->getAsBoolean('incomplete') ? 'checked="checked"' : '';
		$showMemoryAndTime = $this->userSettingsService->getAsBoolean('showMemoryAndTime') ? 'checked="checked"' : '';
		$testdoxState = $this->userSettingsService->getAsBoolean('testdox') ? 'checked="checked"' : '';
		$output .= '<input type="checkbox" id="SET_success" ' . $successState . ' /><label for="SET_success">Success</label>';
		$output .= ' <input type="checkbox" id="SET_failure" ' . $failureState . ' /><label for="SET_failure">Failure</label>';
		$output .= ' <input type="checkbox" id="SET_skipped" ' . $skippedState . ' /><label for="SET_skipped">Skipped</label>';
		$output .= ' <input type="checkbox" id="SET_error" ' . $errorState . ' /><label for="SET_error">Error</label>';
		$output .= ' <input type="checkbox" id="SET_testdox" ' . $testdoxState .
				   ' /><label for="SET_testdox">Show as human readable</label>';
		$output .= ' <input type="checkbox" id="SET_incomplete" ' . $incompleteState .
				   ' /><label for="SET_incomplete">Incomplete</label>';
		$output .= ' <input type="checkbox" id="SET_showMemoryAndTime" ' . $showMemoryAndTime .
				   '/><label for="SET_showMemoryAndTime">Show memory & time</label>';

		$codecoverageDisable = '';
		$codecoverageForLabelWhenDisabled = '';
		if (!extension_loaded('xdebug')) {
			$codecoverageDisable = ' disabled="disabled"';
			$codecoverageForLabelWhenDisabled = ' title="Code coverage requires XDebug to be installed."';
		}
		$codeCoverageState = $this->userSettingsService->getAsBoolean('codeCoverage') ? 'checked="checked"' : '';
		$output .= ' <input type="checkbox" id="SET_codeCoverage" ' . $codecoverageDisable . ' ' . $codeCoverageState .
			' /><label for="SET_codeCoverage"' . $codecoverageForLabelWhenDisabled .
			'>Collect code-coverage data</label>';
		$runSeleniumTests = $this->userSettingsService->getAsBoolean('runSeleniumTests') ? 'checked="checked"' : '';
		$output .= ' <input type="checkbox" id="SET_runSeleniumTests" ' . $runSeleniumTests . '/><label for="SET_runSeleniumTests">' . $this->translate('run_selenium_tests') . '</label>';
		$output .= '</div>';
		$output .= '</form>';

		return $output;
	}

	/**
	 * Renders the screen for the function "Run tests" which shows and
	 * runs the actual unit tests.
	 *
	 * @return void
	 */
	protected function runTests_renderRunningTest() {
		$selectedExtensionKey = $this->userSettingsService->getAsString('extSel');

		$testSuite = new PHPUnit_Framework_TestSuite('tx_phpunit_basetestsuite');
		$extensionKeysToProcess = $this->getTestFinder()->getTestablesForEverything();
		if ($selectedExtensionKey === Tx_Phpunit_Testable::ALL_EXTENSIONS) {
			$this->outputService->output('<h1>' . $this->translate('testing_all_extensions') . '</h1>');
		} else {
			$this->outputService->output(
				'<h1>' . $this->translate('testing_extension') . ': ' . htmlspecialchars($selectedExtensionKey) . '</h1>'
			);
			$extensionKeysToProcess = array($extensionKeysToProcess[$selectedExtensionKey]);
		}

		// Loads the files containing test cases from extensions.
		/** @var $extension Tx_Phpunit_Testable */
		foreach ($extensionKeysToProcess as $extension) {
			$testsPathOfExtension = $extension->getTestsPath();
			$testSuites = $this->getTestFinder()->findTestCaseFilesDirectory($testsPathOfExtension);
			foreach ($testSuites as $fileName) {
				require_once(realpath($testsPathOfExtension . $fileName));
			}
		}

		// Adds all classes to the test suite which end with "testcase" or "Test".
		foreach (get_declared_classes() as $class) {
			$classReflection = new ReflectionClass($class);
			if ($classReflection->isSubclassOf('Tx_Phpunit_TestCase')
				&& ((strtolower(substr($class, -8, 8)) === 'testcase') || (substr($class, -4, 4) === 'Test'))
				&& ($class !== 'Tx_Phpunit_TestCase') && ($class !== 'Tx_Phpunit_Database_TestCase')
			) {
				$testSuite->addTestSuite($class);
			} elseif ($this->userSettingsService->getAsBoolean('runSeleniumTests')
				&& $classReflection->isSubclassOf('Tx_Phpunit_Selenium_TestCase')
				&& ((strtolower(substr($class, -8, 8)) === 'testcase') || (substr($class, -4, 4) === 'Test'))
				&& ($class !== 'Tx_Phpunit_Selenium_TestCase')
			) {
				$testSuite->addTestSuite($class);
			}
		}

		$result = new PHPUnit_Framework_TestResult();

		if ($this->shouldCollectCodeCoverageInformation()) {
			$this->coverage = new PHP_CodeCoverage;
			$this->coverage->start('PHPUnit');
		}

		if ($this->userSettingsService->getAsBoolean('testdox')) {
			$this->testListener->useHumanReadableTextFormat();
		}

		if ($this->userSettingsService->getAsBoolean('showMemoryAndTime')) {
			$this->testListener->enableShowMenoryAndTime();
		}

		$result->addListener($this->testListener);

		$startMemory = memory_get_usage();
		$startTime = microtime(TRUE);

		if (t3lib_div::_GP('testname')) {
			$this->runTests_renderInfoAndProgressbar();
			/** @var $testCases PHPUnit_Framework_TestSuite */
			foreach ($testSuite->tests() as $testCases) {
				foreach ($testCases->tests() as $test) {
					if ($test instanceof PHPUnit_Framework_TestSuite) {
						/** @var $test PHPUnit_Framework_TestSuite */
						list($testSuiteName, $testName) = explode('::', $test->getName());
						$this->testListener->setTestSuiteName($testSuiteName);
						$testIdentifier = $testName . '(' . $testSuiteName . ')';
					} else {
						$testIdentifier = $test->toString();
						list($testSuiteName, $unused) = explode('::', $testIdentifier);
						$this->testListener->setTestSuiteName($testSuiteName);
					}
					if ($testIdentifier === t3lib_div::_GP('testname')) {
						if ($test instanceof PHPUnit_Framework_TestSuite) {
							$this->testListener->setTotalNumberOfTests($test->count());
						} else {
							$this->testListener->setTotalNumberOfTests(1);
						}
						$this->outputService->output('<h2 class="testSuiteName">Testsuite: ' . $testCases->getName() . '</h2>');
						$test->run($result);
					}
				}
			}
			if (!is_object($result)) {
				$this->outputService->output(
					'<h2 class="hadError">Error</h2><p>The test <strong> ' .
						htmlspecialchars(t3lib_div::_GP('testCaseFile')) . '</strong> could not be found.</p>'
				);
				return;
			}
		} elseif (t3lib_div::_GP('testCaseFile')) {
			$testCaseFileName = t3lib_div::_GP('testCaseFile');
			$this->testListener->setTestSuiteName($testCaseFileName);

			$suiteNameHasBeenDisplayed = FALSE;
			$totalNumberOfTestCases = 0;
			foreach ($testSuite->tests() as $testCases) {
				foreach ($testCases->tests() as $test) {
					if ($test instanceof PHPUnit_Framework_TestSuite) {
						list($testIdentifier, $unused) = explode('::', $test->getName());
					} else {
						$testIdentifier = get_class($test);
					}
					if ($testIdentifier === $testCaseFileName) {
						if ($test instanceof PHPUnit_Framework_TestSuite) {
							$totalNumberOfTestCases += $test->count();
						} else {
							$totalNumberOfTestCases++;
						}
					}
				}
			}
			$this->testListener->setTotalNumberOfTests($totalNumberOfTestCases);
			$this->runTests_renderInfoAndProgressbar();

			foreach ($testSuite->tests() as $testCases) {
				foreach ($testCases->tests() as $test) {
					if ($test instanceof PHPUnit_Framework_TestSuite) {
						list($testIdentifier, $unused) = explode('::', $test->getName());
					} else {
						$testIdentifier = get_class($test);
					}
					if ($testIdentifier === $testCaseFileName) {
						if (!$suiteNameHasBeenDisplayed) {
							$this->outputService->output('<h2 class="testSuiteName">Testsuite: ' . $testCaseFileName . '</h2>');
							$suiteNameHasBeenDisplayed = TRUE;
						}
						$test->run($result);
					}
				}
			}
			if (!is_object($result)) {
				$this->outputService->output(
					'<h2 class="hadError">Error</h2><p>The test <strong> ' .
						htmlspecialchars(t3lib_div::_GP('testname')) . '</strong> could not be found.</p>'
				);
				return;
			}
		} else {
			$this->testListener->setTotalNumberOfTests($testSuite->count());
			$this->runTests_renderInfoAndProgressbar();
			$testSuite->run($result);
		}

		$timeSpent = microtime(TRUE) - $startTime;
		$leakedMemory = memory_get_usage() - $startMemory;

		// Displays test statistics.
		if ($result->wasSuccessful()) {
			$testStatistics = '<h2 class="wasSuccessful">' . $this->translate('testing_success') . '</h2>';
		} else {
			if ($result->errorCount() > 0) {
				$testStatistics = '<script type="text/javascript">/*<![CDATA[*/setProgressBarClass("hadError");/*]]>*/</script>
					<h2 class="hadError">' . $this->translate('testing_failure') . '</h2>';
			} else {
				$testStatistics = '<script type="text/javascript">/*<![CDATA[*/setProgressBarClass("hadFailure");/*]]>*/</script>
					<h2 class="hadFailure">' . $this->translate('testing_failure') . '</h2>';
			}
		}
		$testStatistics .= '<p>' . $result->count() . ' ' . $this->translate('tests_total') . ', ' . $this->testListener->assertionCount() . ' ' .
			$this->translate('assertions_total') . ', ' . $result->failureCount() . ' ' . $this->translate('tests_failures') .
			', ' . $result->skippedCount() . ' ' . $this->translate('tests_skipped') . ', ' .
			$result->notImplementedCount() . ' ' . $this->translate('tests_notimplemented') . ', ' . $result->errorCount() .
			' ' . $this->translate('tests_errors') . ', <span title="' . $timeSpent . '&nbsp;' .
			$this->translate('tests_seconds') . '">' . round($timeSpent, 3) . '&nbsp;' . $this->translate('tests_seconds') .
			', </span>' . t3lib_div::formatSize($leakedMemory) . 'B (' . $leakedMemory . ' B) ' .
			$this->translate('tests_leaks') . '</p>';
		$this->outputService->output($testStatistics);

		$this->outputService->output(
			'<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">
				<p>
					<button type="submit" name="bingo" value="run" accesskey="r">' . $this->translate('run_again') . '</button>
					<input name="command" type="hidden" value="' . t3lib_div::_GP('command') . '" />
					<input name="testname" type="hidden" value="' . t3lib_div::_GP('testname') . '" />
					<input name="testCaseFile" type="hidden" value="' . t3lib_div::_GP('testCaseFile') . '" />
				</p>
			</form>' .
			'<div id="testsHaveFinished"></div>'
		);

		if ($this->shouldCollectCodeCoverageInformation()) {
			$this->coverage->stop();

			$codeCoverageDirectory = PATH_site . 'typo3temp/codecoverage/';
			if (!is_readable($codeCoverageDirectory) && !is_dir($codeCoverageDirectory)) {
				t3lib_div::mkdir($codeCoverageDirectory);
			}

			$coverageReport = new PHP_CodeCoverage_Report_HTML();
			$coverageReport->process($this->coverage, $codeCoverageDirectory);
			$this->outputService->output(
				'<p><a target="_blank" href="../typo3temp/codecoverage/index.html">' .
					'Click here to access the Code Coverage report</a></p>' .
					'<p>Memory peak usage: ' . t3lib_div::formatSize(memory_get_peak_usage()) . 'B<p/>'
			);
		}
	}

	/**
	 * Renders DIVs which contain information and a progressbar to visualize
	 * the running tests.
	 *
	 * The actual information will be written via JS during
	 * the test runs.
	 *
	 * @return void
	 */
	protected function runTests_renderInfoAndProgressbar() {
		$this->outputService->output(
			'<div class="progress-bar-wrap">
				<span id="progress-bar" class="wasSuccessful">&nbsp;</span>
				<span id="transparent-bar">&nbsp;</span>
			</div>'
		);
	}

	/*********************************************************
	 *
	 * Helper functions
	 *
	 *********************************************************/

	/**
	 * Renders a link which opens the current screen in a new window,
	 *
	 * @return string
	 */
	protected function openNewWindowLink() {
		$url = t3lib_div::getIndpEnv('TYPO3_REQUEST_SCRIPT') . '?M=tools_txphpunitbeM1';
		$onClick = "phpunitbeWin=window.open('" . $url .
				   "','phpunitbe','width=790,status=0,menubar=1,resizable=1,location=0,scrollbars=1,toolbar=0');phpunitbeWin.focus();return false;";
		$content = '<a id="opennewwindow" href="" onclick="' . htmlspecialchars($onClick) . '" accesskey="n">
				<img' . t3lib_iconWorks::skinImg($GLOBALS['BACK_PATH'], 'gfx/open_in_new_window.gif', 'width="19" height="14"') . ' title="' .
				   $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.openInNewWindow', 1) . '" class="absmiddle" alt="" />
				Ope<span class="access-key">n</span> in separate window.
			</a>
			<script type="text/javascript">/*<![CDATA[*/if (window.name === "phpunitbe") { document.getElementById("opennewwindow").style.display = "none"; }/*]]>*/</script>';

		return $content;
	}

	/**
	 * Recursively finds all test case files in the directory $directory.
	 *
	 * @param string $directory
	 *        the absolute path of the directory in which to look for test cases,
	 *        must not be empty
	 *
	 * @return array<array><string>
	 *         files names of the test cases in the directory $dir and all
	 *         its subdirectories relative to $dir, will be empty if no
	 *         test cases have been found
	 */
	protected function findTestCasesInDir($directory) {
		if (!is_dir($directory)) {
			return array();
		}

		$testCaseFileNames = $this->getTestFinder()->findTestCaseFilesDirectory($directory);

		$extensionsArr = array();
		if (!empty($testCaseFileNames)) {
			$extensionsArr[$directory] = $testCaseFileNames;
		}

		return $extensionsArr;
	}

	/**
	 * Includes all PHP files given in $paths.
	 *
	 * @param array<strings> $paths
	 *        array keys: absolute path
	 *        array values: file names in that path
	 *
	 * @return void
	 */
	protected function loadRequiredTestClasses(array $paths) {
		foreach ($paths as $path => $fileNames) {
			foreach ($fileNames as $fileName) {
				require_once(realpath($path . '/' . $fileName));
			}
		}
	}

	/**
	 * Creates the CSS style attribute content for an icon for the extension
	 * $extensionKey.
	 *
	 * @param string $extensionKey
	 *        the key of a loaded extension, may also be "typo3"
	 *
	 * @return string the content for the "style" attribute, will not be empty
	 *
	 * @throws Tx_Phpunit_Exception_NoTestsDirectory
	 *         if there is not extension with tests with the given key
	 */
	protected function createIconStyle($extensionKey) {
		if ($extensionKey === '') {
			throw new Tx_Phpunit_Exception_NoTestsDirectory('$extensionKey must not be empty.', 1303503647);
		}
		if (!$this->getTestFinder()->existsTestableForKey($extensionKey)) {
			throw new Tx_Phpunit_Exception_NoTestsDirectory('The extension ' . $extensionKey . ' is not loaded.', 1303503664);
		}

		$testable = $this->getTestFinder()->getTestableForKey($extensionKey);

		return 'background: url(' . $testable->getIconPath() . ') 3px 50% white no-repeat; padding: 1px 1px 1px 24px;';
	}

	/**
	 * Tests whether $class is the name of a class which can be run in the test
	 * runner.
	 *
	 * @param string $class class name to test, must not be empty
	 *
	 * @return boolean TRUE if the class is accepted, FALSE otherwise
	 */
	protected function isAcceptedTestSuitClass($class) {
		return !in_array($class, $this->ignoredTestSuitClasses);
	}

	/**
	 * Checks whether code coverage information should be collected.
	 *
	 * @return boolean whether code coverage information should be collected
	 */
	protected function shouldCollectCodeCoverageInformation() {
		return $this->userSettingsService->getAsBoolean('codeCoverage') && extension_loaded('xdebug');
	}
}
?>