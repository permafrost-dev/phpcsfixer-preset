<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\SpatieRuleset;
use Permafrost\PhpCsFixerRules\SharedConfig;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class SharedConfigTest extends TestCase
{
    /** @test */
    public function it_returns_a_php_cs_fixer_config_object(): void
    {
        $finder = Finder::create();
        $config = SharedConfig::create($finder);

        $this->assertInstanceOf(Config::class, $config);
    }

    /** @test */
    public function it_returns_the_default_ruleset_when_none_is_provided_to_the_create_method(): void
    {
        $finder = Finder::create();
        $config = SharedConfig::create($finder);
        $expectedRules = (new DefaultRuleset())->rules();

        $this->assertSame($expectedRules, $config->getRules());
    }

    /** @test */
    public function it_returns_the_provided_ruleset_when_one_is_provided_to_the_create_method(): void
    {
        $finder = Finder::create();
        $config = SharedConfig::create($finder, new SpatieRuleset());
        $expectedRules = (new SpatieRuleset())->rules();

        $this->assertSame($expectedRules, $config->getRules());
    }
}
