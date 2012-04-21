<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Oliver Klee <typo3-coding@oliverklee.de>
 *
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

/**
 * Test case for class Tx_Phpunit_Service_FakeSettingsService.
 *
 * @package TYPO3
 * @subpackage tx_phpunit
 *
 * @author Oliver Klee <typo3-coding@oliverklee.,de>
 */
class Tx_Phpunit_Service_FakeSettingsServiceTest extends Tx_Phpunit_TestCase {
	/**
	 * @var Tx_Phpunit_Service_FakeSettingsService
	 */
	protected $fixture = NULL;

	public function setUp() {
		$this->fixture = new Tx_Phpunit_Service_FakeSettingsService();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function classIsSingletonUserSettings() {
		$this->assertInstanceOf(
			'Tx_Phpunit_Interface_UserSettingsService',
			$this->fixture
		);
	}

	/**
	 * @test
	 */
	public function getAsBooleanForMissingValueReturnsFalse() {
		$this->assertFalse(
			$this->fixture->getAsBoolean('foo')
		);
	}

	/**
	 * @test
	 */
	public function setCanSetBooleanValueToFalse() {
		$key = 'foo';
		$this->fixture->set($key, FALSE);

		$this->assertFalse(
			$this->fixture->getAsBoolean($key)
		);
	}

	/**
	 * @test
	 */
	public function setCanSetBooleanValueToTrue() {
		$key = 'foo';
		$this->fixture->set($key, TRUE);

		$this->assertTrue(
			$this->fixture->getAsBoolean($key)
		);
	}

	/**
	 * @test
	 */
	public function getAsBooleanCanReturnOneStringAsTrue() {
		$key = 'foo';
		$this->fixture->set($key, '1');

		$this->assertTrue(
			$this->fixture->getAsBoolean($key)
		);
	}

	/**
	 * @test
	 */
	public function getAsIntegerForMissingValueReturnsZero() {
		$this->assertSame(
			0,
			$this->fixture->getAsInteger('foo')
		);
	}

	/**
	 * @test
	 */
	public function setCanSetIntegerValue() {
		$key = 'foo';
		$value = 42;
		$this->fixture->set($key, $value);

		$this->assertSame(
			$value,
			$this->fixture->getAsInteger($key)
		);
	}

	/**
	 * @test
	 */
	public function getAsStringForMissingValueReturnsEmptyString() {
		$this->assertSame(
			'',
			$this->fixture->getAsString('foo')
		);
	}

	/**
	 * @test
	 */
	public function setCanSetStringValue() {
		$key = 'foo';
		$value = 'Hello world!';
		$this->fixture->set($key, $value);

		$this->assertSame(
			$value,
			$this->fixture->getAsString($key)
		);
	}
}
?>