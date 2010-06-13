<?php

/**
 * Tests Kohana File helper
 *
 * @group kohana
 * @group kohana.url
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2010 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_FileTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provides test data for testSanitize()
	 * 
	 * @return array
	 */
	function providerMime()
	{
		return array(
			// $value, $result
			array(Kohana::find_file('classes', 'file'), 'application/x-httpd-php'),
			array(Kohana::find_file('', 'phpunit', 'xml'), 'text/xml'),
			array(Kohana::find_file('tests', 'test_data/github', 'png'), 'image/png'),
		);
	}

	/**
	 * Tests File::mime()
	 *
	 * @test
	 * @dataProvider providerMime
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testMime($input, $expected)
	{
		$this->assertSame($expected, File::mime($input));
	}

	/**
	 * Provides test data for testSplitJoin()
	 * 
	 * @return array
	 */
	function providerSplitJoin()
	{
		return array(
			// $value, $result
			array(Kohana::find_file('tests', 'test_data/github', 'png'), .01, 1),
		);
	}

	/**
	 * Tests File::mime()
	 *
	 * @test
	 * @dataProvider providerSplitJoin
	 * @param boolean $input    Input for File::split
	 * @param boolean $peices   Input for File::split
	 * @param boolean $expected Output for File::splut
	 */
	function testSplitJoin($input, $peices, $expected)
	{
		$this->assertSame($expected, File::split($input, $peices));
		$this->assertSame($expected, File::join($input));
	}
}