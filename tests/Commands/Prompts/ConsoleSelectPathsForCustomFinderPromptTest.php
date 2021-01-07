<?php

namespace Permafrost\Tests\Unit\Commands\Prompts;

use Permafrost\PhpCsFixerRules\Commands\Prompts\ConsoleSelectPathsForCustomFinderPrompt;
use Permafrost\PhpCsFixerRules\Support\Collection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleSelectPathsForCustomFinderPromptTest extends TestCase
{
    /** @test */
    public function it_prepares_items_for_display(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null);

        $this->assertSame(['none', 'aaa', 'bbb'], $prompt->withNoneOption(true)->prepareItems(['aaa', 'bbb'], []));
        $this->assertSame(['aaa', 'bbb'], $prompt->withNoneOption(false)->prepareItems(['aaa', 'bbb'], []));
        $this->assertSame(['aaa'], $prompt->withNoneOption(false)->prepareItems(['aaa'], []));
        $this->assertSame(['aaa', 'bbb'], $prompt->withNoneOption(false)->prepareItems(Collection::create(['aaa', 'bbb'])->toArray(), []));
        $this->assertSame(['aaa'], $prompt->withNoneOption(false)->prepareItems(Collection::create(['aaa', 'bbb'])->toArray(), ['bbb']));
    }

    /** @test */
    public function it_returns_a__choice_question_instance(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null);
        $question = $prompt->getQuestion(['aaa']);

        $this->assertInstanceOf(ChoiceQuestion::class, $question);
    }

    /** @test */
    public function it_configures_the_question_instance_correctly(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null);
        $question = $prompt->getQuestion(['aaa']);

        $this->assertTrue($question->isMultiselect());
    }

    /** @test */
    public function it_returns_an_array_after_prompting()
    {
        $io = $this->createMock(SymfonyStyle::class);

        $io->method('askQuestion')
            ->withAnyParameters()
            ->willReturn(['bbb']);

        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, $io);
        $result = $prompt
            ->withNoneOption(false)
            //->excludePaths(['aaa'])
            ->withPromptType(1)
            ->display(['aaa', 'bbb']);

        $this->assertSame(['bbb'], $result);
    }

    /** @test */
    public function it_returns_an_empty_array_after_prompting_returns_none_item()
    {
        $io = $this->createMock(SymfonyStyle::class);

        $io->method('askQuestion')
            ->withAnyParameters()
            ->willReturn(['none']);

        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, $io);
        $result = $prompt
            ->withNoneOption(true)
            //->excludePaths(['aaa'])
            ->withPromptType(1)
            ->display(['aaa', 'bbb']);

        $this->assertEmpty($result);
    }
}
