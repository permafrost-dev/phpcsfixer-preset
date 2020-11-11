<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\SharedConfig;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class SharedConfigTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_php_cs_fixer_config_object(): void
    {
        $finder = Finder::create();
        $config = SharedConfig::create($finder);

        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @test
     */
    public function it_merges_rules(): void
    {
        $ruleset = new DefaultRuleset();
        $rules = SharedConfig::loadAndMergeRules($ruleset, ['__MERGED_RULE__' => 12]);
        $actualRules = $ruleset->rules();

        $this->assertIsArray($rules);
        $this->assertCount(count($actualRules) + 1, $rules);
        $this->assertArrayHasKey('__MERGED_RULE__', $rules);
        $this->assertEquals(12, $rules['__MERGED_RULE__']);
    }

    /**
     * @test
     */
    public function it_loads_rules(): void
    {
        $ruleset = new DefaultRuleset();
        $rules = SharedConfig::loadAndMergeRules($ruleset, []);
        $actualRules = $ruleset->rules();

        $this->assertIsArray($rules);
        $this->assertEquals($actualRules, $rules);
    }
}
