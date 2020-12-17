<?php

namespace Permafrost\PhpCsFixerRules\Commands\Traits;

trait DisplaysOutput
{
    protected function displayError(string $message): void
    {
        $this->output->writeln("<info>$message</info>");
    }

    protected function displayFinishedSuccessfully(): void
    {
        $this->output->writeln("<info>Successfully wrote configuration file '{$this->filename}'.</info>");
    }
}
