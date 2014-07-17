<?php
/**
 * @author: gareth
 */

namespace Garoevans\ConfigLoader\Tests;

use Garoevans\ConfigLoader\ConfigLoader;

class ConfigLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReturnsDefault()
    {
        $config = new ConfigLoader(dirname(__DIR__) . '/config/');

        $this->assertEquals(null, $config->get('foo'));
        $this->assertEquals('', $config->get('foo', ''));
        $this->assertEquals(array(1 => 2), $config->get('foo', array(1 => 2)));
        $this->assertEquals('bar', $config->get('foo', 'bar'));
        $this->assertEquals(new \stdClass, $config->get('foo', new \stdClass));
        $this->assertEquals(1.123, $config->get('foo', 1.123));
        $this->assertEquals(null, $config->get('foo', null));
    }

    public function testLoadDefaultConfig()
    {
        $config = new ConfigLoader(dirname(__DIR__) . '/config/');
        $config->load();

        $section_one = $config->get('section_one');

        $this->assertEquals(array('key1' => 'val1', 'key2' => 'val2'), $section_one);
    }

    public function testExceptionThrownWhenFileDoesNotExist()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unable to find [' . dirname(__DIR__) . '/no_config/.config.ini' . ']'
        );
        $config = new ConfigLoader(dirname(__DIR__) . '/no_config/');
        $config->load();
    }

    public function testExceptionThrownWhenFileFailsToLoad()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unable to load [' . dirname(__DIR__) . '/config/.bad.ini' . ']'
        );
        $config = new ConfigLoader(dirname(__DIR__) . '/config/', '.bad.ini');
        $config->load();
    }

    public function testExceptionThrowWhenTryingToReLoadConfig()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Already loaded [' . dirname(__DIR__) . '/config/.config.ini' . ']'
        );
        $config = new ConfigLoader(dirname(__DIR__) . '/config/');
        $config->load();
        $config->load();
    }

    public function testLoadingMoreConfigOverridesValues()
    {
        $config = new ConfigLoader(dirname(__DIR__) . '/config/');
        $config->load();

        $db = $config->get('db');
        $this->assertEquals('127.0.0.1', $db['host']);
        $this->assertEquals("asd][}{'|123@£#€", $db['pass']);

        $config->load('.local.config.ini');

        $db = $config->get('db');
        $this->assertEquals('localhost', $db['host']);
        $this->assertEquals("@£$23456|':", $db['pass']);
    }

    public function testLoadingSubSections()
    {
        $config = new ConfigLoader(dirname(__DIR__) . '/config/');
        $config->load();

        $this->assertEquals("asd][}{'|123@£#€", $config->get('db/pass'));
    }

    public function testDefaultReturnedWhenFailingToLoadSubSection()
    {
        $config = new ConfigLoader(dirname(__DIR__) . '/config/');
        $config->load();

        $this->assertEquals(null, $config->get('db/password'));
    }
}
 