<?php
namespace CarbonForms;

class Config {
    public static $values = [];

    public static function load($file) {
        if (!file_exists($file)) {
            throw new ConfigException('Could not find config file: ' . $file);
        }

        self::merge_file($file);
    }

    public static function reset() {
        self::$values = [];
    }

    public static function load_optional($file) {
        if (!file_exists($file)) {
            return;
        }

        self::merge_file($file);
    }

    private static function merge_file($file) {
        $values = require($file);
        if (!is_array($values)) {
            throw new ConfigException("Config file ``$file\" does not return an array. ");
        }

        self::$values = array_replace_recursive(self::$values, $values);
    }
}