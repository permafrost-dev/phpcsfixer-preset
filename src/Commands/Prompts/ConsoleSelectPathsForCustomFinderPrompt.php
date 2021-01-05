<?php

namespace Permafrost\PhpCsFixerRules\Commands\Prompts;

use Permafrost\PhpCsFixerRules\Support\Collection;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleSelectPathsForCustomFinderPrompt
{
    public const INCLUDE_PATHS_PROMPT = 1;
    public const EXCLUDE_PATHS_PROMPT = 2;

    protected $input;
    protected $output;
    protected $command;
    protected $io = null;

    /** @var bool $includeNoneOption */
    protected $includeNoneOption = true;

    /** @var array $excludedPaths */
    protected $excludedPaths = [];

    /** @var int $promptType */
    protected $promptType = self::INCLUDE_PATHS_PROMPT;

    public function __construct($input, $output, $command, $io = null)
    {
        $this->input = $input;
        $this->output = $output;
        $this->command = $command;
        $this->io = $io;
    }

    public function display($items, array $excludeItems = []): array
    {
        $excludeItems = array_merge(['.idea', '.vscode', 'node_modules', 'vendor'], $this->excludedPaths, $excludeItems);

        if (!$this->hasPreparedItems($items, $excludeItems)) {
            return [];
        }

        $io = $this->io ?? new SymfonyStyle($this->input, $this->output);

        $result = $io->askQuestion(
            $this->getQuestion($items, $this->promptType, $excludeItems)
        );

        if ($result === ['none']) {
            $result = [];
        }

        return $result;
    }

    public function withNoneOption(bool $value = true): self
    {
        $this->includeNoneOption = $value;

        return $this;
    }

    public function excludePaths(array $paths): self
    {
        $this->excludedPaths = $paths;

        return $this;
    }

    public function withIncludePromptType(): self
    {
        $this->promptType = self::INCLUDE_PATHS_PROMPT;

        return $this;
    }

    public function withExcludePromptType(): self
    {
        $this->promptType = self::EXCLUDE_PATHS_PROMPT;

        return $this;
    }

    public function getQuestion($items, int $type, array $excludeItems = []): ChoiceQuestion
    {
        return (new ChoiceQuestion(
            $this->getPromptText($type),
            $this->prepareItems($items, $excludeItems)
        ))->setMultiselect(true);
    }

    public function getPromptText(int $type): string
    {
        $prompts = [
            self::INCLUDE_PATHS_PROMPT => '%s <%s>SEARCH</>',
            self::EXCLUDE_PATHS_PROMPT => '%s <%s>IGNORE</>',
        ];

        return sprintf(
            $prompts[$type] ?? $prompts[self::INCLUDE_PATHS_PROMPT],
            'Please enter a comma-separated list of the directories php-cs-fixer should',
            'fg=yellow;bg=default;options=bold'
        );
    }

    public function prepareItems($items, array $excludeItems): array
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        if (!is_array($items)) {
            $items = [$items];
        }

        if ($this->includeNoneOption) {
            array_unshift($items, 'none');
        }

        return Collection::create($items)
            ->exclude(['.git', '.github'])
            ->exclude($excludeItems)
            ->exclude($this->excludedPaths)
            ->reject(function ($item) {
                return ($item[0] ?? '') === '.';
            })
            ->toArray();
    }

    public function hasPreparedItems($items, array $excludeItems): bool
    {
        return count($this->prepareItems($items, $excludeItems)) > 0;
    }
}
