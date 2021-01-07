<?php

namespace Permafrost\PhpCsFixerRules\Commands;

use Permafrost\PhpCsFixerRules\Commands\Prompts\ConsoleOverwriteExistingFilePrompt;
use Permafrost\PhpCsFixerRules\Commands\Prompts\ConsoleSelectPathsForCustomFinderPrompt;
use Permafrost\PhpCsFixerRules\Commands\Support\ConfigGenerator;
use Permafrost\PhpCsFixerRules\Commands\Support\CustomConfigGenerator;
use Permafrost\PhpCsFixerRules\Commands\Support\FinderMap;
use Permafrost\PhpCsFixerRules\Commands\Support\Options;
use Permafrost\PhpCsFixerRules\Finders\BasicProjectFinder;
use Permafrost\PhpCsFixerRules\Finders\ComposerPackageFinder;
use Permafrost\PhpCsFixerRules\Finders\LaravelPackageFinder;
use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\LaravelShiftRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\PhpUnitRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\SpatieRuleset;
use Permafrost\PhpCsFixerRules\Support\Path;
use Permafrost\PhpCsFixerRules\Support\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateConfigCommand extends Command
{
    /** @var \Symfony\Component\Console\Output\OutputInterface $output */
    protected $output;

    /** @var \Symfony\Component\Console\Input\InputInterface $input */
    protected $input;

    /** @var FinderMap $finderMap */
    protected $finderMap;

    /** @var Options $options */
    protected $options;

    public function __construct(string $name = null)
    {
        $this->finderMap = new FinderMap($this->finders());
        $this->finderMap->mapType('custom', \PhpCsFixer\Finder::class);

        parent::__construct($name);
    }

    /**
     * Returns an array of all valid Finder classnames.
     *
     * @return string[]
     */
    protected function finders(): array
    {
        return [
            BasicProjectFinder::class,
            ComposerPackageFinder::class,
            LaravelPackageFinder::class,
            LaravelProjectFinder::class,
        ];
    }

    /**
     * Returns an array of all valid Ruleset names.
     *
     * @return string[]
     */
    protected function rulesets(): array
    {
        return [
            DefaultRuleset::name(),
            LaravelShiftRuleset::name(),
            PhpUnitRuleset::name(),
            SpatieRuleset::name(),
        ];
    }

    /**
     * Returns an array of all valid configuration type names.
     *
     * @return array
     */
    protected function types(): array
    {
        $result = array_keys($this->finderMap->getMap());

        sort($result);

        return $result;
    }

    /**
     * Returns the fully-qualified output filename.
     *
     * @return string
     */
    protected function getOutputFilename(): string
    {
        return getcwd() . DIRECTORY_SEPARATOR . $this->options->filename();
    }

    /**
     * Validates user-provided input:
     *   - ruleset flag
     *   - type parameter.
     *
     * Returns true if the user input is valid, otherwise false.
     *
     * @return bool
     */
    protected function validUserInput(): bool
    {
        if (!in_array($this->options->rulesetName(), $this->rulesets(), true)) {
            $this->output->writeln('<comment>Ruleset not found.</comment>');
            $this->output->writeln('<comment>Valid rulesets: ' . implode(', ', $this->rulesets()) . '.</comment>');

            return false;
        }

        if (!in_array($this->options->typeName(), $this->types(), true)) {
            $this->output->writeln('<comment>Invalid type.</comment>');
            $this->output->writeln('<comment>Valid types: ' . implode(', ', $this->types()) . '.</comment>');

            return false;
        }

        return true;
    }

    /**
     * Generates the configuration file and tries to write the contents to file.
     * Returns true on success or false if the file could not be written.
     *
     * @param ConfigGenerator $generator
     *
     * @return bool
     */
    protected function generateAndSaveCode(ConfigGenerator $generator): bool
    {
        $type = $this->options->typeName();
        $ruleset = Str::studly($this->options->rulesetName()) . 'Ruleset';
        $code = $generator->generate($this->finderMap->find($type), $ruleset);

        if (file_put_contents($this->getOutputFilename(), $code) === false) {
            $this->output->writeln('<comment>Failed to write to output file.</comment>');

            return false;
        }

        return true;
    }

    /**
     * If the --force flag was not provided, display a prompt to the user asking if they want to
     * overwrite the existing file.
     *
     * Returns true if the file should be overwritten, otherwise false.
     *
     * @return bool
     */
    protected function overwriteExistingFile(): bool
    {
        if (!$this->options->overwriteExisting()) {
            $prompt = new ConsoleOverwriteExistingFilePrompt($this->input, $this->output, $this);

            return $prompt->display($this->options->filename());
        }

        return true;
    }

    /**
     * Returns true if the output file exists, otherwise false.
     *
     * @return bool
     */
    protected function outputFileExists(): bool
    {
        return file_exists($this->getOutputFilename())
            && !is_dir($this->getOutputFilename());
    }

    /**
     * Returns a configured `CustomConfigGenerator` instance.  Configuration is determined by prompting the user for
     * the directories to include and exclude.
     *
     * @return CustomConfigGenerator
     */
    protected function createCustomConfigGenerator(): CustomConfigGenerator
    {
        $generator = new CustomConfigGenerator();
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt($this->input, $this->output);
        $dirNames = Path::getSubDirectoryNames(getcwd());
        $include = $prompt->withPromptType(1)->withNoneOption(false)->display($dirNames);
        $exclude = $prompt->withPromptType(2)->withNoneOption(true)->display($dirNames, $include);

        $generator->setPaths($include, $exclude);

        return $generator;
    }

    /**
     * Returns a default (non-custom) config generator instance.
     *
     * @return ConfigGenerator
     */
    protected function createDefaultConfigGenerator(): ConfigGenerator
    {
        return new ConfigGenerator();
    }

    /**
     * Generates and saves the code using the correct generator based on the config type.
     * Uses CustomConfigGenerator if the type is 'custom', otherwise uses ConfigGenerator.
     *
     * @see GenerateConfigCommand::createDefaultConfigGenerator()
     * @see GenerateConfigCommand::createCustomConfigGenerator()
     *
     * @return bool
     */
    protected function runCodeGenerator(): bool
    {
        if ($this->options->typeName() === 'custom') {
            $generator = $this->createCustomConfigGenerator();
        } else {
            $generator = $this->createDefaultConfigGenerator();
        }

        return $this->generateAndSaveCode($generator);
    }

    /**
     * Initializes instance properties.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function executeInit(InputInterface $input, OutputInterface $output): void
    {
        $this->output = $output;
        $this->input = $input;
        $this->options = new Options($input);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executeInit($input, $output);

        $filename = $this->options->filename();

        if (!$this->validUserInput()) {
            return Command::FAILURE;
        }

        if ($this->outputFileExists() && !$this->overwriteExistingFile()) {
            return Command::FAILURE;
        }

        if (!$this->runCodeGenerator()) {
            return Command::FAILURE;
        }

        $this->output->writeln("<info>Successfully wrote configuration file '{$filename}'.</info>");

        return Command::SUCCESS;
    }
}
