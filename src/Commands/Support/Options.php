<?php

namespace Permafrost\PhpCsFixerRules\Commands\Support;

class Options
{
    protected $input;
    protected $ruleset;
    protected $type;
    protected $force;
    protected $filename;

    public function __construct($input)
    {
        $this->input = $input;

        $this->initialize();
    }

    protected function initialize(string $defaultFilename = '.php_cs.dist'): void
    {
        $this->filename = $this->input->hasOption('outfile')
            ? $this->input->getOption('outfile')
            : $defaultFilename;

        $this->force = $this->input->hasOption('force')
            && $this->input->getOption('force');

        $this->ruleset = $this->input->hasOption('ruleset')
            ? $this->input->getOption('ruleset')
            : 'default';

        $this->type = strtolower($this->input->getFirstArgument());
    }

    public function rulesetName(): string
    {
        return $this->ruleset;
    }

    public function typeName(): string
    {
        return $this->type;
    }

    public function overwriteExisting(): bool
    {
        return $this->force;
    }

    public function filename(): string
    {
        return $this->filename;
    }
}
