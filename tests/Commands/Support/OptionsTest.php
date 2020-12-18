<?php

namespace Permafrost\Tests\Unit\Commands\Support;

use Permafrost\PhpCsFixerRules\Commands\Support\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    protected function getMockInputClass()
    {
        return new class() {
            public $options = [];
            public $firstArg = '';

            public function setOption($name, $value): void
            {
                $this->options[$name] = $value;
            }

            public function hasOption($name): bool
            {
                return isset($this->options[$name]);
            }

            public function getOption($name)
            {
                return $this->options[$name];
            }

            public function getFirstArgument(): string
            {
                return $this->firstArg;
            }
        };
    }

    /** @test */
    public function it_returns_the_correct_filename(): void
    {
        $input = $this->getMockInputClass();
        $input->setOption('outfile', 'test.conf');
        $input->firstArg = 'project';
        $options = new Options($input);

        $this->assertEquals('test.conf', $options->filename());

        $input = $this->getMockInputClass();
        $input->firstArg = 'project';
        $options = new Options($input);

        $this->assertNotEmpty($options->filename());
    }

    /** @test */
    public function it_returns_the_correct_overwrite_existing_file_value(): void
    {
        $input = $this->getMockInputClass();
        $input->setOption('force', true);
        $input->firstArg = 'project';

        $options = new Options($input);
        $this->assertTrue($options->overwriteExisting());

        $input->setOption('force', false);
        $options = new Options($input);
        $this->assertFalse($options->overwriteExisting());
    }

    /** @test */
    public function it_returns_the_correct_type_name(): void
    {
        $input = $this->getMockInputClass();
        $input->firstArg = 'test_type';

        $options = new Options($input);

        $this->assertEquals('test_type', $options->typeName());
    }

    /** @test */
    public function it_returns_the_correct_ruleset_name(): void
    {
        $input = $this->getMockInputClass();
        $input->setOption('ruleset', 'testruleset');
        $input->firstArg = 'test_type';

        $options = new Options($input);

        $this->assertEquals('testruleset', $options->rulesetName());
    }
}
