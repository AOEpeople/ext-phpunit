<?php
/***************************************************************
* Copyright notice
*
* (c) 2008-2009 Kasper Ligaard <ligaard@daimi.au.dk>
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

require_once (PATH_t3lib . 'class.t3lib_scbase.php');

/**
 * Module 'PHPUnit' for the 'phpunit' extension.
 *
 * @author Kasper Ligaard <ligaard@daimi.au.dk>
 *
 * @package TYPO3
 * @subpackage tx_phpunit
 */
class tx_phpunit_module1 extends t3lib_SCbase {
	/**
	 * @var string the extension key
	 */
	const EXTENSION_KEY = 'phpunit';
	/**
	 * @var string the relative path to this extension
	 */
	protected $extensionPath;

	/**
	 * Returns the localized string for the key $key.
	 *
	 * @param string $key The key of the string to retrieve, must not be empty
	 * @param string $default OPTIONAL default language value
	 * @return string the localized string for the key $key
	 */
	private function getLL($key, $default = '') {
		return $GLOBALS['LANG']->getLL($key, $default);
	}

	/**
	 * The constructor.
	 */
	public function __construct() {
		parent::init();

		$this->extensionPath = t3lib_extMgm::extRelPath(self::EXTENSION_KEY);
	}

	/**
	 * Creates the configuration for the function selector box.
	 */
	public function menuConfig() {
		$this->MOD_MENU = array(
			'function' => array(
				'runtests' => $this->getLL('function_runtests'),
				'about' => $this->getLL('function_about'),
				'news' => $this->getLL('function_news'),
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
	 */
	public function main() {
		global $BE_USER,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

		if ($BE_USER->user['admin']) {
			// Draw the header.
			$this->doc = t3lib_div::makeInstance('bigDoc');
			$this->doc->backPath = $BACK_PATH;
			$this->doc->docType = 'xhtml_strict';
			$this->doc->bodyTagAdditions = 'id="doc3"';

			// JavaScript Libraries
			$this->doc->loadJavascriptLib('contrib/prototype/prototype.js');
			$this->doc->loadJavascriptLib($this->extensionPath.'mod1/yui/yahoo-dom-event.js');
			$this->doc->loadJavascriptLib($this->extensionPath.'mod1/yui/connection-min.js');
			$this->doc->loadJavascriptLib($this->extensionPath.'mod1/yui/json-min.js');
			$this->doc->loadJavascriptLib($this->extensionPath.'mod1/tx_phpunit_module1.js');

			// Mis-using JScode to insert CSS _after_ skin.
			$this->doc->JScode = '<link rel="stylesheet" type="text/css" href="'.$this->extensionPath.'mod1/yui/reset-fonts-grids.css" />';
			$this->doc->JScode .= '<link rel="stylesheet" type="text/css" href="'.$this->extensionPath.'mod1/yui/base-min.css" />';
			$this->doc->JScode .= '<link rel="stylesheet" type="text/css" href="'.$this->extensionPath.'mod1/phpunit-be.css" />';

			echo $this->doc->startPage($this->getLL('title'));
			echo $this->doc->section('', $this->doc->funcMenu(PHPUnit_Runner_Version::getVersionString(), t3lib_BEfunc::getFuncMenu($this->id, 'SET[function]', $this->MOD_SETTINGS['function'], $this->MOD_MENU['function'])));

			// disables output buffering so the result of each test is visible
			// immediately
			ob_end_flush();

				// Render content:
			switch ($this->MOD_SETTINGS['function']) {
				case 'runtests' :
					$this->runTests_render();
					break;
				case 'about' :
					$this->about_render();
					break;
				case 'news' :
					$this->news_render();
					break;
			}

			echo $this->doc->section('Keyboard shortcuts', '
			<p>Use "a" for running all tests, use "s" for running a single test and
			use "r" to re-run the latest tests; to open phpunit in a new window, use "n".</p>
			<p>Depending on your browser and system you will need to press some
			modifier keys:</p>
			<ul>
			<li>Safari, IE and Firefox 1.x: Use "Alt" button on Windows, "Ctrl" on Macs.</li>
			<li>Firefox 2.x and 3.x: Use "Alt-Shift" on Windows, "Ctrl-Shift" on Macs</li>
			</ul>
			');
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
	 * 	Screen render functions
	 *
	 *********************************************************/

	/**
	 * Renders the screens for function "Run tests"
	 *
	 * @return	void
	 * @access	protected
	 */
	protected function runTests_render() {
		if (!$this->isExtensionLoaded($this->MOD_SETTINGS['extSel'])) {
			$this->MOD_SETTINGS['extSel'] = 'phpunit'; // We know that phpunit must be loaded
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
	 * Renders the intro screen for the function "Run tests"
	 *
	 * @return	void
	 * @access	protected
	 */
	protected function runTests_renderIntro() {
		$output = '';

		$extensionsWithTestSuites = $this->getExtensionsWithTestSuites();
		if (is_array($extensionsWithTestSuites)) {
			ksort($extensionsWithTestSuites);

			$output = $this->runTests_renderIntro_renderExtensionSelector($extensionsWithTestSuites);
			if ($this->MOD_SETTINGS['extSel'] && $this->MOD_SETTINGS['extSel']!='uuall') {
				$output .= $this->runTests_renderIntro_renderTestSelector($extensionsWithTestSuites, $this->MOD_SETTINGS['extSel']);
			}

		} else {
			$output = $this->getLL('could_not_find_exts_with_tests');
		}

		echo $output;
	}

	/**
	 * Renders the extension selectorbox
	 *
	 * @param	array		$extensionsWithTestSuites: Array of extension keys for which test suites exist
	 * @return	string		HTML code for the selectorbox and a surrounding form
	 * @access	protected
	 */
	protected function runTests_renderIntro_renderExtensionSelector($extensionsWithTestSuites) {

		$extensionsOptionsArr =array();
		$extensionsOptionsArr[] = '<option value="">'.$this->getLL('select_extension').'</option>';

		$selected = strcmp('uuall', $this->MOD_SETTINGS['extSel']) ? '' : ' selected="selected"';
		$extensionsOptionsArr[] = '<option class="alltests" value="uuall"'.$selected.'>'.$this->getLL('all_extensions').'</option>';

		foreach (array_keys($extensionsWithTestSuites) as $dirName) {
			$style = $this->createIconStyle($dirName);
			$selected = strcmp($dirName, $this->MOD_SETTINGS['extSel']) ? '' : ' selected="selected"';
			if ($selected != '') {
				$currentExtName = $dirName;
			}
			$extensionsOptionsArr[]='<option style="'.$style.'" value="'.htmlspecialchars($dirName).'"'.$selected.'>'.htmlspecialchars($dirName).'</option>';
		}

		try {
			$style = $this->createIconStyle($currentExtName);
		} catch (Exception $exception) {
			$style = '';
		}

		$output = self::eAccelerator0951OptimizerHelp();
		$output .= '
			<form action="'.htmlspecialchars($this->MCONF['_']).'" method="post">
				<p>
                	<select style="' . $style . '" name="SET[extSel]" onchange="jumpToUrl(\''.htmlspecialchars($this->MCONF['_']).'&SET[extSel]=\'+this.options[this.selectedIndex].value,this);">'.implode('', $extensionsOptionsArr).'</select>
					<button type="submit" name="bingo" value="run" accesskey="a">'.$this->getLL('run_all_tests').'</button>
					<input type="hidden" name="command" value="runalltests" />
				</p>
			</form>
		';

		return $output;
	}

	/**
	 * Renders a selector box for running single tests for the given extension
	 *
	 * @param	array		$extensionsWithTestSuites: Array of extension keys for which test suites exist
	 * @param	string		$extensionKey: Extension key of the extensino to run single test for
	 * @return	string		HTML code with the selectorbox and a surrounding form
	 * @access	protected
	 */
	protected function runTests_renderIntro_renderTestSelector($extensionsWithTestSuites, $extensionKey) {
		$testSuite = new PHPUnit_Framework_TestSuite('tx_phpunit_basetestsuite');

		// Load the files containing test cases from extensions:
		$paths = $extensionsWithTestSuites[$extensionKey];

		if (isset($paths)) {
			foreach ($paths as $path => $fileNames) {
				foreach ($fileNames as $fileName) {
					require_once ($path.$fileName);
				}
			}
		}

		// Add all classes to the test suite which end with "testcase" (case-insensitive), except the two special classes used as super-classes.
		foreach (get_declared_classes() as $class) {
			$classReflection = new ReflectionClass($class);
			if (strtolower(substr($class, -8, 8)) == 'testcase' &&
				$classReflection->isSubclassOf('PHPUnit_Framework_TestCase') &&
				$class !== 'tx_phpunit_testcase'	&&
				$class !== 'tx_t3unit_testcase' &&
				$class !== 'tx_phpunit_database_testcase') {
				$testSuite->addTestSuite($class);
			}
		}


			// testCaseFile
		$testCaseFileOptionsArray = array();
		foreach ($testSuite->tests() as $testCases) {
				$selected = $testCases->toString() == t3lib_div::_GP('testCaseFile') ? ' selected="selected"' : '';
				$testCaseFileOptionsArray[] = '<option value="'.$testCases->toString().'"'.$selected.'>'.htmlspecialchars($testCases->getName()).'</option>';
		}
			// single test case
		$testsOptionsArr = array();
		foreach ($testSuite->tests() as $testCases) {
			foreach ($testCases->tests() as $test) {
				$selected = $test->toString() == t3lib_div::_GP('testname') ? ' selected="selected"' : '';
				$testSuiteName = strstr($test->toString(), '(');
				$testSuiteName = trim($testSuiteName, '()');
				$testsOptionsArr[$testSuiteName][] .= '<option value="'.$test->toString().'"'.$selected.'>'.htmlspecialchars($test->getName()).'</option>';
			}
		}

		$currentStyle = $this->createIconStyle($extensionKey);


		// build options for select (incl. option groups for test suites)
		$testOptionsHtml         = '';
		foreach ($testsOptionsArr as $suiteName => $testArr) {
			$testOptionsHtml .= '<optgroup label="'.$suiteName.'">';
			foreach ($testArr as $testHtml) {
				$testOptionsHtml .= $testHtml;
			}
			$testOptionsHtml .= '</optgroup>';
		}

		$currentStyle = $this->createIconStyle($extensionKey);

		$output = '
			<form action="'.htmlspecialchars($this->MCONF['_']).'" method="post">
				<p>
					<select style="'.$currentStyle.'" name="testCaseFile">
					<option value="">'.$this->getLL('select_tests').'</option>'
					 . implode('\n', $testCaseFileOptionsArray) .
					'</select>
					<button type="submit" name="bingo" value="run" accesskey="f">'.$this->getLL('runTestCaseFile').'</button>
					<input type="hidden" name="command" value="runTestCaseFile" />
				</p>
			</form>
		';
		$output .= '
			<form action="'.htmlspecialchars($this->MCONF['_']).'" method="post">
				<p>
					<select style="'.$currentStyle.'" name="testname">
					<option value="">'.$this->getLL('select_tests').'</option>'.
					$testOptionsHtml.
					'</select>
					<button type="submit" name="bingo" value="run" accesskey="s">'.$this->getLL('run_single_test').'</button>
					<input type="hidden" name="command" value="runsingletest" />
				</p>
			</form>
		';

		// Experimental: Buttons for turning failures, errors or success on/off
		$output .= '<div class="phpunit-controls">';
		$output .= '<form action="'.htmlspecialchars($this->MCONF['_']).'" method="post">';
		$failureState = $this->MOD_SETTINGS['failure'] === 'on' ? 'checked="checked"' : '';
		$errorState = $this->MOD_SETTINGS['error'] === 'on' ? 'checked="checked"' : '';
		$skippedState = $this->MOD_SETTINGS['skipped'] === 'on' ? 'checked="checked"' : '';
		$successState = $this->MOD_SETTINGS['success'] === 'on' ? 'checked="checked"' : '';
		$notImplementedState = $this->MOD_SETTINGS['notimplemented'] === 'on' ? 'checked="checked"' : '';
		$testdoxState = $this->MOD_SETTINGS['testdox'] === 'on' ? 'checked="checked"' : '';
		$output .= '<label for="SET[success]"><input type="checkbox" id="SET[success]" name="SET[success]" '.$successState.'>Success</label>';
		$output .= '<label for="SET[failure]"><input type="checkbox" id="SET[failure]" name="SET[failure]" '.$failureState.'>Failure</label>';
		$output .= '<label for="SET[skipped]"><input type="checkbox" id="SET[skipped]"name="SET[skipped]" '.$skippedState.'>Skipped</label>';
		$output .= '<label for="SET[error]"><input type="checkbox" id="SET[error]"name="SET[error]" '.$errorState.'>Error</label>';
		$output .= '<label for="SET[testdox]"><input type="checkbox" id="SET[testdox]"name="SET[testdox]" ' . $testdoxState . '>Show as human readable</label>';
		$output .= '<label for="SET[notimplemented]"><input type="checkbox" id="SET[notimplemented]"name="SET[notimplemented]" '.$notImplementedState.'>Not implemented</label>';

        $codecoverageDisable = '';
        $codecoverageForLabelWhenDisabled = '';
		if (!extension_loaded('xdebug')) {
        	$codecoverageDisable = ' disabled="disabled"';
        	$codecoverageForLabelWhenDisabled = ' title="Code coverage requires XDebug to be installed."';
        }
		$codeCoverageState = $this->MOD_SETTINGS['codeCoverage'] === 'on' ? 'checked="checked"' : '';
       	$output .= '<label for="SET[codeCoverage]"'.$codecoverageForLabelWhenDisabled.'><input type="checkbox" id="SET[codeCoverage]"name="SET[codeCoverage]"'.$codecoverageDisable.' '.$codeCoverageState.'>Collect code-coverage data</label>';
		$output .= '</form>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Renders the screen for the function "Run tests" which shows and
	 * runs the actual unit tests
	 *
	 * @return	void
	 * @access	protected
	 */
	protected function runTests_renderRunningTest() {
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['alwaysSimulateFrontendEnvironment']) {
			$this->simulateFrontendEnviroment();
		}

		$extensionsWithTestSuites = $this->getExtensionsWithTestSuites();
		$testSuite = new PHPUnit_Framework_TestSuite('tx_phpunit_basetestsuite');
		$extensionKeysToProcess = array();
		if ($this->MOD_SETTINGS['extSel']=='uuall') {
			echo '<h3>'.$this->getLL('testing_all_extensions').'</h3>';
			$extensionKeysToProcess = array_keys($extensionsWithTestSuites);
		} else {
			echo '<h3>'.$this->getLL('testing_extension').': '.htmlspecialchars($this->MOD_SETTINGS['extSel']).'</h3>';
			$extInfo = $extensionsWithTestSuites[$this->MOD_SETTINGS['extSel']];
			$extensionsWithTestSuites = array();
			$extensionsWithTestSuites[$this->MOD_SETTINGS['extSel']] = $extInfo;
			$extensionKeysToProcess = array($this->MOD_SETTINGS['extSel']);
		}


		// Load the files containing test cases from extensions:
		foreach ($extensionKeysToProcess as $extensionKey) {
			$paths = $extensionsWithTestSuites[$extensionKey];
			self::loadRequiredTestClasses($paths);
		}

			// Add all classes to the test suite which end with "testcase"
		foreach (get_declared_classes() as $class) {
			if (strtolower(substr($class, -8, 8)) == 'testcase' && $class != 'tx_phpunit_testcase' && $class != 'tx_phpunit_database_testcase' && $class != 'tx_t3unit_testcase') {
				$testSuite->addTestSuite($class);
			}
		}

		// Create a result object and configure it.
		$result = new PHPUnit_Framework_TestResult();

		// Set to collect code coverage information.
		if ($GLOBALS['BE_USER']->uc['moduleData']['tools_txphpunitM1']['codeCoverage'] === 'on' &&
             extension_loaded('xdebug')) {
            $result->collectCodeCoverageInformation(TRUE);
        }

		$testListener = new tx_phpunit_testlistener();
		if ( $this->MOD_SETTINGS['testdox'] == 'on')
			$testListener->useHumanReadableTextFormat();

		$result->addListener($testListener);

		$startMemory = memory_get_usage();
		$startTime = microtime(true);

		if (t3lib_div::_GP('testname')) {
			$testListener->totalNumberOfTestCases = 1;
			$this->runTests_renderInfoAndProgressbar(1);
			foreach ($testSuite->tests() as $testCases) {
				foreach ($testCases->tests() as $test) {
					if ($test->toString() === t3lib_div::_GP('testname')) {
						$test->run($result);
					}
				}
			}
			if (!is_object($result)) {
				echo '<h2 class="hadError">Error</h2>' .
					'<p>The test <strong> ' .
					htmlspecialchars(t3lib_div::_GP('testCaseFile')) .
					'</strong> could not be found.</p>';
				return;
			}
		} elseif (t3lib_div::_GP('testCaseFile')) {
			$testListener->totalNumberOfTestCases = 1;
			$this->runTests_renderInfoAndProgressbar(1);
			foreach ($testSuite->tests() as $testCases) {
				foreach ($testCases->tests() as $test) {
					if (get_class($test) === t3lib_div::_GP('testCaseFile')) {
						$test->run($result);
					}
				}
			}
			if (!is_object($result)) {
				echo '<h2 class="hadError">Error</h2>' .
					'<p>The test <strong> ' .
					htmlspecialchars(t3lib_div::_GP('testname')) .
					'</strong> could not be found.</p>';
				return;
			}
		} else {
			$testListener->totalNumberOfTestCases = $testSuite->count();
			$this->runTests_renderInfoAndProgressbar($testListener->totalNumberOfTestCases);
			$testSuite->run($result);
		}

		$timeSpent = microtime(true) - $startTime;
		$leakedMemory = memory_get_usage() - $startMemory;

		// Display test statistics:
		$testStatistics = '';
		if ($result->wasSuccessful()) {
	    	$testStatistics = '
				<script type="text/javascript">setClass("progress-bar","wasSuccessful");</script>
				<h2 class="wasSuccessful">'.$this->getLL('testing_success').'</h2>';
		} else {
	    	$testStatistics = '
				<script type="text/javascript">setClass("progress-bar","hadFailure");</script>
				<h2 class="hadFailure">'.$this->getLL('testing_failure').'</h2>';
		}
		$testStatistics .= '<p>' . $result->count() . ' ' .	$this->getLL('tests_total') . ', ' .
			$testListener->assertionCount() . ' ' . $this->getLL('assertions_total') . ', ' .
			$result->failureCount() . ' ' . $this->getLL('tests_failures') .	', ' .
			$result->skippedCount() . ' ' . $this->getLL('tests_skipped') .	', ' .
			$result->notImplementedCount() . ' ' . $this->getLL('tests_notimplemented') .	', ' .
			$result->errorCount() . ' ' . $this->getLL('tests_errors') . ', ' .
			'<span title="'.$timeSpent . '&nbsp;' . $this->getLL('tests_seconds').'">'.round($timeSpent, 3) . '&nbsp;' . $this->getLL('tests_seconds').', </span>' .
			t3lib_div::formatSize($leakedMemory) . 'B (' . $leakedMemory .' B) ' . $this->getLL('tests_leaks') .
			'</p>';
		echo $testStatistics;

		echo '
			<form action="'.htmlspecialchars($this->MCONF['_']).'" method="post" >
				<p>
					<button type="submit" name="bingo" value="run" accesskey="r">'.$this->getLL('run_again').'</button>
					<input name="command" type="hidden" value="'.t3lib_div::_GP('command').'" />
					<input name="testname" type="hidden" value="'.t3lib_div::_GP('testname').'" />
					<input name="testCaseFile" type="hidden" value="'.t3lib_div::_GP('testCaseFile').'" />
				</p>
			</form>
		';

		// Code coverage output.
		if (!t3lib_div::_GP('testname') && $result->getCollectCodeCoverageInformation()) {
			$jsonCodeCoverage = json_encode($result->getCodeCoverageInformation());
		    PHPUnit_Util_Report::render($result, t3lib_extMgm::extPath('phpunit').'codecoverage/');
		    echo '<p><a target="_blank" href="'.$this->extensionPath.'codecoverage/typo3conf_ext.html">Click here to access the Code Coverage report</a></p>';
		    echo '<p>Memory peak usage: '.t3lib_div::formatSize(memory_get_peak_usage()).'B<p/>';

		    /* TODO: Add metrics UI presentation
		    $logMetricsWriter = new PHPUnit_Util_Log_Metrics();
			$logMetricsWriter->process($testResult);
			*/

		    /* TODO: Add Project Mess Detector (PMD) statistics
        	$logPmdWriter = new PHPUnit_Util_Log_PMD();
        	$logPmdWriter->process($testResult);
			*/

		    /* TODO: Add Code Duplication Detection (CPD) statistics.
        	$logCpdWriter = new PHPUnit_Util_Log_CPD();
        	$logCpdWriter->process($testResult);
			*/
		}
	}

	/**
	 * Renders DIVs which contain information and a progressbar to visualize
	 * the running tests. The actual information will be written via JS during
	 * the test runs.
	 *
	 * @return	void
	 * @access	protected
	 */
	protected function runTests_renderInfoAndProgressbar($tests = 1) {
		echo '
			<div class="progress-bar-wrap">
				<span id="progress-bar">&nbsp;</span>
				<span id="transparent-bar">&nbsp;</span>
			</div>
		';

		/*
		echo '<div style="width : 100%;">';
		$width = 100 / $tests;
		for ($i = 0; $i < $tests; $i++) {
			echo '<div style="float: left; width : '.$width.'%; height:20px;"><a href="#testCaseNum-'.$i.'" id="tx_phpunit_testcase_nr_'.$i.'" class="tx_phpunit_testcase_progressbox" title="'.$i.'"></a></div>';
		}
		echo '</div>';
		*/
	}

	/**
	 * Roughly simulates the front end although being in the back end.
	 *
	 * @todo	This is a quick hack, needs proper implementation.
	 */
	protected function simulateFrontendEnviroment() {
		require_once(PATH_t3lib . 'class.t3lib_timetrack.php');
		require_once(PATH_tslib . 'class.tslib_fe.php');
		require_once(PATH_t3lib . 'class.t3lib_page.php');
		require_once(PATH_t3lib . 'class.t3lib_userauth.php');
		require_once(PATH_tslib . 'class.tslib_feuserauth.php');
		require_once(PATH_t3lib . 'class.t3lib_tstemplate.php');
		require_once(PATH_t3lib . 'class.t3lib_cs.php');

		if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
			// avoids some memory leaks
			unset(
				$GLOBALS['TSFE']->tmpl, $GLOBALS['TSFE']->sys_page,
				$GLOBALS['TSFE']->fe_user, $GLOBALS['TSFE']->TYPO3_CONF_VARS,
				$GLOBALS['TSFE']->config, $GLOBALS['TSFE']->TCAcachedExtras,
				$GLOBALS['TSFE']->imagesOnPage, $GLOBALS['TSFE']->cObj,
				$GLOBALS['TSFE']->csConvObj, $GLOBALS['TSFE']->pagesection_lockObj,
				$GLOBALS['TSFE']->pages_lockObj
			);
			$GLOBALS['TSFE'] = null;
			$GLOBALS['TT'] = null;
		}

		$GLOBALS['TT'] = t3lib_div::makeInstance('t3lib_timeTrack');

		$className = t3lib_div::makeInstanceClassName('tslib_fe');
		$frontEnd = new $className(
			$GLOBALS['TYPO3_CONF_VARS'], $pageUid, 0
		);

		// simulates a normal FE without any logged-in FE or BE user
		$frontEnd->beUserLogin = false;
		$frontEnd->workspacePreview = '';
		// $frontEnd->getConfigArray() doesn't work here because the dummy FE
		// is not required to have a template.
		$frontEnd->config = array();

		$GLOBALS['TSFE'] = $frontEnd;
	}

	/**
	 * Renders the "About" screen
	 *
	 * @return	void
	 * @access	protected
	 */
	protected function about_render() {
		if ($codeCoverageDir['exists'] = file_exists($this->extensionPath.'codecoverage')) {
			$codeCoverageDir['writeable'] = is_writeable($this->extensionPath.'codecoverage');
			$codeCoverageDir['readable'] = is_readable($this->extensionPath.'codecoverage');
		}
		echo '<img src="'.$this->extensionPath.'mod1/phpunit.gif" width="94" height="80" alt="PHPUnit" title="PHPUnit" style="float:right; margin-left:10px;" />';
		$excludeExtensions = t3lib_div::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['excludeextensions']);
		echo self::eAccelerator0951OptimizerHelp();
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['usepear'] && !t3lib_extMgm::isLoaded('pear')) {
			echo '<h2>Extension pear is not loaded</h2>
			<p>The option for phpunit to use pear is set in the extension manager, but the pear extension is not loaded.</p>
			<p>As a fall back phpunit uses the PHPUnit that is provided with it.</p>
			<p>If you wish to use a pear provided PHPUnit, then load/install pear from the extension manager and fetch PHPUnit with the pear manager.</p>
			';
		}
		echo '
		<h1>About PHPUnit Backend Module</h1>
		<p>PHPUnit BE is a <a href="http://en.wikipedia.org/wiki/Unit_testing">unit testing</a> framework based on <a href="http://www.phpunit.de/" target="_new">PHPUnit</a> by Sebastian Bergmann. It offers smooth integration
		of the PHPUnit framework into TYPO3 and offers an API and many functions which make unit testing of TYPO3 extensions easy and comfortable.</p>
		<h2>Get test-infected!</h2>
		<p>If you think writing tests are dull, then try it. <a href="http://junit.sourceforge.net/doc/testinfected/testing.htm">You might become test-infected</a>!</p>
		<h2>Current include path</h2>
		<p>Below are the paths of the includepath that phpunit currently uses to locate PHPUnit:</p>
		<pre>'.join(chr(10), explode(PATH_SEPARATOR, get_include_path())).'</pre>
		<h2>Currently excluded extension</h2>
		<p>The following extensions are excluded from being searched for tests:</p>
		<pre>'.join(chr(10), $excludeExtensions).'</pre>
		<p>Note: The extension exclusion list can be changed in the extension manager.</p>
		<h2>Is XDebug PHP extension loaded?</h2>
		<p>To get code coverage reporting, PHPUnit needs the PHP extension <a target="_blank" href="http://www.xdebug.org"><em>XDebug</em></a>.</p>
		<p>On this PHP installation, XDebug is '.(extension_loaded('xdebug') ? '' : '<em>not</em>').' loaded.</p>
		<h2>Current memory limit</h2>
		<p>When using XDebug to collect code coverage data, you will need the memory limit to be set rather high. Something like 256MB will probably be needed.</p>
		<p>On this PHP installation the memory limit is currently set to: '.ini_get('memory_limit').'</p>
		<h2>This extension has bugs...</h2>
		<P><a target="_blank" href="http://forge.typo3.org/projects/extension-phpunit/issues">Issues can be seen and posted by clicking this link, http://forge.typo3.org/projects/extension-phpunit/issues</a>.</p>
		<p>You can report an issue by following the above link. An issue can be e.g. a bug or an improvement/enhancement.</p>
		<p>Older issues: <a target="_blank" href="http://bugs.typo3.org/search.php?project_id=79&amp;sticky_issues=on&amp;sortby=last_updated&amp;dir=DESC&amp;hide_status_id=90">Click to see the list of (preferably older) issues for this extension</a>.</p>
		<h2>Browse code in Subversion repository</h2>
		<p><a target="_blank" href="http://forge.typo3.org/repositories/show/extension-phpunit">The code repository for the phpunit extension can be browsed here, http://forge.typo3.org/repositories/show/extension-phpunit</a>.</p>
		<p>Previously, <a target="_blank" href="http://typo3xdev.svn.sourceforge.net/viewvc/typo3xdev/tx_phpunit/">the code was hosted at sourceforge</a>.</p>
		<h2>Licence and copyright</h2>
		<p>PHPUnit is released under the terms of the PHP License as free software.</p>
		<p>PHPUnit Copyright &copy; 2001&#8211;2009 Sebastian Bergmann</p>
		<p>PHPUnit BE is released under the GPL Licence and is part of the TYPO3 Framework.</p>
		<p>PHPUnit BE Copyright &copy; 2005&#8211;2009 <a href="mailto:kasperligaard@gmail.com">Kasper Ligaard</a></p>
		<h2>Contributors</h2>
		<p>The following people have contributed by testing, bugfixing, suggesting new features etc.</p>
		<p>Robert Lemke, Mario Rimann, Oliver Klee, SÃ¸ren Soltveit and Mikkel Ricky.</p>
		';
	}

	private function news_render() {
		echo '<img src="'.$this->extensionPath.'mod1/phpunit.gif" width="94" height="80" alt="PHPUnit" title="PHPUnit" style="float:right; margin-left:10px;" />';
		echo '<h1>News & Changes from one version to another</h1>';
		echo '<p>Below you see the NEWS file for this extension. It lists notable changes from one version to the next.</p>';
		echo '<p>If you experience problems after an upgrade, then check this list for changes that has happended since your previously installed version.</p>';
		echo '<h2>NEWS file</h2>';
		echo '<div class="tx_phpunit-newsfile">';
		$newsfile = file_get_contents(t3lib_extMgm::extPath(self::EXTENSION_KEY).'NEWS');
		echo nl2br($newsfile);
		echo '</div>';
	}


	/*********************************************************
	 *
	 * 	Helper functions
	 *
	 *********************************************************/

	/**
	 * Renders a link which opens the current screen in a new window
	 *
	 * @return	string
	 * @access	protected
	 */
	protected function openNewWindowLink() {
		global $BACK_PATH;

		// FIXME: Needs to take mod.php into account, when generating URL here. Otherwise 'Open link in new window' will not work (gives error: Value "" for "M" was not found as a module).
		$url = t3lib_div::getIndpEnv('TYPO3_REQUEST_SCRIPT').'?M=tools_txphpunitbeM1';
		$onClick = "phpunitbeWin=window.open('".$url."','phpunitbe','width=790,status=0,menubar=1,resizable=1,location=0,scrollbars=1,toolbar=0');phpunitbeWin.focus();return false;";
		$content = '
			<a id="opennewwindow" href="" onclick="'.htmlspecialchars($onClick).'" accesskey="n">
				<img'.t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/open_in_new_window.gif', 'width="19" height="14"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.openInNewWindow', 1).'" class="absmiddle" alt="" />
				Ope<span class="access-key">n</span> in separate window.
			</a>
			<script type="text/javascript">if (window.name == "phpunitbe") { document.getElementById("opennewwindow").style.display = "none"; }</script>
		';

		return $content;
	}

	/**
	 * Scans all available extensions for test suites and returns the path / file names in an array
	 *
	 * @return	array		Array of testcase files
	 */
	protected function getExtensionsWithTestSuites() {
		// Fetch extension manager configuration options
		$excludeExtensions = t3lib_div::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['excludeextensions']);
		$outOfLineTestCases = $this->traversePathForTestCases($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['phpunit']['outoflinetestspath']);

		// Get list of loaded extensions
		$extList = explode(',', $GLOBALS['TYPO3_CONF_VARS']['EXT']['extList']);

		$extensionsOwnTestCases = array();
		foreach ($extList as $extKey) {
			$testCasesArr = $this->findTestCasesInDir(
				t3lib_extMgm::extPath($extKey) . 'tests/'
			);
			if (!empty($testCasesArr)) {
				$extensionsOwnTestCases[$extKey] = $testCasesArr;
			}
		}

		$coreTestCases = array();
		$coreTestsDirectory = PATH_site . 'typo3_src/tests/';
		if (@is_dir($coreTestsDirectory)) {
			$coreTestCases['typo3'] = $this->findTestCasesInDir(
				$coreTestsDirectory
			);
		}

		$totalTestsArr = array_merge_recursive(
			$outOfLineTestCases, $extensionsOwnTestCases, $coreTestCases
		);

		// Exclude extensions according to extension manager config
		$returnTestsArr = array_diff_key($totalTestsArr, array_flip($excludeExtensions));
		return $returnTestsArr;
	}

	/**
	 * Traverses a given path recursively for *testcase.php files
	 *
	 * @param	string		$path: The path to descent from
	 * @return	array		Array of paths / filenames
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
					$testsPath = $path . $dirName . 'tests/';
					$extensionsArr[$dirName] = $this->findTestCasesInDir($testsPath);
				}
			}
		}

		return $extensionsArr;
	}

	/**
	 * Recursively finds all test case files in the directory $dir.
	 *
	 * @param string the absolute path of the directory in which to look for
	 *               test cases
	 * @return array files names of the test cases in the directory $dir and all
	 *               its subdirectories relative to $dir, will be empty if no
	 *               test cases have been found
	 */
	private function findTestCasesInDir($dir) {
		if (!is_dir($dir)) {
			return array();
		}

		$pathLength = strlen($dir);
		$fileNames = t3lib_div::getAllFilesAndFoldersInPath(
			array(), $dir, 'php'
		);

		$testCaseFileNames = array ();
		foreach ($fileNames as $fileName) {
			if (substr($fileName, -12) == 'testcase.php') {
				$testCaseFileNames[] = substr($fileName, $pathLength);;
			}
		}

		$extensionsArr = array();
		if (!empty($testCaseFileNames)) {
			sort($testCaseFileNames);
			$extensionsArr[$dir] = $testCaseFileNames;
		}

		return $extensionsArr;
	}

	private static function eAccelerator0951OptimizerHelp () {
		$retval = '';
		if (extension_loaded('eaccelerator') && version_compare(phpversion('eaccelerator'), '0.9.5.2', '<')) {
			$retval .= '<h2>IMPORTANT NOTICE ABOUT eAccelerator!</h2>';
			$retval .= '<p>eAccelerator '.phpversion('eaccelerator').' is loaded. This version of eAccelerator is known to crash phpunit when the optimizer is turned on.</p>';
			$retval .= '<p>You should either upgrade to eAccelerator version 0.9.5.2 (or later) or turn off the eAccelerator optimizer (in php.ini set: <code>eaccelerator.optimizer = &quot;0&quot;</code>) when running phpunit.</p>';
			$vars = @ini_get_all('eaccelerator');
			$retval .= '<p>Current local value of <code>eaccelerator.optimizer</code>: '.$vars['eaccelerator.optimizer']['local_value'].'</p>';
			$retval .= '<p>Current global value of <code>eaccelerator.optimizer</code>: '.$vars['eaccelerator.optimizer']['global_value'].'</p>';
			$retval .= '<p>Confer <a href="http://eaccelerator.net/ticket/242">eAccelerator ticket 242 for more info</a></p>';
		}
		return $retval;
	}

	private static function loadRequiredTestClasses ($paths) {
		if (isset($paths)) {
			foreach ($paths as $path => $fileNames) {
				foreach ($fileNames as $fileName) {
					require_once (realpath($path.'/'.$fileName));
				}
			}
		}
	}

	/**
	 * Checks whether a extension is valid and the extension is loaded.
	 *
	 * "typo3" is considered as a valid extension key for this purpose, too.
	 *
	 * @param string the key of the extension to test, may be empty
	 *
	 * @return boolean true if an extension with the key $extensionKey is
	 *                 loaded, false otherwise
	 */
	private function isExtensionLoaded($extensionKey) {
		if ($extensionKey == '') {
			return false;
		}

		return ($extensionKey == 'typo3')
			|| t3lib_extMgm::isLoaded($extensionKey);
	}

	/**
	 * Creates the CSS style attribute content for an icon for the extension
	 * $extensionKey.
	 *
	 * @param string the key of a loaded extension, may also be "typo3"
	 *
	 * @return string the content for the "style" attribute, will not be empty
	 */
	private function createIconStyle($extensionKey) {
		if (!$this->isExtensionLoaded($extensionKey)) {
			throw new Exception(
				'The extension ' . $extensionKey . ' is not loaded.'
			);
		}

		$result = 'background: white no-repeat ';

		if ($extensionKey == 'typo3') {
			$result .= 'url(gfx/typo3logo_mini.png) -41px 50%;';
		} else {
			$result .= 'url(' . t3lib_extMgm::extRelPath($extensionKey) .
				'ext_icon.gif) 3px 50%;';
		}

		$result .= ' padding: 1px 1px 1px 24px;';

		return $result;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/phpunit/mod1/class.tx_phpunit_module1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/phpunit/mod1/class.tx_phpunit_module1.php']);
}
?>