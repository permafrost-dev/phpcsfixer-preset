<?php

namespace Permafrost\PhpCsFixerRules\Commands;

use Permafrost\PhpCsFixerRules\Commands\Traits\HasMappedFinderClasses;
use Permafrost\PhpCsFixerRules\Finders\BasicProjectFinder;
use Permafrost\PhpCsFixerRules\Finders\ComposerPackageFinder;
use Permafrost\PhpCsFixerRules\Finders\LaravelPackageFinder;
use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use Permafrost\PhpCsFixerRules\Support\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Finder\Finder;

class GenerateConfigCommand extends Command
{
    use HasMappedFinderClasses;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /** @var \Symfony\Component\Console\Input\InputInterface */
    protected $input;

    public $filename = '.php_cs.dist';

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

    protected function getRulesetName()
    {
        if ($this->input->hasOption('ruleset')) {
            return $this->input->getOption('ruleset');
        }

        return 'default';
    }

    protected function outputFileExists()
    {
        return file_exists($this->getOutputFilename());
    }

    protected function isValidOutputFilename(string $filename): bool
    {
        return trim($filename) !== '' || preg_match('~[\w\.\-\_]+~', $filename) === 1;
    }

    protected function getOutputFilename(): string
    {
        if ($this->input->hasOption('outfile')) {
            $temp = $this->input->getOption('outfile');
            if ($this->isValidOutputFilename($temp)) {
                $this->filename = $temp;
            }
        }

        return getcwd() . DIRECTORY_SEPARATOR . basename($this->filename);
    }

    protected function shouldOverwriteExisting(): bool
    {
        return $this->input->hasOption('force') && $this->input->getOption('force');
    }

    protected function handleError(string $message): int
    {
        $this->output->writeln("<info>$message</info>");

        return Command::FAILURE;
    }

    protected function handleFinished(): int
    {
        $this->output->writeln('<info>Successfully wrote configuration file.</info>');

        return Command::SUCCESS;
    }

    protected function validUserInput($type, $ruleset): bool
    {
        $validRulesets = $this->validRulesets();

        if (!in_array($ruleset, $validRulesets, true)) {
            $this->handleError("Ruleset not found.\nValid rulesets: " . implode(', ', $validRulesets) . '.');

            return false;
        }

        if (!in_array($type, $this->validTypes(), true)) {
            $this->handleError("Invalid type.\nValid types: " . implode(', ', $this->validTypes()) . '.');

            return false;
        }

        return true;
    }

    protected function validRulesets(): array
    {
        $result = [];
        $finder = Finder::create();
        $files = $finder->in(__DIR__ . '/../Rulesets')
            ->name('*Ruleset.php')
            ->files()
            ->getIterator();

        foreach ($files as $file) {
            $result[] = Str::snake(preg_replace('~Ruleset$~', '', $file->getBasename('.php')));
        }

        return $result;
    }

    protected function validTypes(): array
    {
        return array_keys($this->finderConfigTypeMap());
    }

    protected function generateAndSaveCode($type, $ruleset): bool
    {
        $code = $this->generatePhpCsConfig(
            $this->determineCorrectFinder($type),
            Str::studly($ruleset) . 'Ruleset'
        );

        if (file_put_contents($this->getOutputFilename(), $code) === false) {
            return false;
        }

        return true;
    }

    protected function generatePhpCsConfig(string $finderName, string $rulesetClass): string
    {
        //remove the namespace from the finder classname
        $finderNameParts = explode('\\', $finderName);
        $finderNameShort = array_pop($finderNameParts);

        $code = <<<CODE
<?php
require_once(__DIR__.'/vendor/autoload.php');

use $finderName;
use Permafrost\\PhpCsFixerRules\\Rulesets\\$rulesetClass;
use Permafrost\\PhpCsFixerRules\\SharedConfig;

// optional: chain additiional custom Finder options:
\$finder = $finderNameShort::create(__DIR__);

return SharedConfig::create(\$finder, new $rulesetClass());
CODE;

        return trim($code);
    }

    protected function handleExistingOutputFile(): bool
    {
        if (!$this->outputFileExists()) {
            return false;
        }

        if (!$this->shouldOverwriteExisting()) {
            $helperSet = new HelperSet([new SymfonyQuestionHelper()]);
            $this->setHelperSet($helperSet);
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion("The file `{$this->filename}` already exists. Overwrite it?", false);

            if (!$helper->ask($this->input, $this->output, $question)) {
                $this->output->writeln('<info>Not overwriting existing file.</info>');

                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;

        $type = strtolower($input->getFirstArgument());
        $ruleset = strtolower($this->getRulesetName());

        if (!$this->validUserInput($type, $ruleset)) {
            return Command::FAILURE;
        }

        if ($this->outputFileExists() && !$this->handleExistingOutputFile()) {
            return Command::FAILURE;
        }

        if (!$this->generateAndSaveCode($type, $ruleset)) {
            $this->handleError('Failed to write to output file.');

            return Command::FAILURE;
        }

        return $this->handleFinished();
    }
}
