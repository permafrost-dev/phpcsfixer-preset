<?php

namespace Permafrost\PhpCsFixerRules\Commands\Traits;

trait HasOutputFile
{
    public $filename = '.php_cs.dist';

    protected function updateOutputFilename(): void
    {
        if ($this->input->hasOption('outfile')) {
            $outfile = $this->input->getOption('outfile');

            if ($this->isValidOutputFilename($outfile)) {
                $this->filename = basename($outfile);
            }
        }
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
        return getcwd() . DIRECTORY_SEPARATOR . $this->filename;
    }

    protected function shouldOverwriteExisting(): bool
    {
        return $this->input->hasOption('force') && $this->input->getOption('force');
    }
}
