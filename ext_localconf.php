<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY] = unserialize($_EXTCONF);

if (is_dir($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['phpunitlib'])) {
	$phpunitlib = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['phpunitlib'] . PATH_SEPARATOR;
} else {
	$phpunitlib = t3lib_extMgm::extPath('phpunit') . 'PEAR/';
}

define(TX_PHPUNITLIB_EXTPATH, $phpunitlib);
set_include_path(TX_PHPUNITLIB_EXTPATH . PATH_SEPARATOR . get_include_path());

$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['tx_phpunit_module1_ajax'] = 'typo3conf/ext/phpunit/mod1/class.tx_phpunit_module1_ajax.php:tx_phpunit_module1_ajax->ajaxBroker';

if (TYPO3_MODE === 'BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys'][$_EXTKEY] = array(
		'EXT:' . $_EXTKEY . '/Classes/Cli/TestRunner.php',
		'_CLI_phpunit'
	);
}
?>