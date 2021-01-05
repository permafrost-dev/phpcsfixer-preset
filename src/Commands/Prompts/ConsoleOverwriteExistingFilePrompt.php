<?php

namespace Permafrost\PhpCsFixerRules\Commands\Prompts;

use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleOverwriteExistingFilePrompt
{
    protected $input;
    protected $output;
    protected $command;
    protected $io = null;

    public function __construct($input, $output, $command, $io = null)
    {
        $this->input = $input;
        $this->output = $output;
        $this->command = $command;
        $this->io = $io;
    }

    public function display(string $filename): bool
    {
        $io = $this->io ?? new SymfonyStyle($this->input, $this->output);

        if (!$io->confirm("The file '{$filename}' already exists. Overwrite it?", false)) {
            $this->output->writeln('<comment>Not overwriting existing file.</comment>');

            return false;
        }

        return true;
    }
}
