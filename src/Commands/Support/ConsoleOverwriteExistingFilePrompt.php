<?php

namespace Permafrost\PhpCsFixerRules\Commands\Support;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ConsoleOverwriteExistingFilePrompt
{
    protected $input;
    protected $output;
    protected $command;

    public function __construct($input, $output, Command $command)
    {
        $this->input = $input;
        $this->output = $output;
        $this->command = $command;
    }

    public function display(string $filename): bool
    {
        $this->setHelper();

        $question = new ConfirmationQuestion("The file '{$filename}' already exists. Overwrite it?", false);

        if (!$this->helper()->ask($this->input, $this->output, $question)) {
            $this->output->writeln('<info>Not overwriting existing file.</info>');

            return false;
        }

        return true;
    }

    protected function setHelper(): void
    {
        $helperSet = new HelperSet([new SymfonyQuestionHelper()]);

        $this->command->setHelperSet($helperSet);
    }

    protected function helper()
    {
        return $this->command->getHelper('question');
    }
}
