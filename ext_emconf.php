<?php

########################################################################
# Extension Manager/Repository config file for ext: "phpunit"
#
# Auto generated 04-04-2008 14:06
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'PHPUnit',
	'description' => 'TYPO3 unit testing UI based on PHPUnit ver. 3.2 by Sebastian Bergmann. Part of the ECT effort (Extension Coordination Team).',
	'category' => 'module',
	'shy' => 0,
	'version' => '3.2.13',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Kasper Ligaard',
	'author_email' => 'ligaard@systime.dk',
	'author_company' => 'GT3',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.1-0.0.0',
			'typo3' => '4.1-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'pear' => '2.3.5-0.0.0',
		),
	),
	'_md5_values_when_last_written' => 'a:404:{s:9:"Changelog";s:4:"d41d";s:4:"NEWS";s:4:"5107";s:4:"TODO";s:4:"c984";s:38:"class.tx_phpunit_database_testcase.php";s:4:"a7a7";s:29:"class.tx_phpunit_testcase.php";s:4:"14c3";s:33:"class.tx_phpunit_testlistener.php";s:4:"ab3c";s:21:"ext_conf_template.txt";s:4:"5bdc";s:12:"ext_icon.gif";s:4:"5f50";s:17:"ext_localconf.php";s:4:"63c9";s:14:"ext_tables.php";s:4:"2fe8";s:14:"doc/manual.sxw";s:4:"0159";s:27:"tests/database_testcase.php";s:4:"bf3e";s:35:"tests/database_testcase_dataset.xml";s:4:"0651";s:34:"tests/tx_phpunit_test_testcase.php";s:4:"92e4";s:30:"tests/tx_phpunit_testsuite.php";s:4:"94a7";s:33:"tests/tx_t3unit_test_testcase.php";s:4:"645e";s:23:"tests/res/ccc/ChangeLog";s:4:"6901";s:24:"tests/res/ccc/README.txt";s:4:"ee2d";s:28:"tests/res/ccc/ext_emconf.php";s:4:"1dc9";s:26:"tests/res/ccc/ext_icon.gif";s:4:"1bdc";s:28:"tests/res/ccc/ext_tables.php";s:4:"7b15";s:28:"tests/res/ccc/ext_tables.sql";s:4:"ed29";s:34:"tests/res/ccc/icon_tx_ccc_data.gif";s:4:"475a";s:34:"tests/res/ccc/icon_tx_ccc_test.gif";s:4:"475a";s:30:"tests/res/ccc/locallang_db.xml";s:4:"4f69";s:21:"tests/res/ccc/tca.php";s:4:"eb30";s:33:"tests/res/ccc/doc/wizard_form.dat";s:4:"5b6a";s:34:"tests/res/ccc/doc/wizard_form.html";s:4:"79f5";s:23:"tests/res/aaa/ChangeLog";s:4:"661e";s:24:"tests/res/aaa/README.txt";s:4:"ee2d";s:28:"tests/res/aaa/ext_emconf.php";s:4:"c526";s:26:"tests/res/aaa/ext_icon.gif";s:4:"1bdc";s:28:"tests/res/aaa/ext_tables.php";s:4:"6a79";s:28:"tests/res/aaa/ext_tables.sql";s:4:"1764";s:34:"tests/res/aaa/icon_tx_aaa_test.gif";s:4:"475a";s:30:"tests/res/aaa/locallang_db.xml";s:4:"9d47";s:21:"tests/res/aaa/tca.php";s:4:"ddf9";s:33:"tests/res/aaa/doc/wizard_form.dat";s:4:"1855";s:34:"tests/res/aaa/doc/wizard_form.html";s:4:"a38d";s:23:"tests/res/bbb/ChangeLog";s:4:"1da2";s:24:"tests/res/bbb/README.txt";s:4:"ee2d";s:28:"tests/res/bbb/ext_emconf.php";s:4:"00bd";s:26:"tests/res/bbb/ext_icon.gif";s:4:"1bdc";s:28:"tests/res/bbb/ext_tables.php";s:4:"84d0";s:28:"tests/res/bbb/ext_tables.sql";s:4:"717c";s:34:"tests/res/bbb/icon_tx_bbb_test.gif";s:4:"475a";s:30:"tests/res/bbb/locallang_db.xml";s:4:"7f20";s:21:"tests/res/bbb/tca.php";s:4:"f9d2";s:33:"tests/res/bbb/doc/wizard_form.dat";s:4:"12fb";s:34:"tests/res/bbb/doc/wizard_form.html";s:4:"37bf";s:23:"tests/res/ddd/ChangeLog";s:4:"661e";s:24:"tests/res/ddd/README.txt";s:4:"ee2d";s:28:"tests/res/ddd/ext_emconf.php";s:4:"e6a7";s:26:"tests/res/ddd/ext_icon.gif";s:4:"1bdc";s:28:"tests/res/ddd/ext_tables.php";s:4:"eed5";s:28:"tests/res/ddd/ext_tables.sql";s:4:"df94";s:34:"tests/res/ddd/icon_tx_ddd_test.gif";s:4:"475a";s:30:"tests/res/ddd/locallang_db.xml";s:4:"0b54";s:21:"tests/res/ddd/tca.php";s:4:"7e17";s:33:"tests/res/ddd/doc/wizard_form.dat";s:4:"4baa";s:34:"tests/res/ddd/doc/wizard_form.html";s:4:"7033";s:33:"mod1/class.tx_phpunit_module1.php";s:4:"e289";s:38:"mod1/class.tx_phpunit_module1_ajax.php";s:4:"c772";s:45:"mod1/class.tx_phpunit_module1_mikkelricky.php";s:4:"b3bb";s:13:"mod1/conf.php";s:4:"a873";s:14:"mod1/index.php";s:4:"dec4";s:18:"mod1/locallang.xml";s:4:"bca5";s:22:"mod1/locallang_mod.php";s:4:"4d5c";s:19:"mod1/moduleicon.gif";s:4:"9d0b";s:19:"mod1/phpunit-be.css";s:4:"7ed4";s:16:"mod1/phpunit.gif";s:4:"ea4a";s:15:"mod1/runner.gif";s:4:"9644";s:15:"mod1/styles.css";s:4:"6ad0";s:26:"mod1/tx_phpunit_module1.js";s:4:"7013";s:36:"PHPUnit-3.2.17/PHPUnit/Framework.php";s:4:"b119";s:46:"PHPUnit-3.2.17/PHPUnit/Samples/FailureTest.php";s:4:"1956";s:60:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccountDB/BankAccount.php";s:4:"1725";s:66:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccountDB/BankAccountDBTest.php";s:4:"16e7";s:71:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccountDB/BankAccountDBTestMySQL.php";s:4:"fe46";s:83:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccountDB/_files/bank-account-after-deposits.xml";s:4:"c0c4";s:86:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccountDB/_files/bank-account-after-new-account.xml";s:4:"2f81";s:86:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccountDB/_files/bank-account-after-withdrawals.xml";s:4:"b8fd";s:73:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccountDB/_files/bank-account-seed.xml";s:4:"344f";s:58:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccount/BankAccount.php";s:4:"3e11";s:62:"PHPUnit-3.2.17/PHPUnit/Samples/BankAccount/BankAccountTest.php";s:4:"df27";s:47:"PHPUnit-3.2.17/PHPUnit/Samples/Money/IMoney.php";s:4:"3230";s:46:"PHPUnit-3.2.17/PHPUnit/Samples/Money/Money.php";s:4:"599c";s:49:"PHPUnit-3.2.17/PHPUnit/Samples/Money/MoneyBag.php";s:4:"e765";s:50:"PHPUnit-3.2.17/PHPUnit/Samples/Money/MoneyTest.php";s:4:"a1ef";s:48:"PHPUnit-3.2.17/PHPUnit/Runner/BaseTestRunner.php";s:4:"b6c5";s:58:"PHPUnit-3.2.17/PHPUnit/Runner/IncludePathTestCollector.php";s:4:"61ed";s:57:"PHPUnit-3.2.17/PHPUnit/Runner/StandardTestSuiteLoader.php";s:4:"5805";s:47:"PHPUnit-3.2.17/PHPUnit/Runner/TestCollector.php";s:4:"771f";s:49:"PHPUnit-3.2.17/PHPUnit/Runner/TestSuiteLoader.php";s:4:"7984";s:41:"PHPUnit-3.2.17/PHPUnit/Runner/Version.php";s:4:"1c76";s:37:"PHPUnit-3.2.17/PHPUnit/Util/Class.php";s:4:"d518";s:44:"PHPUnit-3.2.17/PHPUnit/Util/CodeCoverage.php";s:4:"f59f";s:45:"PHPUnit-3.2.17/PHPUnit/Util/Configuration.php";s:4:"fb62";s:44:"PHPUnit-3.2.17/PHPUnit/Util/ErrorHandler.php";s:4:"90d9";s:42:"PHPUnit-3.2.17/PHPUnit/Util/Fileloader.php";s:4:"8751";s:42:"PHPUnit-3.2.17/PHPUnit/Util/Filesystem.php";s:4:"9095";s:38:"PHPUnit-3.2.17/PHPUnit/Util/Filter.php";s:4:"ae79";s:46:"PHPUnit-3.2.17/PHPUnit/Util/FilterIterator.php";s:4:"ee8b";s:38:"PHPUnit-3.2.17/PHPUnit/Util/Getopt.php";s:4:"59da";s:39:"PHPUnit-3.2.17/PHPUnit/Util/Metrics.php";s:4:"651f";s:35:"PHPUnit-3.2.17/PHPUnit/Util/PDO.php";s:4:"bfde";s:39:"PHPUnit-3.2.17/PHPUnit/Util/Printer.php";s:4:"b8d6";s:38:"PHPUnit-3.2.17/PHPUnit/Util/Report.php";s:4:"ed4f";s:40:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton.php";s:4:"5f21";s:40:"PHPUnit-3.2.17/PHPUnit/Util/Template.php";s:4:"5865";s:36:"PHPUnit-3.2.17/PHPUnit/Util/Test.php";s:4:"0740";s:49:"PHPUnit-3.2.17/PHPUnit/Util/TestSuiteIterator.php";s:4:"973b";s:37:"PHPUnit-3.2.17/PHPUnit/Util/Timer.php";s:4:"6e8a";s:36:"PHPUnit-3.2.17/PHPUnit/Util/Type.php";s:4:"364f";s:35:"PHPUnit-3.2.17/PHPUnit/Util/XML.php";s:4:"f1cf";s:43:"PHPUnit-3.2.17/PHPUnit/Util/Report/Node.php";s:4:"ea73";s:53:"PHPUnit-3.2.17/PHPUnit/Util/Report/Node/Directory.php";s:4:"ecca";s:48:"PHPUnit-3.2.17/PHPUnit/Util/Report/Node/File.php";s:4:"629c";s:54:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/butter.png";s:4:"521e";s:57:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/chameleon.png";s:4:"24ab";s:57:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/close12_1.gif";s:4:"770d";s:60:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/container-min.js";s:4:"322d";s:57:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/container.css";s:4:"9f41";s:58:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/directory.html";s:4:"1f29";s:53:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/file.html";s:4:"75cd";s:60:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/file_no_yui.html";s:4:"0396";s:53:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/glass.png";s:4:"d0bc";s:53:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/item.html";s:4:"e9f0";s:60:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/method_item.html";s:4:"02b8";s:59:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/scarlet_red.png";s:4:"e7e9";s:52:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/snow.png";s:4:"3d0f";s:53:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/style.css";s:4:"8af7";s:62:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/yahoo-dom-event.js";s:4:"02f0";s:55:"PHPUnit-3.2.17/PHPUnit/Util/Report/Template/yui_item.js";s:4:"32c5";s:39:"PHPUnit-3.2.17/PHPUnit/Util/Log/CPD.php";s:4:"53cc";s:44:"PHPUnit-3.2.17/PHPUnit/Util/Log/Database.php";s:4:"8fd5";s:44:"PHPUnit-3.2.17/PHPUnit/Util/Log/GraphViz.php";s:4:"83b6";s:40:"PHPUnit-3.2.17/PHPUnit/Util/Log/JSON.php";s:4:"7c41";s:43:"PHPUnit-3.2.17/PHPUnit/Util/Log/Metrics.php";s:4:"29e5";s:40:"PHPUnit-3.2.17/PHPUnit/Util/Log/PEAR.php";s:4:"72e6";s:39:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD.php";s:4:"9773";s:39:"PHPUnit-3.2.17/PHPUnit/Util/Log/TAP.php";s:4:"1080";s:39:"PHPUnit-3.2.17/PHPUnit/Util/Log/XML.php";s:4:"18fd";s:50:"PHPUnit-3.2.17/PHPUnit/Util/Log/Database/MySQL.sql";s:4:"5dac";s:52:"PHPUnit-3.2.17/PHPUnit/Util/Log/Database/SQLite3.sql";s:4:"d9b0";s:44:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule.php";s:4:"8a00";s:50:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Class.php";s:4:"6bab";s:49:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/File.php";s:4:"43ee";s:53:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Function.php";s:4:"4785";s:52:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Project.php";s:4:"3f30";s:73:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Class/DepthOfInheritanceTree.php";s:4:"fa5d";s:67:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Class/EfferentCoupling.php";s:4:"e761";s:71:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Class/ExcessiveClassLength.php";s:4:"fa83";s:71:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Class/ExcessivePublicCount.php";s:4:"ccc8";s:64:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Class/TooManyFields.php";s:4:"9801";s:58:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Function/CRAP.php";s:4:"0bc7";s:66:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Function/CodeCoverage.php";s:4:"cc98";s:74:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Function/CyclomaticComplexity.php";s:4:"f9e0";s:75:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Function/ExcessiveMethodLength.php";s:4:"ace9";s:76:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Function/ExcessiveParameterList.php";s:4:"b50b";s:69:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Function/NPathComplexity.php";s:4:"d6f8";s:57:"PHPUnit-3.2.17/PHPUnit/Util/Log/PMD/Rule/Project/CRAP.php";s:4:"bf35";s:57:"PHPUnit-3.2.17/PHPUnit/Util/Log/CodeCoverage/Database.php";s:4:"4102";s:52:"PHPUnit-3.2.17/PHPUnit/Util/Log/CodeCoverage/XML.php";s:4:"c3a7";s:61:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton/IncompleteTestMethod.tpl";s:4:"f965";s:50:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton/TestClass.tpl";s:4:"8a33";s:51:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton/TestMethod.tpl";s:4:"6141";s:55:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton/TestMethodBool.tpl";s:4:"9a09";s:61:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton/TestMethodBoolStatic.tpl";s:4:"9fa0";s:60:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton/TestMethodException.tpl";s:4:"c08f";s:66:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton/TestMethodExceptionStatic.tpl";s:4:"c021";s:57:"PHPUnit-3.2.17/PHPUnit/Util/Skeleton/TestMethodStatic.tpl";s:4:"4b31";s:45:"PHPUnit-3.2.17/PHPUnit/Util/Metrics/Class.php";s:4:"1393";s:44:"PHPUnit-3.2.17/PHPUnit/Util/Metrics/File.php";s:4:"caf1";s:48:"PHPUnit-3.2.17/PHPUnit/Util/Metrics/Function.php";s:4:"e951";s:47:"PHPUnit-3.2.17/PHPUnit/Util/Metrics/Project.php";s:4:"e862";s:54:"PHPUnit-3.2.17/PHPUnit/Util/TestDox/NamePrettifier.php";s:4:"25c4";s:53:"PHPUnit-3.2.17/PHPUnit/Util/TestDox/ResultPrinter.php";s:4:"610b";s:58:"PHPUnit-3.2.17/PHPUnit/Util/TestDox/ResultPrinter/HTML.php";s:4:"2b7c";s:58:"PHPUnit-3.2.17/PHPUnit/Util/TestDox/ResultPrinter/Text.php";s:4:"f5a2";s:43:"PHPUnit-3.2.17/PHPUnit/Framework/Assert.php";s:4:"093f";s:57:"PHPUnit-3.2.17/PHPUnit/Framework/AssertionFailedError.php";s:4:"fcff";s:54:"PHPUnit-3.2.17/PHPUnit/Framework/ComparisonFailure.php";s:4:"6c87";s:47:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint.php";s:4:"3ba6";s:42:"PHPUnit-3.2.17/PHPUnit/Framework/Error.php";s:4:"d946";s:63:"PHPUnit-3.2.17/PHPUnit/Framework/ExpectationFailedException.php";s:4:"d0ad";s:51:"PHPUnit-3.2.17/PHPUnit/Framework/IncompleteTest.php";s:4:"0b69";s:56:"PHPUnit-3.2.17/PHPUnit/Framework/IncompleteTestError.php";s:4:"d75f";s:43:"PHPUnit-3.2.17/PHPUnit/Framework/Notice.php";s:4:"3434";s:51:"PHPUnit-3.2.17/PHPUnit/Framework/SelfDescribing.php";s:4:"ad8e";s:48:"PHPUnit-3.2.17/PHPUnit/Framework/SkippedTest.php";s:4:"d55c";s:53:"PHPUnit-3.2.17/PHPUnit/Framework/SkippedTestError.php";s:4:"7fc2";s:58:"PHPUnit-3.2.17/PHPUnit/Framework/SkippedTestSuiteError.php";s:4:"642b";s:41:"PHPUnit-3.2.17/PHPUnit/Framework/Test.php";s:4:"997f";s:45:"PHPUnit-3.2.17/PHPUnit/Framework/TestCase.php";s:4:"6b1d";s:48:"PHPUnit-3.2.17/PHPUnit/Framework/TestFailure.php";s:4:"b701";s:49:"PHPUnit-3.2.17/PHPUnit/Framework/TestListener.php";s:4:"6c00";s:47:"PHPUnit-3.2.17/PHPUnit/Framework/TestResult.php";s:4:"c648";s:46:"PHPUnit-3.2.17/PHPUnit/Framework/TestSuite.php";s:4:"7592";s:44:"PHPUnit-3.2.17/PHPUnit/Framework/Warning.php";s:4:"4ba0";s:60:"PHPUnit-3.2.17/PHPUnit/Framework/ComparisonFailure/Array.php";s:4:"2450";s:61:"PHPUnit-3.2.17/PHPUnit/Framework/ComparisonFailure/Object.php";s:4:"1393";s:61:"PHPUnit-3.2.17/PHPUnit/Framework/ComparisonFailure/Scalar.php";s:4:"a872";s:61:"PHPUnit-3.2.17/PHPUnit/Framework/ComparisonFailure/String.php";s:4:"4a9b";s:59:"PHPUnit-3.2.17/PHPUnit/Framework/ComparisonFailure/Type.php";s:4:"3fdf";s:58:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Invocation.php";s:4:"1a7d";s:64:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/InvocationMocker.php";s:4:"8bed";s:57:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Invokable.php";s:4:"ed06";s:55:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher.php";s:4:"9d3a";s:52:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Mock.php";s:4:"2f61";s:58:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/MockObject.php";s:4:"ada6";s:52:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Stub.php";s:4:"26c8";s:58:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Verifiable.php";s:4:"990f";s:71:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/AnyInvokedCount.php";s:4:"cffa";s:69:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/AnyParameters.php";s:4:"b1ff";s:66:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/Invocation.php";s:4:"7aa3";s:70:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/InvokedAtIndex.php";s:4:"8e36";s:74:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/InvokedAtLeastOnce.php";s:4:"d74d";s:68:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/InvokedCount.php";s:4:"aa68";s:71:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/InvokedRecorder.php";s:4:"e0bb";s:66:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/MethodName.php";s:4:"281c";s:66:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/Parameters.php";s:4:"5348";s:75:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Matcher/StatelessInvocation.php";s:4:"d854";s:64:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Builder/Identity.php";s:4:"df1d";s:72:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Builder/InvocationMocker.php";s:4:"564e";s:61:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Builder/Match.php";s:4:"34f6";s:71:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Builder/MethodNameMatch.php";s:4:"5a65";s:65:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Builder/Namespace.php";s:4:"4e5b";s:71:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Builder/ParametersMatch.php";s:4:"a5a0";s:60:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Builder/Stub.php";s:4:"93fb";s:69:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Stub/ConsecutiveCalls.php";s:4:"1b34";s:62:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Stub/Exception.php";s:4:"beee";s:70:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Stub/MatcherCollection.php";s:4:"0daf";s:59:"PHPUnit-3.2.17/PHPUnit/Framework/MockObject/Stub/Return.php";s:4:"3a4d";s:51:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/And.php";s:4:"0f07";s:59:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/ArrayHasKey.php";s:4:"7e26";s:57:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/Attribute.php";s:4:"4eaa";s:65:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/ClassHasAttribute.php";s:4:"95a1";s:71:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/ClassHasStaticAttribute.php";s:4:"5731";s:58:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/FileExists.php";s:4:"e907";s:59:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/GreaterThan.php";s:4:"aad8";s:58:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/IsAnything.php";s:4:"2f2c";s:55:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/IsEqual.php";s:4:"00d8";s:59:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/IsIdentical.php";s:4:"9d0d";s:60:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/IsInstanceOf.php";s:4:"342b";s:54:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/IsType.php";s:4:"e512";s:56:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/LessThan.php";s:4:"17cc";s:51:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/Not.php";s:4:"2cb3";s:66:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/ObjectHasAttribute.php";s:4:"a151";s:50:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/Or.php";s:4:"55bf";s:57:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/PCREMatch.php";s:4:"94d1";s:62:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/StringContains.php";s:4:"7488";s:67:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/TraversableContains.php";s:4:"2317";s:71:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/TraversableContainsOnly.php";s:4:"9b0b";s:51:"PHPUnit-3.2.17/PHPUnit/Framework/Constraint/Xor.php";s:4:"de2b";s:55:"PHPUnit-3.2.17/PHPUnit/Extensions/ExceptionTestCase.php";s:4:"17a2";s:52:"PHPUnit-3.2.17/PHPUnit/Extensions/OutputTestCase.php";s:4:"d071";s:57:"PHPUnit-3.2.17/PHPUnit/Extensions/PerformanceTestCase.php";s:4:"3e92";s:50:"PHPUnit-3.2.17/PHPUnit/Extensions/PhptTestCase.php";s:4:"79a7";s:51:"PHPUnit-3.2.17/PHPUnit/Extensions/PhptTestSuite.php";s:4:"b01a";s:50:"PHPUnit-3.2.17/PHPUnit/Extensions/RepeatedTest.php";s:4:"cd94";s:54:"PHPUnit-3.2.17/PHPUnit/Extensions/SeleniumTestCase.php";s:4:"bb40";s:51:"PHPUnit-3.2.17/PHPUnit/Extensions/TestDecorator.php";s:4:"98a3";s:47:"PHPUnit-3.2.17/PHPUnit/Extensions/TestSetup.php";s:4:"47b6";s:61:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/AbstractTester.php";s:4:"21ee";s:60:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DefaultTester.php";s:4:"5e66";s:54:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/ITester.php";s:4:"41b7";s:55:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/TestCase.php";s:4:"edc7";s:66:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Composite.php";s:4:"9705";s:63:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Delete.php";s:4:"812d";s:66:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/DeleteAll.php";s:4:"dc44";s:66:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Exception.php";s:4:"87c1";s:64:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Factory.php";s:4:"c009";s:75:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/IDatabaseOperation.php";s:4:"6742";s:63:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Insert.php";s:4:"cf7e";s:61:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Null.php";s:4:"429b";s:64:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Replace.php";s:4:"42c4";s:65:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/RowBased.php";s:4:"63b0";s:65:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Truncate.php";s:4:"2692";s:63:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Operation/Update.php";s:4:"87e2";s:70:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/AbstractDataSet.php";s:4:"e736";s:68:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/AbstractTable.php";s:4:"5a67";s:76:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/AbstractTableMetaData.php";s:4:"cb9f";s:73:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/AbstractXmlDataSet.php";s:4:"4e3f";s:68:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/DataSetFilter.php";s:4:"89f6";s:69:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/DefaultDataSet.php";s:4:"023a";s:67:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/DefaultTable.php";s:4:"c6e4";s:75:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/DefaultTableIterator.php";s:4:"e515";s:75:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/DefaultTableMetaData.php";s:4:"e905";s:69:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/FlatXmlDataSet.php";s:4:"9851";s:63:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/IDataSet.php";s:4:"02cc";s:61:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/ITable.php";s:4:"b691";s:69:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/ITableIterator.php";s:4:"0a26";s:69:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/ITableMetaData.php";s:4:"8e34";s:66:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/TableFilter.php";s:4:"4759";s:74:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/TableMetaDataFilter.php";s:4:"8df5";s:65:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DataSet/XmlDataSet.php";s:4:"47b3";s:57:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/DataSet.php";s:4:"12f5";s:75:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/DefaultDatabaseConnection.php";s:4:"35ad";s:65:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/FilteredDataSet.php";s:4:"d5be";s:69:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/IDatabaseConnection.php";s:4:"2da3";s:59:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/IMetaData.php";s:4:"3ad5";s:58:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/MetaData.php";s:4:"4fdd";s:64:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/ResultSetTable.php";s:4:"245d";s:55:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/Table.php";s:4:"2384";s:63:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/TableIterator.php";s:4:"4a4a";s:63:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/TableMetaData.php";s:4:"ef76";s:76:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/MetaData/InformationSchema.php";s:4:"ee29";s:64:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/MetaData/MySQL.php";s:4:"64db";s:62:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/MetaData/Oci.php";s:4:"c186";s:64:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/MetaData/PgSQL.php";s:4:"23c7";s:65:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/DB/MetaData/Sqlite.php";s:4:"34c6";s:72:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Constraint/DataSetIsEqual.php";s:4:"1b7f";s:70:"PHPUnit-3.2.17/PHPUnit/Extensions/Database/Constraint/TableIsEqual.php";s:4:"26c5";s:57:"PHPUnit-3.2.17/PHPUnit/Extensions/PhptTestCase/Logger.php";s:4:"4e35";s:61:"PHPUnit-3.2.17/PHPUnit/Extensions/SeleniumTestCase/append.php";s:4:"7f04";s:71:"PHPUnit-3.2.17/PHPUnit/Extensions/SeleniumTestCase/phpunit_coverage.php";s:4:"3d38";s:62:"PHPUnit-3.2.17/PHPUnit/Extensions/SeleniumTestCase/prepend.php";s:4:"fb42";s:41:"PHPUnit-3.2.17/PHPUnit/TextUI/Command.php";s:4:"fa0a";s:47:"PHPUnit-3.2.17/PHPUnit/TextUI/ResultPrinter.php";s:4:"17f8";s:44:"PHPUnit-3.2.17/PHPUnit/TextUI/TestRunner.php";s:4:"b95b";s:41:"PHPUnit-3.2.17/PHPUnit/Tests/AllTests.php";s:4:"0dbf";s:55:"PHPUnit-3.2.17/PHPUnit/Tests/TestConfiguration.php.dist";s:4:"54e9";s:48:"PHPUnit-3.2.17/PHPUnit/Tests/Runner/AllTests.php";s:4:"eac9";s:58:"PHPUnit-3.2.17/PHPUnit/Tests/Runner/BaseTestRunnerTest.php";s:4:"ede3";s:46:"PHPUnit-3.2.17/PHPUnit/Tests/Util/AllTests.php";s:4:"0f16";s:47:"PHPUnit-3.2.17/PHPUnit/Tests/Util/TimerTest.php";s:4:"4128";s:54:"PHPUnit-3.2.17/PHPUnit/Tests/Util/TestDox/AllTests.php";s:4:"080f";s:64:"PHPUnit-3.2.17/PHPUnit/Tests/Util/TestDox/NamePrettifierTest.php";s:4:"0cea";s:51:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/AllTests.php";s:4:"f196";s:53:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/AssertTest.php";s:4:"5d34";s:64:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/ComparisonFailureTest.php";s:4:"f579";s:57:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/ConstraintTest.php";s:4:"6290";s:57:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/MockObjectTest.php";s:4:"40a2";s:52:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/SuiteTest.php";s:4:"98a1";s:55:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/TestCaseTest.php";s:4:"75ff";s:62:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/TestImplementorTest.php";s:4:"7351";s:59:"PHPUnit-3.2.17/PHPUnit/Tests/Framework/TestListenerTest.php";s:4:"6da8";s:52:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/AllTests.php";s:4:"7b2e";s:62:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/OutputTestCaseTest.php";s:4:"fe20";s:67:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/PerformanceTestCaseTest.php";s:4:"cba7";s:60:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/RepeatedTestTest.php";s:4:"2a8e";s:64:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/SeleniumTestCaseTest.php";s:4:"9636";s:61:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/AllTests.php";s:4:"4d55";s:71:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/Operation/AllTests.php";s:4:"6729";s:77:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/Operation/OperationsTest.php";s:4:"989c";s:75:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/Operation/RowBasedTest.php";s:4:"1909";s:69:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/DataSet/AllTests.php";s:4:"d5df";s:71:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/DataSet/FilterTest.php";s:4:"dd16";s:76:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/DataSet/XmlDataSetsTest.php";s:4:"ffa6";s:94:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/DeleteAllOperationTest.xml";s:4:"fb1c";s:93:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/DeleteOperationResult.xml";s:4:"d076";s:91:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/DeleteOperationTest.xml";s:4:"e1a9";s:94:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/FilteredTestComparison.xml";s:4:"1f27";s:91:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/FilteredTestFixture.xml";s:4:"184a";s:86:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/FlatXmlDataSet.xml";s:4:"2b8f";s:93:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/InsertOperationResult.xml";s:4:"8156";s:91:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/InsertOperationTest.xml";s:4:"89c5";s:93:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/OperationsTestFixture.xml";s:4:"1f27";s:94:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/ReplaceOperationResult.xml";s:4:"845c";s:92:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/ReplaceOperationTest.xml";s:4:"68cb";s:87:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/RowBasedExecute.xml";s:4:"1b80";s:93:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/UpdateOperationResult.xml";s:4:"4917";s:91:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/UpdateOperationTest.xml";s:4:"134a";s:82:"PHPUnit-3.2.17/PHPUnit/Tests/Extensions/Database/_files/XmlDataSets/XmlDataSet.xml";s:4:"c2d2";s:51:"PHPUnit-3.2.17/PHPUnit/Tests/_files/AnInterface.php";s:4:"dcc9";s:52:"PHPUnit-3.2.17/PHPUnit/Tests/_files/BankAccount.json";s:4:"5d0b";s:55:"PHPUnit-3.2.17/PHPUnit/Tests/_files/BankAccount.metrics";s:4:"68b3";s:51:"PHPUnit-3.2.17/PHPUnit/Tests/_files/BankAccount.pmd";s:4:"68b3";s:51:"PHPUnit-3.2.17/PHPUnit/Tests/_files/BankAccount.tap";s:4:"0c52";s:51:"PHPUnit-3.2.17/PHPUnit/Tests/_files/BankAccount.xml";s:4:"4bbc";s:68:"PHPUnit-3.2.17/PHPUnit/Tests/_files/ClassWithNonPublicAttributes.php";s:4:"06f4";s:54:"PHPUnit-3.2.17/PHPUnit/Tests/_files/DoubleTestCase.php";s:4:"89e0";s:45:"PHPUnit-3.2.17/PHPUnit/Tests/_files/Error.php";s:4:"f86f";s:47:"PHPUnit-3.2.17/PHPUnit/Tests/_files/Failure.php";s:4:"8834";s:57:"PHPUnit-3.2.17/PHPUnit/Tests/_files/InheritedTestCase.php";s:4:"3ec6";s:50:"PHPUnit-3.2.17/PHPUnit/Tests/_files/MockRunner.php";s:4:"4189";s:57:"PHPUnit-3.2.17/PHPUnit/Tests/_files/NoArgTestCaseTest.php";s:4:"dbd4";s:55:"PHPUnit-3.2.17/PHPUnit/Tests/_files/NoTestCaseClass.php";s:4:"2c05";s:51:"PHPUnit-3.2.17/PHPUnit/Tests/_files/NoTestCases.php";s:4:"16ea";s:49:"PHPUnit-3.2.17/PHPUnit/Tests/_files/NonStatic.php";s:4:"13ae";s:57:"PHPUnit-3.2.17/PHPUnit/Tests/_files/NotPublicTestCase.php";s:4:"9a10";s:55:"PHPUnit-3.2.17/PHPUnit/Tests/_files/NotVoidTestCase.php";s:4:"efff";s:51:"PHPUnit-3.2.17/PHPUnit/Tests/_files/OneTestCase.php";s:4:"6f26";s:54:"PHPUnit-3.2.17/PHPUnit/Tests/_files/OutputTestCase.php";s:4:"8d27";s:56:"PHPUnit-3.2.17/PHPUnit/Tests/_files/OverrideTestCase.php";s:4:"8881";s:51:"PHPUnit-3.2.17/PHPUnit/Tests/_files/SampleClass.php";s:4:"4f35";s:52:"PHPUnit-3.2.17/PHPUnit/Tests/_files/SetupFailure.php";s:4:"de7f";s:45:"PHPUnit-3.2.17/PHPUnit/Tests/_files/Sleep.php";s:4:"d215";s:46:"PHPUnit-3.2.17/PHPUnit/Tests/_files/Struct.php";s:4:"6878";s:47:"PHPUnit-3.2.17/PHPUnit/Tests/_files/Success.php";s:4:"0aef";s:55:"PHPUnit-3.2.17/PHPUnit/Tests/_files/TearDownFailure.php";s:4:"9e97";s:52:"PHPUnit-3.2.17/PHPUnit/Tests/_files/TestIterator.php";s:4:"56e2";s:62:"PHPUnit-3.2.17/PHPUnit/Tests/_files/ThrowExceptionTestCase.php";s:4:"071b";s:64:"PHPUnit-3.2.17/PHPUnit/Tests/_files/ThrowNoExceptionTestCase.php";s:4:"7961";s:48:"PHPUnit-3.2.17/PHPUnit/Tests/_files/TornDown.php";s:4:"11d6";s:49:"PHPUnit-3.2.17/PHPUnit/Tests/_files/TornDown2.php";s:4:"06a2";s:49:"PHPUnit-3.2.17/PHPUnit/Tests/_files/TornDown3.php";s:4:"f4f5";s:49:"PHPUnit-3.2.17/PHPUnit/Tests/_files/TornDown4.php";s:4:"a066";s:49:"PHPUnit-3.2.17/PHPUnit/Tests/_files/TornDown5.php";s:4:"934c";s:46:"PHPUnit-3.2.17/PHPUnit/Tests/_files/WasRun.php";s:4:"72cf";s:43:"PHPUnit-3.2.17/PHPUnit/Tests/_files/bar.xml";s:4:"1581";s:43:"PHPUnit-3.2.17/PHPUnit/Tests/_files/foo.xml";s:4:"6dc4";s:25:"lib/JsonPrettyPrinter.php";s:4:"22fd";}',
	'suggests' => array(
	),
);

?>