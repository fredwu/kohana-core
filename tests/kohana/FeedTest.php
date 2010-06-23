<?php

/**
 * Test for feed helper
 *
 * @group kohana
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2010 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_FeedTest extends Kohana_Unittest_TestCase
{
	/**
	 * Provides test data for test_parse()
	 * 
	 * @return array
	 */
	function provider_parse()
	{
		return array(
			// $source, $expected
			array('http://dev.kohanaframework.org/projects/kohana3/activity.atom?key=pqSTxjuK4m2b3dSYF4S00eOYW86BJUq3cwzQj2xo', 15),
		);
	}

	/**
	 * @test
	 * 
	 * @dataProvider provider_parse
	 * 
	 * @covers feed::parse
	 * @param string  $source   URL to test
	 * @param integer $expected Count of items
	 */
	function test_parse($source, $expected)
	{
		$this->assertEquals($expected, count(feed::parse($source)));
	}

	/**
	 * Provides test data for test_create()
	 * 
	 * @return array
	 */
	function provider_create()
	{
		return array(
			// $source, $expected
			array(array('pubDate' => 123), array('foo' => array('foo' => 'bar', 'pubDate' => 123, 'link' => 'foo')), array('_SERVER' => array('HTTP_HOST' => 'localhost')+$_SERVER),
				"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
				'<rss version="2.0"><channel><pubDate>'.date('r', 123).'</pubDate><title>Generated Feed</title><link>http://localhost/</link><generator>KohanaPHP</generator><item><foo>bar</foo><pubDate>Wed, 31 Dec 1969 18:02:03 -0600</pubDate><link>http://localhost/foo</link></item></channel></rss>'."\n"
			),
		);
	}

	/**
	 * @test
	 * 
	 * @dataProvider provider_create
	 * 
	 * @covers feed::create
	 * 
	 * @param string  $info     info to pass
	 * @param integer $items    items to add
	 * @param integer $expected output
	 */
	function test_create($info, $items, $enviroment, $expected)
	{
		$this->setEnvironment($enviroment);

		$this->assertEquals($expected, feed::create($info, $items));
	}
}