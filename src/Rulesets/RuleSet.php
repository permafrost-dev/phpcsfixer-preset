<?php

namespace Permafrost\PhpCsFixerRules\Rulesets;

interface RuleSet
{
    public function allowRisky(): bool;

    public static function name(): string;

    public function rules(): array;
}
