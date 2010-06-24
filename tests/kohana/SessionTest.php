<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests the cookie class
 *
 * @group kohana
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2010 Kohana Team
 * @license    http://kohanaphp.com/license
 */
Class Kohana_SessionTest extends Kohana_Unittest_TestCase
{
	/**
	 * Provides test data for test_set()
	 *
	 * @return array
	 */
	function provider_set()
	{
		return array(
			// $type, $key, $value
			array(NULL, 'unit', 'test'),
			array('native', 'unit', 'test'),
			array('cookie', 'unit', 'test'),
		);
	}

	/**
	 * Tests session::set()
	 *
	 * @test
	 * @dataProvider provider_set
	 * @covers session::instance
	 * @covers session::__construct
	 * @covers session::set
	 * @param mixed   $type     the type of the session to use
	 * @param mixed   $key      key to use
	 * @param mixed   $value    value to set
	 */
	function test_set($type, $key, $value)
	{
		$session = Session::instance($type);
		$session->set($key, $value);
		$this->assertSame($value, $session->get($key));
	}

	/**
	 * Provides test data for test_actions()
	 *
	 * @return array
	 */
	function provider_actions()
	{
		return array(
			// $type, $key, $value
			array('native', 'unit', 'test', 'YToyOntzOjQ6InVuaXQiO3M6NDoidGVzdCI7czozOiJmb28iO3M6MzoiYmFyIjt9', array('unit' => 'test', 'foo' => 'bar')),
			array('cookie', 'unit', 'test', 'YToyOntzOjQ6InVuaXQiO3M6NDoidGVzdCI7czozOiJmb28iO3M6MzoiYmFyIjt9', array('unit' => 'test', 'foo' => 'bar')),
		);
	}

	/**
	 * Tests session actions
	 *
	 * @test
	 * @dataProvider provider_actions
	 * @param mixed   $type     the type of the session to use
	 * @param mixed   $key      key to use
	 * @param mixed   $value    value to set
	 */
	function test_actions($type, $key, $value, $expected_tostring, $expected_array)
	{
		$session = Session::instance($type);
		foreach ($expected_array as $s_key => $s_value)
			$session->set($s_key, $s_value);

		$this->assertSame($expected_tostring, $session->__toString());
		$this->assertSame($value, $session->get($key));
		$this->assertSame($expected_array, $session->as_array());

		$session->delete($key);
		$this->assertSame(array('foo' => 'bar'), $session->as_array());

		$status = $session->destroy();
		$this->assertSame(TRUE, $status);
		$this->assertSame(array(), $session->as_array());
	}
}
