<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Description of RouteTest
 *
 * @group kohana
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_RouteTest extends Kohana_Unittest_TestCase
{
	/**
	 * Remove all caches
	 */
	function setUp()
	{
		parent::setUp();

		$this->clean_cache_dir();
	}

	/**
	 * Removes cache files created during tests
	 */
	function tearDown()
	{
		parent::tearDown();

		$this->clean_cache_dir();
	}

	/**
	 * Removes all kohana related cache files in the cache directory
	 */
	function clean_cache_dir()
	{
		$cache_dir = opendir(Kohana::$cache_dir);

		while($dir = readdir($cache_dir))
		{
			// Cache files are split into directories based on first two characters of hash
			if($dir[0] !== '.' AND strlen($dir) === 2)
			{
				$cache = opendir(Kohana::$cache_dir.'/'.$dir);

				while($file = readdir($cache))
				{
					if($file[0] !== '.')
					{
						unlink(Kohana::$cache_dir.'/'.$dir.'/'.$file);
					}
				}

				closedir($cache);

				rmdir(Kohana::$cache_dir.'/'.$dir);
			}
		}

		closedir($cache_dir);
	}

	/**
	 * Route::matches() should return false if the route doesn't match against a uri
	 *
	 * @test
	 */
	function test_matches_returns_false_on_failure()
	{
		$route = new Route('projects/(<project_id>/(<controller>(/<action>(/<id>))))');

		$this->assertSame(FALSE, $route->matches('apple/pie'));
	}

	/**
	 * Route::matches() should return an array of parameters when a match is made
	 * An parameters that are not matched should not be present in the array of matches
	 *
	 * @test
	 * @covers Route
	 */
	function test_matches_returns_array_of_parameters_on_successful_match()
	{
		$route = new Route('(<controller>(/<action>(/<id>)))');

		$matches = $route->matches('welcome/index');

		$this->assertType('array', $matches);
		$this->assertArrayHasKey('controller', $matches);
		$this->assertArrayHasKey('action', $matches);
		$this->assertArrayNotHasKey('id', $matches);
		$this->assertSame(2, count($matches));
		$this->assertSame('welcome', $matches['controller']);
		$this->assertSame('index', $matches['action']);
	}

	/**
	 * Defaults specified with defaults() should be used if their values aren't
	 * present in the uri
	 *
	 * @test
	 * @covers Route
	 */
	function test_defaults_are_used_if_params_arent_specified()
	{
		$route = new Route('(<controller>(/<action>(/<id>)))');
		$route->defaults(array('controller' => 'welcome', 'action' => 'index'));

		$matches = $route->matches('');

		$this->assertType('array', $matches);
		$this->assertArrayHasKey('controller', $matches);
		$this->assertArrayHasKey('action', $matches);
		$this->assertArrayNotHasKey('id', $matches);
		$this->assertSame(2, count($matches));
		$this->assertSame('welcome', $matches['controller']);
		$this->assertSame('index', $matches['action']);
		$this->assertSame('unit/test/1', $route->uri(array(
			'controller' => 'unit',
			'action' => 'test',
			'id' => '1'
		)));
		$this->assertSame('welcome/index', $route->uri());
	}

	/**
	 * This tests that routes with required parameters will not match uris without them present
	 *
	 * @test
	 * @covers Route
	 */
	function test_required_parameters_are_needed()
	{
		$route = new Route('admin(/<controller>(/<action>(/<id>)))');

		$this->assertFalse($route->matches(''));

		$matches = $route->matches('admin');

		$this->assertType('array', $matches);

		$matches = $route->matches('admin/users/add');

		$this->assertType('array', $matches);
		$this->assertSame(2, count($matches));
		$this->assertArrayHasKey('controller', $matches);
		$this->assertArrayHasKey('action', $matches);
	}

	/**
	 * This tests the reverse routing returns the uri specified in the route
	 * if it's a static route
	 *
	 * A static route is a route without any parameters
	 *
	 * @test
	 * @covers Route::uri
	 */
	function test_reverse_routing_returns_routes_uri_if_route_is_static()
	{
		$route = new Route('info/about_us');

		$this->assertSame('info/about_us', $route->uri(array('some' => 'random', 'params' => 'to confuse')));
	}

	/**
	 * Tests route caching
	 *
	 * @todo remove the cached files. need to delete the cache before running the unit tests to get coverage
	 *
	 * @test
	 * @covers Route::cache
	 */
	function test_cache_name()
	{
		$this->assertSame(FALSE, Route::cache());
		Route::set('foobar', '(<controller>(/<action>(/<id>)))')
			->defaults(array(
				'controller' => 'welcome',
			)
		);

		$route = Route::get('foobar');
		$this->assertSame('foobar', Route::name($route));
		$this->assertSame(NULL, Route::cache(TRUE));
	}

	/**
	 * Provides test data for test_composing_url_from_route()
	 * @return array
	 */
	function provider_composing_url_from_route()
	{
		return array(
			array('/welcome'),
			array('/news/view/42', array('controller' => 'news', 'action' => 'view', 'id' => 42)),
			array('http://kohanaframework.org/news', array('controller' => 'news'), true)
		);
	}

	/**
	 * Tests Route::url()
	 *
	 * Checks the url composing from specific route via Route::url() shortcut
	 *
	 * @test
	 * @dataProvider provider_composing_url_from_route
	 * @param string $expected
	 * @param array $params
	 * @param boolean $protocol
	 */
	function test_composing_url_from_route($expected, $params = NULL, $protocol = NULL)
	{
		Route::set('foobar', '(<controller>(/<action>(/<id>)))')
			->defaults(array(
				'controller' => 'welcome',
			)
		);

		$this->setEnvironment(array(
			'_SERVER' => array('HTTP_HOST' => 'kohanaframework.org'),
			'Kohana::$base_url' => '/',
			'Request::$protocol' => 'http',
			'Kohana::$index_file' => '',
		));

		$this->assertSame($expected, Route::url('foobar', $params, $protocol));
	}
}
