<?php

namespace Permafrost\PhpCsFixerRules\Commands;

use Permafrost\PhpCsFixerRules\Support\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class GenerateConfigCommand extends Command
{
    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /** @var \Symfony\Component\Console\Input\InputInterface */
    protected $input;

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
        return getcwd() . '/.php_cs.dist';
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
        if (!in_array($ruleset, $this->validRulesets(), true)) {
            return $this->handleError('Ruleset not found.  Valid rulesets: ' . implode(', ', $this->validRulesets()) . '.');
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
        return [
            'laravel',
            'laravel:package',
            'laravel:project',
            'package',
            'project',
        ];
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
     * @param string $type
     *
     * @return string
     */
    protected function determineCorrectFinder(string $type): string
    {
        switch ($type) {
            case 'laravel':
            case 'laravel:project':
                return 'LaravelProjectFinder';

            case 'laravel:package':
                return 'LaravelPackageFinder';

            case 'package':
                return 'ComposerPackageFinder';

            case 'project':
            default:
                return 'BasicProjectFinder';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;

        if ($this->outputFileExists()) {
            return $this->handleError('An existing ".php_cs.dist" file already exists.  Exiting.');
        }

        $type = strtolower($input->getFirstArgument());
        $ruleset = $this->getRulesetName();

        $this->validateUserInput($type, $ruleset);
        $this->generateAndSaveCode($type, $ruleset);

        return $this->handleFinished();
    }

}
