<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2008-2011 Kasper Ligaard <kasperligaard@gmail.com>
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
 * Module "PHPUnit".
 *
 * @package TYPO3
 * @subpackage tx_phpunit
 *
 * @author Kasper Ligaard <kasperligaard@gmail.com>
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class tx_phpunit_module1 extends t3lib_SCbase {
	/**
	 * the extension key
	 *
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
	 * a test finder
	 *
	 * @var Tx_Phpunit_Service_TestFinder
	 */
	protected $testFinder = NULL;

	/**
	 * The constructor.
	 */
	public function __construct() {
		parent::init();

		$this->extensionPath = t3lib_extMgm::extRelPath(self::EXTENSION_KEY);

		$this->testFinder = t3lib_div::makeInstance('Tx_Phpunit_Service_TestFinder');
	}

	/**
	 * The destructor.
	 */
	public function __destruct() {
		unset($this->testFinder);
	}

	/**
	 * Returns the localized string for the key $key.
	 *
	 * @param string $key the key of the string to retrieve, must not be empty
	 * @param string $default default language value
	 *
	 * @return string the localized string for the key $key
	 */
	private function getLL($key, $default = '') {
		return $GLOBALS['LANG']->getLL($key, $default);
	}

	/**
	 * Creates the configuration for the function selector box.
	 *
	 * @return void
	 */
	public function menuConfig() {
		$this->MOD_MENU = array(
			'function' => array(
				'runtests' => $this->getLL('function_runtests'),
				'about' => $this->getLL('function_about'),
			),
			'extSel' => '',
			'failure' => '',
			'success' => '',
			'error' => '',
			'codeCoverage' => '',
		);
		parent::menuConfig();
	}

	/**
	 * Main function of the module. Outputs all content directly using echo
	 * instead of collecting it and doing the output later.
	 *
	 * @return void
	 */
	public function main() {
		global $BE_USER, $BACK_PATH;

		if ($BE_USER->user['admin']) {
			// Draw the header.
			$this->doc = t3lib_div::makeInstance('bigDoc');
			$this->doc->backPath = $BACK_PATH;
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
			echo $this->doc->startPage($this->getLL('title'));
			echo $this->doc->section(
				'',
				$this->doc->funcMenu(
					PHPUnit_Runner_Version::getVersionString(),
					t3lib_BEfunc::getFuncMenu(
						$this->id,
						'SET[function]',
						$this->MOD_SETTINGS['function'],
						$this->MOD_MENU['function']
					)
				)
			);

			// Render content:
			switch ($this->MOD_SETTINGS['function']) {
				case 'runtests' :
					$this->runTests_render();
					break;
				case 'about' :
					$this->about_render();
					break;
			}

			echo $this->doc->section(
				'Keyboard shortcuts',
				'<p>Use "a" for running all tests, use "s" for running a single test and
				use "r" to re-run the latest tests; to open phpunit in a new window, use "n".</p>
				<p>Depending on your browser and system you will need to press some
				modifier keys:</p>
				<ul>
				<li>Safari, IE and Firefox 1.x: Use "Alt" button on Windows, "Ctrl" on Macs.</li>
				<li>Firefox 2.x and 3.x: Use "Alt-Shift" on Windows, "Ctrl-Shift" on Macs</li>
				</ul>'
			);
			echo $this->doc->section('', $this->openNewWindowLink());
		} else {
			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $BACK_PATH;

			echo $this->doc->startPage($this->getLL('title'));
			echo $this->doc->header($this->getLL('title'));
			echo $this->getLL('admin_rights_needed');
		}

		echo $this->doc->endPage();
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
		if (($this->MOD_SETTINGS['extSel'] !== 'uuall') && !$this->isExtensionLoaded($this->MOD_SETTINGS['extSel'])) {
			// We know that phpunit must be loaded.
			$this->MOD_SETTINGS['extSel'] = 'phpunit';
		}
		$command = $this->MOD_SETTINGS['extSel'] ? t3lib_div::_GP('command') : '';
		switch ($command) {
			case 'runalltests':
			case 'runTestCaseFile':
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
		$extensionsWithTestSuites = $this->getExtensionsWithTestSuites();
		if (!is_array($extensionsWithTestSuites)) {
			return $this->getLL('could_not_find_exts_with_tests');
		}

		ksort($extensionsWithTestSuites);

		$output = $this->runTests_renderIntro_renderExtensionSelector($extensionsWithTestSuites);
		if ($this->MOD_SETTINGS['extSel'] && ($this->MOD_SETTINGS['extSel'] !== 'uuall')) {
			$output .= $this->runTests_renderIntro_renderTestSelector(
				$extensionsWithTestSuites,
				$this->MOD_SETTINGS['extSel']
			);
		}

		echo $output;
	}

	/**
	 * Renders the extension drop-down.
	 *
	 * @param array<string> $extensionsWithTestSuites
	 *        keys of the extensions for which test suites exist, may be empty
	 *
	 * @return string
	 *         HTML code for the drop-down and a surrounding form, will not be empty
	 */
	protected function runTests_renderIntro_renderExtensionSelector(
		array $extensionsWithTestSuites
	) {
		$extensionsOptionsArr = array();
		$extensionsOptionsArr[] = '<option value="">' . $this->getLL('select_extension') . '</option>';

		$selected = strcmp('uuall', $this->MOD_SETTINGS['extSel']) ? '' : ' selected="selected"';
		$extensionsOptionsArr[] = '<option class="alltests" value="uuall"' . $selected . '>' .
			$this->getLL('all_extensions') . '</option>';

		foreach (array_keys($extensionsWithTestSuites) as $dirName) {
			$style = $this->createIconStyle($dirName);
			$selected = strcmp($dirName, $this->MOD_SETTINGS['extSel']) ? '' : ' selected="selected"';
			if ($selected !== '') {
				$currentExtName = $dirName;
			}
			$extensionsOptionsArr[] = '<option style="' . $style . '" value="' . htmlspecialchars($dirName) . '"' . $selected . '>' .
				htmlspecialchars($dirName) . '</option>';
		}

		try {
			$style = $this->createIconStyle($currentExtName);
		} catch (Exception $exception) {
			$style = '';
		}

		$output = '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">
				<p>
				<select style="' . $style . '" name="SET[extSel]" onchange="jumpToUrl(\'' . htmlspecialchars($this->MCONF['_']) .
				'&amp;SET[extSel]=\'+this.options[this.selectedIndex].value,this);">' .
				implode('', $extensionsOptionsArr) .
				'</select>
				<button type="submit" name="bingo" value="run" accesskey="a">' .
				$this->getLL('run_all_tests') . '</button>
				<input type="hidden" name="command" value="runalltests" />
				</p>
			</form>';

		return $output;
	}

	/**
	 * Renders a drop-down for running single tests for the given extension.
	 *
	 * @param array<string> $extensionsWithTestSuites
	 *        keys of the extensions for which test suites exist
	 * @param string $extensionKey
	 *        keys of the extension for which to render the drop-down
	 *
	 * @return string HTML code with the selectorbox and a surrounding form
	 */
	protected function runTests_renderIntro_renderTestSelector(
		array $extensionsWithTestSuites, $extensionKey
	) {
		$testSuite = new PHPUnit_Framework_TestSuite('tx_phpunit_basetestsuite');

		// Loads the files containing test cases from extensions.
		$paths = $extensionsWithTestSuites[$extensionKey];

		if (isset($paths)) {
			foreach ($paths as $path => $fileNames) {
				foreach ($fileNames as $fileName) {
					require_once($path . $fileName);
				}
			}
		}

		// Adds all classes to the test suite which end with "testcase" (case-insensitive)
		// or "Test", except the two special classes used as superclasses.
		foreach (get_declared_classes() as $class) {
			$classReflection = new ReflectionClass($class);
			if ((strtolower(substr($class, -8, 8)) === 'testcase' || substr($class, -4, 4) === 'Test')
				&& $classReflection->isSubclassOf('PHPUnit_Framework_TestCase') && $class !== 'Tx_Phpunit_TestCase'
				&& $class !== 'Tx_Phpunit_Database_TestCase') {
				$testSuite->addTestSuite($class);
			}
		}

		// testCaseFile
		$testCaseFileOptionsArray = array();
		foreach ($testSuite->tests() as $testCases) {
			$selected = ($testCases->toString() === t3lib_div::_GP('testCaseFile')) ? ' selected="selected"' : '';
			$testCaseFileOptionsArray[] = '<option value="' . $testCases->toString() . '"' . $selected . '>' .
				htmlspecialchars($testCases->getName()) . '</option>';
		}

		// single test case
		$testsOptionsArr = array();
		$testCaseFile = t3lib_div::_GP('testCaseFile');
		foreach ($testSuite->tests() as $testCases) {
			if (!is_null($testCaseFile) && ($testCases->getName() !== $testCaseFile)) {
				continue;
			}
			foreach ($testCases->tests() as $test) {
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

		$currentStyle = $this->createIconStyle($extensionKey);

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

		$output = '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">
				<p>
					<select style="' . $currentStyle . '" name="testCaseFile">
					<option value="">' . $this->getLL('select_tests') . '</option>' . implode(chr(10), $testCaseFileOptionsArray) . '</select>
					<button type="submit" name="bingo" value="run" accesskey="f">' . $this->getLL('runTestCaseFile') . '</button>
					<input type="hidden" name="command" value="runTestCaseFile" />
				</p>
			</form>';
		$output .= '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">
				<p>
					<select style="' . $currentStyle . '" name="testname">
					<option value="">' . $this->getLL('select_tests') . '</option>' . $testOptionsHtml . '</select>
					<button type="submit" name="bingo" value="run" accesskey="s">' . $this->getLL('run_single_test') . '</button>
					<input type="hidden" name="command" value="runsingletest" />
					<input type="hidden" name="testCaseFile" value="' . $testCaseFile . '" />
				</p>
			</form>
		';

		$output .= '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">';
		$output .= '<div class="phpunit-controls">';
		$failureState = $this->MOD_SETTINGS['failure'] === 'on' ? 'checked="checked"' : '';
		$errorState = $this->MOD_SETTINGS['error'] === 'on' ? 'checked="checked"' : '';
		$skippedState = $this->MOD_SETTINGS['skipped'] === 'on' ? 'checked="checked"' : '';
		$successState = $this->MOD_SETTINGS['success'] === 'on' ? 'checked="checked"' : '';
		$notImplementedState = $this->MOD_SETTINGS['notimplemented'] === 'on' ? 'checked="checked"' : '';
		$showMemoryAndTime = $this->MOD_SETTINGS['showMemoryAndTime'] === 'on' ? 'checked="checked"' : '';
		$testdoxState = $this->MOD_SETTINGS['testdox'] === 'on' ? 'checked="checked"' : '';
		$output .= '<input type="checkbox" id="SET_success" ' . $successState . ' /><label for="SET_success">Success</label>';
		$output .= ' <input type="checkbox" id="SET_failure" ' . $failureState . ' /><label for="SET_failure">Failure</label>';
		$output .= ' <input type="checkbox" id="SET_skipped" ' . $skippedState . ' /><label for="SET_skipped">Skipped</label>';
		$output .= ' <input type="checkbox" id="SET_error" ' . $errorState . ' /><label for="SET_error">Error</label>';
		$output .= ' <input type="checkbox" id="SET_testdox" ' . $testdoxState .
				   ' /><label for="SET_testdox">Show as human readable</label>';
		$output .= ' <input type="checkbox" id="SET_notimplemented" ' . $notImplementedState .
				   ' /><label for="SET_notimplemented">Not implemented</label>';
		$output .= ' <input type="checkbox" id="SET_showMemoryAndTime" ' . $showMemoryAndTime .
				   '/><label for="SET_showMemoryAndTime">Show memory & time</label>';

		$codecoverageDisable = '';
		$codecoverageForLabelWhenDisabled = '';
		if (!extension_loaded('xdebug')) {
			$codecoverageDisable = ' disabled="disabled"';
			$codecoverageForLabelWhenDisabled = ' title="Code coverage requires XDebug to be installed."';
		}
		$codeCoverageState = $this->MOD_SETTINGS['codeCoverage'] === 'on' ? 'checked="checked"' : '';
		$output .= ' <input type="checkbox" id="SET_codeCoverage" ' . $codecoverageDisable . ' ' . $codeCoverageState .
			' /><label for="SET_codeCoverage"' . $codecoverageForLabelWhenDisabled .
			'>Collect code-coverage data</label>';
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
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['alwaysSimulateFrontendEnvironment']) {
			$this->simulateFrontendEnviroment();
		}

		$extensionsWithTestSuites = $this->getExtensionsWithTestSuites();
		$testSuite = new PHPUnit_Framework_TestSuite('tx_phpunit_basetestsuite');
		$extensionKeysToProcess = array();
		if ($this->MOD_SETTINGS['extSel'] === 'uuall') {
			echo '<h1>' . $this->getLL('testing_all_extensions') . '</h1>';
			$extensionKeysToProcess = array_keys($extensionsWithTestSuites);
		} else {
			echo '<h1>' . $this->getLL('testing_extension') . ': ' . htmlspecialchars($this->MOD_SETTINGS['extSel']) . '</h1>';
			$extInfo = $extensionsWithTestSuites[$this->MOD_SETTINGS['extSel']];
			$extensionsWithTestSuites = array();
			$extensionsWithTestSuites[$this->MOD_SETTINGS['extSel']] = $extInfo;
			$extensionKeysToProcess = array($this->MOD_SETTINGS['extSel']);
		}

		// Loads the files containing test cases from extensions.
		foreach ($extensionKeysToProcess as $extensionKey) {
			$paths = $extensionsWithTestSuites[$extensionKey];
			$this->loadRequiredTestClasses($paths);
		}

		// Adds all classes to the test suite which end with "testcase" or "Test".
		foreach (get_declared_classes() as $class) {
			$classReflection = new ReflectionClass($class);
			if ($classReflection->isSubclassOf('Tx_Phpunit_TestCase')
				&& (strtolower(substr($class, -8, 8)) === 'testcase' || substr($class, -4, 4) === 'Test')
				&& ($class !== 'Tx_Phpunit_TestCase') && ($class !== 'Tx_Phpunit_Database_TestCase')
			) {
				$testSuite->addTestSuite($class);
			}
		}

		$result = new PHPUnit_Framework_TestResult();

		// Sets to collect code coverage information.
		if (($GLOBALS['BE_USER']->uc['moduleData']['tools_txphpunitM1']['codeCoverage'] === 'on')
			&& extension_loaded('xdebug')
		) {
			$result->collectCodeCoverageInformation(TRUE);
		}

		$testListener = new Tx_PhpUnit_BackEnd_TestListener();
		if ($this->MOD_SETTINGS['testdox'] === 'on') {
			$testListener->useHumanReadableTextFormat();
		}

		if ($this->MOD_SETTINGS['showMemoryAndTime'] === 'on') {
			$testListener->enableShowMenoryAndTime();
		}

		$result->addListener($testListener);

		$startMemory = memory_get_usage();
		$startTime = microtime(TRUE);

		if (t3lib_div::_GP('testname')) {
			$testListener->setTotalNumberOfTests(1);
			$this->runTests_renderInfoAndProgressbar();
			foreach ($testSuite->tests() as $testCases) {
				foreach ($testCases->tests() as $test) {
					if ($test instanceof PHPUnit_Framework_TestSuite) {
						list($testSuiteName, $testName) = explode('::', $test->getName());
						$testListener->setTestSuiteName($testSuiteName);
						$testIdentifier = $testName . '(' . $testSuiteName . ')';
					} else {
						$testIdentifier = $test->toString();
						list($testSuiteName, $testName) = explode('::', $testIdentifier);
						$testListener->setTestSuiteName($testSuiteName);
					}
					if ($testIdentifier === t3lib_div::_GP('testname')) {
						echo '<h2 class="testSuiteName">Testsuite: ' . $testCases->getName() . '</h2>';
						$test->run($result);
					}
				}
			}
			if (!is_object($result)) {
				echo '<h2 class="hadError">Error</h2>' . '<p>The test <strong> ' .
					htmlspecialchars(t3lib_div::_GP('testCaseFile')) . '</strong> could not be found.</p>';
				return;
			}
		} elseif (t3lib_div::_GP('testCaseFile')) {
			$testCaseFileName = t3lib_div::_GP('testCaseFile');
			$testListener->setTestSuiteName($testCaseFileName);

			$suiteNameHasBeenDisplayed = FALSE;
			$totalNumberOfTestCases = 0;
			foreach ($testSuite->tests() as $testCases) {
				foreach ($testCases->tests() as $test) {
					if ($test instanceof PHPUnit_Framework_TestSuite) {
						list($testIdentifier, $testName) = explode('::', $test->getName());
					} else {
						$testIdentifier = get_class($test);
					}
					if ($testIdentifier === $testCaseFileName) {
						$totalNumberOfTestCases++;
					}
				}
			}
			$testListener->setTotalNumberOfTests($totalNumberOfTestCases);
			$this->runTests_renderInfoAndProgressbar();

			foreach ($testSuite->tests() as $testCases) {
				foreach ($testCases->tests() as $test) {
					if ($test instanceof PHPUnit_Framework_TestSuite) {
						list($testIdentifier, $testName) = explode('::', $test->getName());
					} else {
						$testIdentifier = get_class($test);
					}
					if ($testIdentifier === $testCaseFileName) {
						if (!$suiteNameHasBeenDisplayed) {
							echo '<h2 class="testSuiteName">Testsuite: ' . $testCaseFileName . '</h2>';
							$suiteNameHasBeenDisplayed = TRUE;
						}
						$test->run($result);
					}
				}
			}
			if (!is_object($result)) {
				echo '<h2 class="hadError">Error</h2>' . '<p>The test <strong> ' .
					htmlspecialchars(t3lib_div::_GP('testname')) . '</strong> could not be found.</p>';
				return;
			}
		} else {
			$testListener->setTotalNumberOfTests($testSuite->count());
			$this->runTests_renderInfoAndProgressbar();
			$testSuite->run($result);
		}

		$timeSpent = microtime(TRUE) - $startTime;
		$leakedMemory = memory_get_usage() - $startMemory;

		// Displays test statistics.
		$testStatistics = '';
		if ($result->wasSuccessful()) {
			$testStatistics = '<h2 class="wasSuccessful">' . $this->getLL('testing_success') . '</h2>';
		} else {
			$testStatistics = '<script type="text/javascript">/*<![CDATA[*/setProgressBarClass("hadFailure");/*]]>*/</script>
				<h2 class="hadFailure">' . $this->getLL('testing_failure') . '</h2>';
		}
		$testStatistics .= '<p>' . $result->count() . ' ' . $this->getLL('tests_total') . ', ' . $testListener->assertionCount() . ' ' .
			$this->getLL('assertions_total') . ', ' . $result->failureCount() . ' ' . $this->getLL('tests_failures') .
			', ' . $result->skippedCount() . ' ' . $this->getLL('tests_skipped') . ', ' .
			$result->notImplementedCount() . ' ' . $this->getLL('tests_notimplemented') . ', ' . $result->errorCount() .
			' ' . $this->getLL('tests_errors') . ', ' . '<span title="' . $timeSpent . '&nbsp;' .
			$this->getLL('tests_seconds') . '">' . round($timeSpent, 3) . '&nbsp;' . $this->getLL('tests_seconds') .
			', </span>' . t3lib_div::formatSize($leakedMemory) . 'B (' . $leakedMemory . ' B) ' .
			$this->getLL('tests_leaks') . '</p>';
		echo $testStatistics;

		echo '<form action="' . htmlspecialchars($this->MCONF['_']) . '" method="post">
				<p>
					<button type="submit" name="bingo" value="run" accesskey="r">' . $this->getLL('run_again') . '</button>
					<input name="command" type="hidden" value="' . t3lib_div::_GP('command') . '" />
					<input name="testname" type="hidden" value="' . t3lib_div::_GP('testname') . '" />
					<input name="testCaseFile" type="hidden" value="' . t3lib_div::_GP('testCaseFile') . '" />
				</p>
			</form>' .
			'<div id="testsHaveFinished"></div>';

		if (!t3lib_div::_GP('testname') && $result->getCollectCodeCoverageInformation()) {
			require_once('PHP/CodeCoverage/Report/HTML.php');

			$writer =  new PHP_CodeCoverage_Report_HTML();
			$writer->process($result->getCodeCoverage(), t3lib_extMgm::extPath('phpunit') . 'codecoverage/');
			echo '<p><a target="_blank" href="' . $this->extensionPath . 'codecoverage/index.html">Click here to access the Code Coverage report</a></p>';
			echo '<p>Memory peak usage: ' . t3lib_div::formatSize(memory_get_peak_usage()) . 'B<p/>';
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
		echo '<div class="progress-bar-wrap">
				<span id="progress-bar" class="wasSuccessful">&nbsp;</span>
				<span id="transparent-bar">&nbsp;</span>
			</div>';
	}

	/**
	 * Roughly simulates the front end although being in the back end.
	 *
	 * @todo This is a quick hack. It still needs proper implementation.
	 *
	 * @return void
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

	/**
	 * Renders the "About" screen.
	 *
	 * @return void
	 */
	protected function about_render() {
		$codeCoverageDir['exists'] = file_exists($this->extensionPath . 'codecoverage');
		if ($codeCoverageDir['exists']) {
			$codeCoverageDir['writeable'] = is_writeable($this->extensionPath . 'codecoverage');
			$codeCoverageDir['readable'] = is_readable($this->extensionPath . 'codecoverage');
		}
		echo '<img src="' . $this->extensionPath .
			'Resources/Public/Icons/PHPUnit.gif" width="94" height="80" alt="PHPUnit" title="PHPUnit" style="float:right; margin-left:10px;" />';
		$excludeExtensions = t3lib_div::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['excludeextensions']);
		echo '
		<h1>About PHPUnit Backend Module</h1>
		<p>PHPUnit BE is a <a href="http://en.wikipedia.org/wiki/Unit_testing">unit testing</a> framework based on <a href="http://www.phpunit.de/" target="_new">PHPUnit</a> by Sebastian Bergmann. It offers smooth integration
		of the PHPUnit framework into TYPO3 and offers an API and many functions which make unit testing of TYPO3 extensions easy and comfortable.</p>
		<h2>Get test-infected!</h2>
		<p>If you think writing tests are dull, then try it. <a href="http://junit.sourceforge.net/doc/testinfected/testing.htm">You might become test-infected</a>!</p>
		<h2>Current include path</h2>
		<p>Below are the paths of the includepath that phpunit currently uses to locate PHPUnit:</p>
		<pre>' . join(chr(10), explode(PATH_SEPARATOR, get_include_path())) . '</pre>
		<h2>Currently excluded extension</h2>
		<p>The following extensions are excluded from being searched for tests:</p>
		<pre>' . join(chr(10), $excludeExtensions) . '</pre>
		<p>Note: The extension exclusion list can be changed in the extension manager.</p>
		<h2>Current memory limit</h2>
		<p>When using XDebug to collect code coverage data, you will need the memory limit to be set rather high. Something like 256MB will probably be needed.</p>
		<p>On this PHP installation the memory limit is currently set to: ' . ini_get('memory_limit') . '</p>
		<h2>This extension has bugs...</h2>
		<P><a target="_blank" href="http://forge.typo3.org/projects/extension-phpunit/issues">Issues can be seen and posted by clicking this link, http://forge.typo3.org/projects/extension-phpunit/issues</a>.</p>
		<p>You can report an issue by following the above link. An issue can be e.g. a bug or an improvement/enhancement.</p>
		<p>Older issues: <a target="_blank" href="http://bugs.typo3.org/search.php?project_id=79&amp;sticky_issues=on&amp;sortby=last_updated&amp;dir=DESC&amp;hide_status_id=90">Click to see the list of (preferably older) issues for this extension</a>.</p>
		<h2>Browse code in Subversion repository</h2>
		<p><a target="_blank" href="http://forge.typo3.org/repositories/show/extension-phpunit">The code repository for the phpunit extension can be browsed here, http://forge.typo3.org/repositories/show/extension-phpunit</a>.</p>
		';
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
		global $BACK_PATH;

		// FIXME: Needs to take mod.php into account, when generating URL here. Otherwise 'Open link in new window' will not work (gives error: Value "" for "M" was not found as a module).
		$url = t3lib_div::getIndpEnv('TYPO3_REQUEST_SCRIPT') . '?M=tools_txphpunitbeM1';
		$onClick = "phpunitbeWin=window.open('" . $url .
				   "','phpunitbe','width=790,status=0,menubar=1,resizable=1,location=0,scrollbars=1,toolbar=0');phpunitbeWin.focus();return false;";
		$content = '<a id="opennewwindow" href="" onclick="' . htmlspecialchars($onClick) . '" accesskey="n">
				<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/open_in_new_window.gif', 'width="19" height="14"') . ' title="' .
				   $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.openInNewWindow', 1) . '" class="absmiddle" alt="" />
				Ope<span class="access-key">n</span> in separate window.
			</a>
			<script type="text/javascript">/*<![CDATA[*/if (window.name === "phpunitbe") { document.getElementById("opennewwindow").style.display = "none"; }/*]]>*/</script>';

		return $content;
	}

	/**
	 * Scans all available extensions for test case files.
	 *
	 * @return array<array><string>
	 *         first-level array keys: extension key
	 *         second level array values: paths to the test case files relative
	 *         to the extension directory
	 */
	protected function getExtensionsWithTestSuites() {
		$excludeExtensions = t3lib_div::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['excludeextensions']);
		$outOfLineTestCases = $this->traversePathForTestCases(
			$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['outoflinetestspath']
		);

		$extList = explode(',', $GLOBALS['TYPO3_CONF_VARS']['EXT']['extList']);

		$extensionsOwnTestCases = array();
		foreach ($extList as $extKey) {
			$extPath = t3lib_extMgm::extPath($extKey);
			if (is_dir($extPath . 'Tests/')) {
				$testCasesDirectory = $extPath . 'Tests/';
			} else {
				$testCasesDirectory = $extPath . 'tests/';
			}

			$testCasesArr = $this->findTestCasesInDir($testCasesDirectory);
			if (!empty($testCasesArr)) {
				$extensionsOwnTestCases[$extKey] = $testCasesArr;
			}
		}

		$coreTestCases = array();
		if ($this->testFinder->hasCoreTests()) {
			$coreTestCases['typo3'] = $this->findTestCasesInDir($this->testFinder->getAbsoluteCoreTestsPath());
		}

		$totalTestsArr = array_merge_recursive($outOfLineTestCases, $extensionsOwnTestCases, $coreTestCases);

		$returnTestsArr = array_diff_key($totalTestsArr, array_flip($excludeExtensions));
		return $returnTestsArr;
	}

	/**
	 * Traverses a given path recursively for *testcase.php files.
	 *
	 * @param string $path
	 *        the absolute path to descend from, must not be empt
	 *
	 * @return array<array><string>
	 *         first-level array keys: directory names within $path
	 *         second level array values: paths to the test case files relative
	 *         to the corresponding directory
	 */
	private function traversePathForTestCases($path) {
		if (!is_dir($path)) {
			return array();
		}

		$extensionsArr = array();
		$dirs = t3lib_div::get_dirs($path);
		if (is_array($dirs)) {
			sort($dirs);
			foreach ($dirs as $dirName) {
				if ($this->isExtensionLoaded($dirName)) {
					$testsPath = $path . $dirName . '/tests/';
					$extensionsArr[$dirName] = $this->findTestCasesInDir($testsPath);
				}
			}
		}

		return $extensionsArr;
	}

	/**
	 * Recursively finds all test case files in the directory $dir.
	 *
	 * @param string $dir
	 *        the absolute path of the directory in which to look for test cases,
	 *        must not be empty
	 *
	 * @return array<array><string>
	 *         files names of the test cases in the directory $dir and all
	 *         its subdirectories relative to $dir, will be empty if no
	 *         test cases have been found
	 */
	private function findTestCasesInDir($dir) {
		if (!is_dir($dir)) {
			return array();
		}

		$testCaseFileNames = $this->testFinder->findTestCasesInDirectory($dir);

		$extensionsArr = array();
		if (!empty($testCaseFileNames)) {
			$extensionsArr[$dir] = $testCaseFileNames;
		}

		return $extensionsArr;
	}

	/**
	 * Includes all PHP files provided in $paths.
	 *
	 * @param array<strings>|NULL
	 *        array keys: absolute path
	 *        array values: file name in that path
	 *
	 * @return void
	 */
	protected function loadRequiredTestClasses($paths) {
		if (!isset($paths)) {
			return;
		}

		foreach ($paths as $path => $fileNames) {
			foreach ($fileNames as $fileName) {
				require_once(realpath($path . '/' . $fileName));
			}
		}
	}

	/**
	 * Checks whether a extension is valid and the extension is loaded.
	 *
	 * "typo3" is considered as a valid extension key for this purpose, too.
	 *
	 * @param string $extensionKey
	 *        the key of the extension to test, may be empty
	 *
	 * @return boolean
	 *         TRUE if an extension with the key $extensionKey is loaded, FALSE otherwise
	 */
	private function isExtensionLoaded($extensionKey) {
		if ($extensionKey === '') {
			return FALSE;
		}

		return ($extensionKey === 'typo3') || t3lib_extMgm::isLoaded($extensionKey);
	}

	/**
	 * Creates the CSS style attribute content for an icon for the extension
	 * $extensionKey.
	 *
	 * @param string $extensionKey
	 *        the key of a loaded extension, may also be "typo3"
	 *
	 * @return string the content for the "style" attribute, will not be empty
	 */
	private function createIconStyle($extensionKey) {
		if (!$this->isExtensionLoaded($extensionKey)) {
			throw new Exception('The extension ' . $extensionKey . ' is not loaded.');
		}

		$result = 'background: white no-repeat ';

		if ($extensionKey === 'typo3') {
			$result .= 'url(' . t3lib_extMgm::extRelPath('phpunit') . 'Resources/Public/Icons/Typo3.png) 3px 50%;';
		} else {
			$result .= 'url(' . t3lib_extMgm::extRelPath($extensionKey) . 'ext_icon.gif) 3px 50%;';
		}

		$result .= ' padding: 1px 1px 1px 24px;';

		return $result;
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/phpunit/mod1/class.tx_phpunit_module1.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/phpunit/mod1/class.tx_phpunit_module1.php']);
}
?>