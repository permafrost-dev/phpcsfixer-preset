<?php

namespace Permafrost\Tests\Unit\Commands\Prompts;

use Permafrost\PhpCsFixerRules\Commands\Prompts\ConsoleSelectPathsForCustomFinderPrompt;
use Permafrost\PhpCsFixerRules\Support\Collection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Question\ChoiceQuestion;

class ConsoleSelectPathsForCustomFinderPromptTest extends TestCase
{
    /** @test */
    public function it_prepares_items_for_display(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, null);

        $this->assertSame(['none', 'aaa', 'bbb'], $prompt->withNoneOption(true)->prepareItems(['aaa', 'bbb'], []));
        $this->assertSame(['aaa', 'bbb'], $prompt->withNoneOption(false)->prepareItems(['aaa', 'bbb'], []));
        $this->assertSame(['aaa'], $prompt->withNoneOption(false)->prepareItems('aaa', []));
        $this->assertSame(['aaa', 'bbb'], $prompt->withNoneOption(false)->prepareItems(Collection::create(['aaa', 'bbb']), []));
        $this->assertSame(['aaa'], $prompt->withNoneOption(false)->prepareItems(Collection::create(['aaa', 'bbb']), ['bbb']));
    }

    /** @test */
    public function it_gets_the_prompt_text_based_on_the_provided_type(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, null);

        $this->assertNotEmpty($prompt->getPromptText(ConsoleSelectPathsForCustomFinderPrompt::INCLUDE_PATHS_PROMPT));
        $this->assertNotEmpty($prompt->getPromptText(ConsoleSelectPathsForCustomFinderPrompt::EXCLUDE_PATHS_PROMPT));

        $this->assertNotSame(
            $prompt->getPromptText(ConsoleSelectPathsForCustomFinderPrompt::INCLUDE_PATHS_PROMPT),
            $prompt->getPromptText(ConsoleSelectPathsForCustomFinderPrompt::EXCLUDE_PATHS_PROMPT)
        );

        $this->assertSame($prompt->getPromptText(ConsoleSelectPathsForCustomFinderPrompt::INCLUDE_PATHS_PROMPT), $prompt->getPromptText(-999));
    }

    /** @test */
    public function it_returns_a__choice_question_instance(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, null);
        $question = $prompt->getQuestion(['aaa'], ConsoleSelectPathsForCustomFinderPrompt::INCLUDE_PATHS_PROMPT);

        $this->assertInstanceOf(ChoiceQuestion::class, $question);
    }

    /** @test */
    public function it_configures_the_question_instance_correctly(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, null);
        $question = $prompt->getQuestion(['aaa'], ConsoleSelectPathsForCustomFinderPrompt::INCLUDE_PATHS_PROMPT);

        $this->assertTrue($question->isMultiselect());
    }

    /** @test */
    public function it_excludes_items_from_the_prompt(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, null);
        $question = $prompt
            ->withNoneOption(false)
            ->excludePaths(['aaa'])
            ->getQuestion(['aaa', 'bbb'], ConsoleSelectPathsForCustomFinderPrompt::INCLUDE_PATHS_PROMPT);

        $this->assertSame(['bbb'], $question->getChoices());
    }

    /** @test */
    public function it_returns_an_empty_array_if_all_items_are_excluded(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, null);
        $result = $prompt
            ->withNoneOption(false)
            ->excludePaths(['aaa', 'bbb'])
            ->withIncludePromptType()
            ->display(['aaa', 'bbb']);

        $this->assertEmpty($result);
    }
}
