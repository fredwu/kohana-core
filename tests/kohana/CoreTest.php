<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Kohana Core
 *
 * @group kohana
 * @group kohana.core
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2010 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_CoreTest extends Kohana_Unittest_TestCase
{
	/**
	 * Provides test data for testSanitize()
	 * 
	 * @return array
	 */
	function providerSanitize()
	{
		return array(
			// $value, $result
			array('foo', 'foo'),
			array("foo\r\nbar", "foo\nbar"),
			array("foo\rbar", "foo\nbar"),
			array("Is your name O\'reilly?", "Is your name O'reilly?")
		);
	}

	/**
	 * Tests Kohana::santize()
	 *
	 * @test
	 * @dataProvider providerSanitize
	 * @covers Kohana::sanitize
	 * @param boolean $value  Input for Kohana::sanitize
	 * @param boolean $result Output for Kohana::sanitize
	 */
	function testSanitize($value, $result)
	{
		$this->setEnvironment(array('Kohana::$magic_quotes' => TRUE));

		$this->assertSame($result, Kohana::sanitize($value));
	}

	/**
	 * Provides test data for testSanitize()
	 * 
	 * @return array
	 */
	function providerFindFile()
	{
		return array(
			// $folder, $class, $result
			array('classes', 'foo', FALSE),
			array('classes', 'date', SYSPATH.'classes/date.php'),
			array('views', 'kohana/error', SYSPATH.'views/kohana/error.php'),
			array('config', 'credit_cards', array(SYSPATH.'config/credit_cards.php', SYSPATH.'config/credit_cards.php'))// Why is this set twice?
		);
	}

	/**
	 * Tests Kohana::find_file()
	 *
	 * @test
	 * @dataProvider providerFindFile
	 * @covers Kohana::find_file
	 * @param boolean $value  Input for Kohana::find_file
	 * @param boolean $result Output for Kohana::find_file
	 */
	function testFindFile($folder, $class, $result)
	{
		$this->assertSame($result, Kohana::find_file($folder, $class));
	}

	/**
	 * Provides test data for testListFiles()
	 * 
	 * @return array
	 */
	function providerListFiles()
	{
		return array(
			// $folder, $result
			array('i18n', array(
				'i18n/en.php' => SYSPATH.'i18n/en.php',
				'i18n/es.php' => SYSPATH.'i18n/es.php',
				'i18n/fr.php' => SYSPATH.'i18n/fr.php',
			)),
			array('messages', array(
				'messages/validate.php' => SYSPATH.'messages/validate.php'
			)),
		);
	}

	/**
	 * Tests Kohana::list_files()
	 *
	 * @test
	 * @dataProvider providerListFiles
	 * @covers Kohana::list_files
	 * @param boolean $folder Input for Kohana::list_files
	 * @param boolean $result Output for Kohana::list_files
	 */
	function testListFiles($folder, $result)
	{
		$this->assertSame($result, Kohana::list_files($folder));
	}

	
	/**
	 * Tests Kohana::init()
	 * @covers Kohana::init
	 * @covers Kohana::deinit
	 *
	 * @test
	 */
	/*
	function testinit()
	{
		$original_modules = Kohana::modules();

		#de-init first
		Kohana::deinit();

		#now we should only have unit test autoloaders
		$this->assertSame(1, count(spl_autoload_functions()));

		#re-init
		spl_autoload_register(array('Kohana', 'auto_load'));
		Kohana::init(array(
			'base_url'   => '/',
			'index_file' => FALSE,
		));

		Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));
		Kohana::$config->attach(new Kohana_Config_File);
		Kohana::modules($original_modules);

		$this->assertSame(2, count(spl_autoload_functions()));
		//$this->assertSame(array(APPPATH, SYSPATH)+array_values($original_modules), Kohana::include_paths());
	}*/

	/**
	 * Tests Kohana::globals()
	 *
	 * @test
	 * @covers Kohana::globals
	 */
	function testGlobals()
	{
		$GLOBALS = array('hackers' => 'foobar');
		$this->assertEquals(array('hackers' => 'foobar'), $GLOBALS);

		Kohana::globals();
		$this->assertEquals(array(), $GLOBALS);
	}

	/**
	 * Provides test data for testCache()
	 * 
	 * @return array
	 */
	function providerCache()
	{
		return array(
			// $value, $result
			array('foo', 'hello, world', 10),
			array('bar', NULL, 10),
			array('bar', NULL, -10),
		);
	}

	/**
	 * Tests Kohana::cache()
	 *
	 * @test
	 * @dataProvider providerCache
	 * @covers Kohana::cache
	 * @param boolean $key      Key to cache/get for Kohana::cache
	 * @param boolean $value    Output from Kohana::cache
	 * @param boolean $lifetime Lifetime for Kohana::cache
	 */
	function testCache($key, $value, $lifetime)
	{
		Kohana::cache($key, $value, $lifetime);
		$this->assertEquals($value, Kohana::cache($key));
	}

	/**
	 * Provides test data for testMessage()
	 * 
	 * @return array
	 */
	function providerMessage()
	{
		return array(
			// $value, $result
			array('validate', 'not_empty', ':field must not be empty'),
			array('validate', NULL, array(
				'not_empty'    => ':field must not be empty',
				'matches'      => ':field must be the same as :param1',
				'regex'        => ':field does not match the required format',
				'exact_length' => ':field must be exactly :param1 characters long',
				'min_length'   => ':field must be at least :param1 characters long',
				'max_length'   => ':field must be less than :param1 characters long',
				'in_array'     => ':field must be one of the available options',
				'digit'        => ':field must be a digit',
				'decimal'      => ':field must be a decimal with :param1 places',
				'range'        => ':field must be within the range of :param1 to :param2',
			)),
		);
	}

	/**
	 * Tests Kohana::message()
	 *
	 * @test
	 * @dataProvider providerMessage
	 * @covers Kohana::message
	 * @param boolean $file     File to look in for Kohana::message
	 * @param boolean $key      Key for Kohana::message
	 * @param boolean $expected Output for Kohana::message
	 */
	function testMessage($file, $key, $expected)
	{
		$this->assertEquals($expected, Kohana::message($file, $key));
	}

	/**
	 * Provides test data for testErrorHandler()
	 * 
	 * @return array
	 */
	function providerErrorHandler()
	{
		return array(
			array(1, 'Foobar', 'foobar.php', __LINE__),
		);
	}

	/**
	 * Tests Kohana::error_handler()
	 *
	 * @test
	 * @dataProvider providerErrorHandler
	 * @covers Kohana::error_handler
	 * @param boolean $code  Input for Kohana::sanitize
	 * @param boolean $error  Input for Kohana::sanitize
	 * @param boolean $file  Input for Kohana::sanitize
	 * @param boolean $line Output for Kohana::sanitize
	 */
	function testErrorHandler($code, $error, $file, $line)
	{
		$error_level = error_reporting();
		error_reporting(E_ALL);
		try
		{
			Kohana::error_handler($code, $error, $file, $line);
		}
		catch (Exception $e)
		{
			$this->assertEquals($code, $e->getCode());
			$this->assertEquals($error, $e->getMessage());
		}
		error_reporting($error_level);
	}

	/**
	 * Provides test data for testExceptionHandler()
	 * 
	 * @return array
	 */
	function providerExceptionHandler()
	{
		return array(
			// $exception_type, $message, $is_cli, $expected
			array('Kohana_Exception', 'hello, world!', TRUE, TRUE),
			array('ErrorException', 'hello, world!', TRUE, TRUE),
			array('Kohana_Exception', 'hello, world!', FALSE, TRUE),
		);
	}

	/**
	 * Tests Kohana::exception_handler()
	 *
	 * @test
	 * @dataProvider providerExceptionHandler
	 * @covers Kohana::exception_handler
	 * @param boolean $exception_type Exception type to throw
	 * @param boolean $message        Message to pass to exception
	 * @param boolean $is_cli         Use cli mode?
	 * @param boolean $expected       Output for Kohana::exception_handler
	 */
	function testExceptionHandler($exception_type, $message, $is_cli, $expected)
	{
		try
		{
			Kohana::$is_cli = $is_cli;
			throw new $exception_type($message);
		}
		catch (Exception $e)
		{
			ob_start();
			$this->assertEquals($expected, Kohana::exception_handler($e));
			ob_clean();
		}

		Kohana::$is_cli = TRUE;
	}

	/**
	 * Provides test data for testDebug()
	 * 
	 * @return array
	 */
	function providerDebug()
	{
		return array(
			// $exception_type, $message, $is_cli, $expected
			array(array('foobar'), "<pre class=\"debug\"><small>array</small><span>(1)</span> <span>(\n    0 => <small>string</small><span>(6)</span> \"foobar\"\n)</span></pre>"),
		);
	}

	/**
	 * Tests Kohana::debug()
	 *
	 * @test
	 * @dataProvider providerDebug
	 * @covers Kohana::debug
	 * @param boolean $thing    The thing to debug
	 * @param boolean $expected Output for Kohana::debug
	 */
	function testdebug($thing, $expected)
	{
		$this->assertEquals($expected, Kohana::debug($thing));
	}

	/**
	 * Provides test data for testDebugPath()
	 * 
	 * @return array
	 */
	function providerDebugPath()
	{
		return array(
			array(Kohana::find_file('classes', 'kohana'), 'APPPATH'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'kohana.php'), // This kinda sucks, app and sys are the same for unit tests
			array(Kohana::find_file('classes', 'kohana/unittest/runner'), 'MODPATH'.DIRECTORY_SEPARATOR.'unittest'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'kohana'.DIRECTORY_SEPARATOR.'unittest'.DIRECTORY_SEPARATOR.'runner.php'),
		);
	}

	/**
	 * Tests Kohana::debug_path()
	 *
	 * @test
	 * @dataProvider providerDebugPath
	 * @covers Kohana::debug_path
	 * @param boolean $path     Input for Kohana::debug_path
	 * @param boolean $expected Output for Kohana::debug_path
	 */
	function testDebugPath($path, $expected)
	{
		$this->assertEquals($expected, Kohana::debug_path($path));
	}

	/**
	 * Provides test data for testModules()
	 * 
	 * @return array
	 */
	function providerModules()
	{
		return array(
			array(NULL, array('unittest' => MODPATH.'unittest/')),
			array(array(), array()),
			array(array('unittest' => MODPATH.'foobar'), array()),
			array(array('unittest' => MODPATH.'unittest'), array('unittest' => MODPATH.'unittest/')),
		);
	}

	/**
	 * Tests Kohana::modules()
	 *
	 * @test
	 * @dataProvider providerModules
	 * @covers Kohana::modules
	 * @param boolean $source   Input for Kohana::modules
	 * @param boolean $expected Output for Kohana::modules
	 */
	function testModules($source, $expected)
	{
		$modules = Kohana::modules();

		$this->assertEquals($expected, Kohana::modules($source));

		Kohana::modules($modules);
	}

	/**
	 * Provides test data for testIncludePaths()
	 * 
	 * @return array
	 */
	function providerIncludePaths()
	{
		return array(
			array(array(APPPATH, MODPATH.'unittest/', SYSPATH)),
		);
	}

	/**
	 * Tests Kohana::include_paths()
	 *
	 * @test
	 * @dataProvider providerIncludePaths
	 * @covers Kohana::include_paths
	 * @param boolean $expected  Input for Kohana::include_paths
	 */
	function testIncludePaths($expected)
	{
		$this->assertEquals($expected, Kohana::include_paths());
	}

	/**
	 * Provides test data for testExceptionText()
	 * 
	 * @return array
	 */
	function providerExceptionText()
	{
		return array(
			array(new Kohana_Exception('foobar'), 'Kohana_Exception [ 0 ]: foobar ~ APPPATH/tests/kohana/CoreTest.php [ '.__LINE__.' ]'),
		);
	}

	/**
	 * Tests Kohana::exception_text()
	 *
	 * @test
	 * @dataProvider providerExceptionText
	 * @covers Kohana::exception_text
	 * @param object $exception exception to test
	 * @param string $expected  expected output
	 */
	function testExceptionText($exception, $expected)
	{
		$this->assertEquals($expected, Kohana::exception_text($exception));
	}

	/**
	 * Provides test data for testDump()
	 * 
	 * @return array
	 */
	function providerDump()
	{
		return array(
			array('foobar', 128, '<small>string</small><span>(6)</span> "foobar"'),
			array('foobar', 2, '<small>string</small><span>(6)</span> "fo&nbsp;&hellip;"'),
			array(NULL, 128, '<small>NULL</small>'),
			array(TRUE, 128, '<small>bool</small> TRUE'),
			array(array('foobar'), 128, "<small>array</small><span>(1)</span> <span>(\n    0 => <small>string</small><span>(6)</span> \"foobar\"\n)</span>"),
			array(new StdClass, 128, "<small>object</small> <span>stdClass(0)</span> <code>{\n}</code>"),
		);
	}

	/**
	 * Tests Kohana::dump()
	 *
	 * @test
	 * @dataProvider providerDump
	 * @covers Kohana::dump
	 * @covers Kohana::_dump
	 * @param object $exception exception to test
	 * @param string $expected  expected output
	 */
	function testDump($input, $length, $expected)
	{
		$this->assertEquals($expected, Kohana::dump($input, $length));
	}
}
