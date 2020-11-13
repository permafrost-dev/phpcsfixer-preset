<?php

namespace Permafrost\PhpCsFixerRules\Rulesets;

interface RuleSet
{
    public function name(): string;

    public function rules(): array;
}
