<?php

namespace Brontosaurus;

class Config {

    private static $default;
    private static $config;

    public static function loadFromFile(string $filepath) {
        self::$config = \Symfony\Component\Yaml\Yaml::parseFile($filepath);
        if (self::$default == NULL) {
            self::$default = self::$config;
            self::$config = NULL;
        }
    }

    public static function getProperty($parent, $property) {
        if(!isset(self::$default[$parent][$property]))
            throw new \InvalidArgumentException("This config does not exists");

        if(self::$config == NULL) {
            return self::$default[$parent][$property];
        }

        if(!isset(self::$config[$parent][$property])) {
            return self::$default[$parent][$property];
        }

        if(gettype(self::$default[$parent][$property]) !== gettype(self::$config[$parent][$property]))
            throw new \InvalidArgumentException("This config has a wrong type");

        return self::$config[$parent][$property];
    }

}
