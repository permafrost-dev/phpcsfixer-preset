<?php

namespace Permafrost\Tests\Unit\Commands\Prompts;

use Permafrost\PhpCsFixerRules\Commands\Prompts\ConsoleOverwriteExistingFilePrompt;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleOverwriteExistingFilePromptTest extends TestCase
{
    /** @test */
    public function it_returns_true_when_confirmed_successfully()
    {
        $io = $this->createMock(SymfonyStyle::class);

        $io->method('confirm')
            ->withAnyParameters()
            ->willReturn(true);

        $prompt = new ConsoleOverwriteExistingFilePrompt(null, null, null, $io);

        $this->assertTrue($prompt->display('test.temp'));
    }

    /** @test */
    public function it_returns_false_when_not_confirmed_successfully()
    {
        $io = $this->createMock(SymfonyStyle::class);

        $io->method('confirm')
            ->withAnyParameters()
            ->willReturn(false);

        $output = new class() {
            public function writeln(...$args)
            {
            }
        };

        $prompt = new ConsoleOverwriteExistingFilePrompt(null, $output, null, $io);

        $this->assertFalse($prompt->display('test.temp'));
    }
}
