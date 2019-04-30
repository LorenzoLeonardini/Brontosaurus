<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__.'/../src/init.php');

class ConfigTest extends TestCase {

    public function testInvalidConfigPart1() {
        $this->expectException(InvalidArgumentException::class);
        \Brontosaurus\Config::getProperty("form_token", "random");
    }

    public function testInvalidConfigPart2() {
        $this->expectException(InvalidArgumentException::class);
        \Brontosaurus\Config::getProperty("random", "random");
    }

    public function testDefaultConfig() {
        $this->assertSame(20, \Brontosaurus\Config::getProperty("form_token", "maximum_tokens"));
    }

    public function testLoadingConfig() {
        \Brontosaurus\Config::loadFromFile(__DIR__."/config.yml");
        $this->assertSame(40, \Brontosaurus\Config::getProperty("form_token", "maximum_tokens"));
    }

    public function testUnloadConfig() {
        \Brontosaurus\Config::unloadConfig();
        $this->assertSame(20, \Brontosaurus\Config::getProperty("form_token", "maximum_tokens"));
    }

    public function testMissingProperty() {
        \Brontosaurus\Config::loadFromFile(__DIR__."/config.yml");
        $this->assertSame(true, \Brontosaurus\Config::getProperty("dummy_config", "testing"));
        \Brontosaurus\Config::unloadConfig();
    }

    public function testWrongType() {
        $this->expectException(InvalidArgumentException::class);
        \Brontosaurus\Config::loadFromFile(__DIR__."/wrong_config.yml");
        \Brontosaurus\Config::getProperty("form_token", "maximum_tokens");
    }

    public static function tearDownAfterClass() : void {
        \Brontosaurus\Config::unloadConfig();
    }

}
