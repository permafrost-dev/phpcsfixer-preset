<?php

namespace Permafrost\PhpCsFixerRules\Commands\Prompts;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $io = new SymfonyStyle($this->input, $this->output);

        if (!$io->confirm("The file '{$filename}' already exists. Overwrite it?", false)) {
            $this->output->writeln('<comment>Not overwriting existing file.</comment>');

            return false;
        }

        return true;
    }
}
