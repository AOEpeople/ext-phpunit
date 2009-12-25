<?php

/*
 * This class uses the new ajax broker in Typo3 4.2. Thus a minimum requirement
 * of Typo3 4.2 (and hence PHP 5.2.x) is required.
 *
 * For more on the AJAX classes, and how the interact, see http://bugs.typo3.org/view.php?id=7096
 *
 * @author Kasper Ligaard <kasperligaard@gmail.com>
 */

class tx_phpunit_module1_ajax {
	/**
	 * Used to broker incoming requests to other calls.
	 * Called by typo3/ajax.php
	 *
	 * @param	array		$params: additional parameters (not used)
	 * @param	TYPO3AJAX	&$ajaxObj: reference of the TYPO3AJAX object of this request
	 * @return	void
	 */
	public function ajaxBroker($params, &$ajaxObj) {
		// Check for legal input ('white-listing').
		$state = t3lib_div::_POST('state') === 'true' ? 'on' : 'off';
		$checkbox = t3lib_div::_POST('checkbox');
		switch ($checkbox) {
			case 'failure':
			case 'success':
			case 'error':
			case 'skipped':
			case 'notimplemented':
			case 'testdox':
			case 'codeCoverage':
			case 'showMemoryAndTime':
				break;
			default:
				$checkbox = false;
		}

		if ($checkbox) {
			$ajaxObj->setContentFormat('json');
			$GLOBALS['BE_USER']->uc['moduleData']['tools_txphpunitM1'][$checkbox] = $state;
			$GLOBALS['BE_USER']->writeUC();
			$ajaxObj->addContent('success', true);
		} else {
			$ajaxObj->setContentFormat('plain');
			$ajaxObj->setError('Illegal input parameters.');
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/phpunit/mod1/class.tx_phpunit_module1_ajax.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/phpunit/mod1/class.tx_phpunit_module1_ajax.php']);
}
?>