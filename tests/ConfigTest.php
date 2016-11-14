<?php
use PHPUnit\Framework\TestCase;

use CarbonForms\Config;

class ConfigTest extends TestCase {
    function tearDown() {
        Config::reset();
    }

    /** @test */
    function it_loads_mandatory_files() {
        Config::load(__DIR__ . '/test-data/config.php');

        $this->assertArrayHasKey('got_config', Config::$values);
        $this->assertArrayNotHasKey('missing', Config::$values);
    }

    /**
     * @test
     * @expectedException \CarbonForms\ConfigException
     */
    function it_throws_exceptions_when_mandatory_files_are_not_found() {
        Config::load(__DIR__ . '/test-data/____missing_____.php');
    }

    /** @test */
    function it_doesnt_complain_when_optional_files_are_missing() {
        Config::load_optional(__DIR__ . '/test-data/____missing_____.php');
    }

    /** @test */
    function it_overwrites_config_when_new_files_are_consumed() {
        Config::load(__DIR__ . '/test-data/config.php');
        Config::load(__DIR__ . '/test-data/overwrite.php');
        $this->assertEquals("overwritten value", Config::$values['got_config']);
    }

    /** @test */
    function it_doesnt_destroy_inner_vals_during_overwriting() {
        Config::load(__DIR__ . '/test-data/config.php');
        Config::load(__DIR__ . '/test-data/overwrite.php');

        $this->assertCount(3, Config::$values['deep_structure']);
    }

    /** @test */
    function it_overwrites_inner_values() {
        Config::load(__DIR__ . '/test-data/config.php');
        Config::load(__DIR__ . '/test-data/overwrite.php');

        $this->assertEquals(2, Config::$values['ovewritten_inner_val']['inner']);
    }
    /**
     * @test
     * @expectedException \CarbonForms\ConfigException
     */
    function it_complains_about_misformatted_config_files() {
        Config::load(__DIR__ . '/test-data/mifromatted-config.php');
    }
}