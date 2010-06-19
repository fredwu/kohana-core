<?php

/**
 * Tests Kohana Form helper
 *
 * @group kohana
 * @group kohana.form
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2010 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_FormTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Defaults for this test
	 * @var array
	 */
	protected $environmentDefault = array(
		'Kohana::$base_url' => '/',
		'HTTP_HOST' => 'kohanaframework.org',
	);

	/**
	 * Provides test data for testOpen()
	 * 
	 * @return array
	 */
	function providerOpen()
	{
		return array(
			// $value, $result
			#array(NULL, NULL, '<form action="/" method="post" accept-charset="utf-8">'), // Fails because of Request::$current
			array('foo', NULL, '<form action="/foo" method="post" accept-charset="utf-8">'),
			array('', NULL, '<form action="/" method="post" accept-charset="utf-8">'),
			array('foo', array('method' => 'get'), '<form action="/foo" method="get" accept-charset="utf-8">'),
		);
	}

	/**
	 * Tests Form::open()
	 *
	 * @test
	 * @dataProvider providerOpen
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testOpen($action, $attributes, $expected)
	{
		$this->assertSame($expected, Form::open($action, $attributes));
	}

	/**
	 * Tests Form::close()
	 *
	 * @test
	 */
	function testClose()
	{
		$this->assertSame('</form>', Form::close());
	}

	/**
	 * Provides test data for testInput()
	 * 
	 * @return array
	 */
	function providerInput()
	{
		return array(
			// $value, $result
			array('foo', 'bar', NULL, 'input', '<input type="text" name="foo" value="bar" />'),
			array('foo', NULL, NULL, 'input', '<input type="text" name="foo" />'),
			array('foo', 'bar', NULL, 'hidden', '<input type="hidden" name="foo" value="bar" />'),
			array('foo', 'bar', NULL, 'password', '<input type="password" name="foo" value="bar" />'),
		);
	}

	/**
	 * Tests Form::input()
	 *
	 * @test
	 * @dataProvider providerInput
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testInput($name, $value, $attributes, $type, $expected)
	{
		$this->assertSame($expected, Form::$type($name, $value, $attributes));
	}

	/**
	 * Provides test data for testFile()
	 * 
	 * @return array
	 */
	function providerFile()
	{
		return array(
			// $value, $result
			array('foo', NULL, '<input type="file" name="foo" />'),
		);
	}

	/**
	 * Tests Form::file()
	 *
	 * @test
	 * @dataProvider providerFile
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testFile($name, $attributes, $expected)
	{
		$this->assertSame($expected, Form::file($name, $attributes));
	}

	/**
	 * Provides test data for testCheck()
	 * 
	 * @return array
	 */
	function providerCheck()
	{
		return array(
			// $value, $result
			array('foo', NULL, FALSE, NULL, 'checkbox', '<input type="checkbox" name="foo" />'),
			array('foo', NULL, TRUE, NULL, 'checkbox', '<input type="checkbox" name="foo" checked="checked" />'),
			array('foo', 'bar', TRUE, NULL, 'checkbox', '<input type="checkbox" name="foo" value="bar" checked="checked" />'),
			
			array('foo', NULL, FALSE, NULL, 'radio', '<input type="radio" name="foo" />'),
			array('foo', NULL, TRUE, NULL, 'radio', '<input type="radio" name="foo" checked="checked" />'),
			array('foo', 'bar', TRUE, NULL, 'radio', '<input type="radio" name="foo" value="bar" checked="checked" />'),
		);
	}

	/**
	 * Tests Form::check()
	 *
	 * @test
	 * @dataProvider providerCheck
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testCheck($name, $value, $checked, $attributes, $type, $expected)
	{
		$this->assertSame($expected, Form::$type($name, $value, $checked, $attributes));
	}

	/**
	 * Provides test data for testText()
	 * 
	 * @return array
	 */
	function providerText()
	{
		return array(
			// $value, $result
			array('foo', 'bar', NULL, 'textarea', '<textarea name="foo" cols="50" rows="10">bar</textarea>'),
			array('foo', 'bar', array('rows' => 20, 'cols' => 20), 'textarea', '<textarea name="foo" cols="20" rows="20">bar</textarea>'),
			array('foo', 'bar', NULL, 'button', '<button name="foo">bar</button>'),
			array('foo', 'bar', NULL, 'label', '<label for="foo">bar</label>'),
			array('foo', NULL, NULL, 'label', '<label for="foo">Foo</label>'),
		);
	}

	/**
	 * Tests Form::textarea()
	 *
	 * @test
	 * @dataProvider providerText
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testText($name, $body, $attributes, $type, $expected)
	{
		$this->assertSame($expected, Form::$type($name, $body, $attributes));
	}

	/**
	 * Provides test data for testSelect()
	 * 
	 * @return array
	 */
	function providerSelect()
	{
		return array(
			// $value, $result
			array('foo', NULL, NULL, "<select name=\"foo\"></select>"),
			array('foo', array('bar' => 'bar'), NULL, "<select name=\"foo\">\n<option value=\"bar\">bar</option>\n</select>"),
			array('foo', array('bar' => 'bar'), 'bar', "<select name=\"foo\">\n<option value=\"bar\" selected=\"selected\">bar</option>\n</select>"),
			array('foo', array('bar' => array('foo' => 'bar')), NULL, "<select name=\"foo\">\n<optgroup label=\"bar\">\n<option value=\"foo\">bar</option>\n</optgroup>\n</select>"),
			array('foo', array('bar' => array('foo' => 'bar')), 'foo', "<select name=\"foo\">\n<optgroup label=\"bar\">\n<option value=\"foo\" selected=\"selected\">bar</option>\n</optgroup>\n</select>"),
		);
	}

	/**
	 * Tests Form::select()
	 *
	 * @test
	 * @dataProvider providerSelect
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testSelect($name, $options, $selected, $expected)
	{
		$this->assertSame($expected, Form::select($name, $options, $selected));
	}

	/**
	 * Provides test data for testSubmit()
	 * 
	 * @return array
	 */
	function providerSubmit()
	{
		return array(
			// $value, $result
			array('foo', 'Foobar!', '<input type="submit" name="foo" value="Foobar!" />'),
		);
	}

	/**
	 * Tests Form::submit()
	 *
	 * @test
	 * @dataProvider providerSubmit
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testSubmit($name, $value, $expected)
	{
		$this->assertSame($expected, Form::submit($name, $value));
	}


	/**
	 * Provides test data for testImage()
	 * 
	 * @return array
	 */
	function providerImage()
	{
		return array(
			// $value, $result
			array('foo', 'bar', array('src' => 'media/img/login.png'), '<input type="image" name="foo" value="bar" src="/media/img/login.png" />'),
		);
	}

	/**
	 * Tests Form::submit()
	 *
	 * @test
	 * @dataProvider providerImage
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testImage($name, $value, $attributes, $expected)
	{
		$this->assertSame($expected, Form::image($name, $value, $attributes));
	}
}