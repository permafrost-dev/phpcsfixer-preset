<?php

namespace Permafrost\PhpCsFixerRules\Commands\Prompts;

use Permafrost\PhpCsFixerRules\Support\Collection;
use Permafrost\PhpCsFixerRules\Support\Str;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleSelectPathsForCustomFinderPrompt
{
    public const INCLUDE_PATHS_PROMPT = 1;
    public const EXCLUDE_PATHS_PROMPT = 2;

    protected $input;
    protected $output;
    protected $io = null;

    /** @var bool $includeNoneOption */
    protected $includeNoneOption = true;

    /** @var int $promptType */
    protected $promptType = self::INCLUDE_PATHS_PROMPT;

    public function __construct($input, $output, $io = null)
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = $io;
    }

    public function display($items, array $excludeItems = []): array
    {
        $excludeItems = array_merge(['node_modules', 'vendor'], $excludeItems);

        if (!$this->hasPreparedItems(is_array($items) ? $items : $items->toArray(), $excludeItems)) {
            return [];
        }

        $io = $this->io ?? new SymfonyStyle($this->input, $this->output);

        $result = $io->askQuestion(
            $this->getQuestion($items, $excludeItems)
        );

        return $result[0] !== 'none' ? $result : [];
    }

    public function withNoneOption(bool $value = true): self
    {
        $this->includeNoneOption = $value;

        return $this;
    }

    public function withPromptType(int $type): self
    {
        $this->promptType = $type;

        return $this;
    }

    public function getQuestion($items, array $excludeItems = []): ChoiceQuestion
    {
        $action = $this->promptType === self::EXCLUDE_PATHS_PROMPT
            ? 'IGNORE'
            : 'SEARCH';

        $items = is_array($items) ? $items : $items->toArray();

        $question = new ChoiceQuestion(
            "Please enter a comma-separated list of the directories php-cs-fixer should <fg=yellow;bg=default;options=bold>$action</>",
            $this->prepareItems($items, $excludeItems)
        );

        return $question->setMultiselect(true);
    }

    public function prepareItems(array $items, array $excludeItems): array
    {
        if ($this->includeNoneOption) {
            array_unshift($items, 'none');
        }

        return Collection::create($items)
            ->exclude($excludeItems)
            ->filter(function($item) {
                return !Str::startsWith($item, '.');
            })
            ->values()
            ->toArray();
    }

    public function hasPreparedItems(array $items, array $exclude): bool
    {
        $prepared = $this->prepareItems($items, $exclude);

        return $prepared !== ['none'] && !empty($prepared);
    }
}
