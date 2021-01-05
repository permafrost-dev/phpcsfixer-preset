<?php

namespace Permafrost\PhpCsFixerRules\Rulesets;

abstract class BaseRuleset implements RuleSet
{
    protected $additional = [];

    public function __construct(array $additional = [])
    {
        $this->additional = $additional;
    }

    abstract public function allowRisky(): bool;
    abstract public static function name(): string;
    abstract public function rules(): array;
}
