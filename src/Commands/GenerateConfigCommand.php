<?php

namespace Permafrost\PhpCsFixerRules\Commands;

use Permafrost\PhpCsFixerRules\Commands\Traits\DisplaysOutput;
use Permafrost\PhpCsFixerRules\Commands\Traits\HasMappedFinderClasses;
use Permafrost\PhpCsFixerRules\Commands\Traits\HasOutputFile;
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
    use DisplaysOutput, HasMappedFinderClasses, HasOutputFile;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /** @var \Symfony\Component\Console\Input\InputInterface */
    protected $input;

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

    protected function validUserInput($type, $ruleset): bool
    {
        $validRulesets = $this->validRulesets();

        if (!in_array($ruleset, $validRulesets, true)) {
            $this->displayError("Ruleset not found.\nValid rulesets: " . implode(', ', $validRulesets) . '.');

            return false;
        }

        if (!in_array($type, $this->validTypes(), true)) {
            $this->displayError("Invalid type.\nValid types: " . implode(', ', $this->validTypes()) . '.');

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

        $this->updateOutputFilename();

        $type = strtolower($input->getFirstArgument());
        $ruleset = strtolower($this->getRulesetName());

        if (!$this->validUserInput($type, $ruleset)) {
            return Command::FAILURE;
        }

        if ($this->outputFileExists() && !$this->handleExistingOutputFile()) {
            return Command::FAILURE;
        }

        if (!$this->generateAndSaveCode($type, $ruleset)) {
            $this->displayError('Failed to write to output file.');

            return Command::FAILURE;
        }

        $this->displayFinishedSuccessfully();

        return Command::SUCCESS;
    }
}
