<?php

namespace Permafrost\PhpCsFixerRules\Commands;

use Permafrost\PhpCsFixerRules\Commands\Traits\HasMappedFinderClasses;
use Permafrost\PhpCsFixerRules\Finders\BasicProjectFinder;
use Permafrost\PhpCsFixerRules\Finders\ComposerPackageFinder;
use Permafrost\PhpCsFixerRules\Finders\LaravelPackageFinder;
use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use Permafrost\PhpCsFixerRules\Support\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class GenerateConfigCommand extends Command
{
    use HasMappedFinderClasses;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /** @var \Symfony\Component\Console\Input\InputInterface */
    protected $input;

    public $filename = '.php_cs.dist.1';

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

    protected function getOutputFilename(): string
    {
        return getcwd() . DIRECTORY_SEPARATOR . basename($this->filename);
    }

    protected function handleError(string $message): int
    {
        $this->output->writeln("Error: <info>$message</info>");

        return Command::FAILURE;
    }

    protected function handleFinished(): int
    {
        $this->output->writeln('<info>Successfully wrote configuration file.</info>');

        return Command::SUCCESS;
    }

    protected function validateUserInput($type, $ruleset)
    {
        $validRulesets = $this->validRulesets();

        if (!in_array($ruleset, $validRulesets, true)) {
            return $this->handleError('Ruleset not found.  Valid rulesets: ' . implode(', ', $validRulesets) . '.');
        }

        if (!in_array($type, $this->validTypes(), true)) {
            return $this->handleError('Invalid type.  Specify one of: ' . implode(', ', $this->validTypes()) . '.');
        }
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

    protected function generateAndSaveCode($type, $ruleset): void
    {
        $code = $this->generatePhpCsConfig(
            $this->determineCorrectFinder($type),
            Str::studly($ruleset) . 'Ruleset'
        );

        file_put_contents($this->getOutputFilename(), $code);
    }

    protected function generatePhpCsConfig(string $finderName, string $rulesetClass)
    {
        $code = <<<CODE
<?php
require_once(__DIR__.'/vendor/autoload.php');

use Permafrost\\PhpCsFixerRules\\Finders\\$finderName;
use Permafrost\\PhpCsFixerRules\\Rulesets\\$rulesetClass;
use Permafrost\\PhpCsFixerRules\\SharedConfig;

// optional: chain additiional custom Finder options:
\$finder = $finderName::create(__DIR__); 

return SharedConfig::create(\$finder, new $rulesetClass());
CODE;

        return trim($code);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;

        if ($this->outputFileExists()) {
            return $this->handleError("An existing '{$this->filename}' file already exists.  Exiting.");
        }

        $type = strtolower($input->getFirstArgument());
        $ruleset = $this->getRulesetName();

        $this->validateUserInput($type, $ruleset);
        $this->generateAndSaveCode($type, $ruleset);

        return $this->handleFinished();
    }
}
