<?php

use PHPUnit\Framework\TestCase;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ConfigTest extends TestCase {

    public function testDefaultConfig() {
        \Brontosaurus\Config::loadFromFile(__DIR__."/../src/default_config.yml");
        $this->assertSame(20, \Brontosaurus\Config::getProperty("form_token", "maximum_tokens"));
    }

    public function testInvalidConfigPart1() {
        $this->expectException(InvalidArgumentException::class);
        \Brontosaurus\Config::getProperty("form_token", "random");
    }

    public function testInvalidConfigPart2() {
        $this->expectException(InvalidArgumentException::class);
        \Brontosaurus\Config::getProperty("random", "random");
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
