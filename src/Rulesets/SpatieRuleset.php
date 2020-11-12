<?php

namespace Permafrost\PhpCsFixerRules\Rulesets;

class SpatieRuleset implements RuleSet
{
    public function name(): string
    {
        return 'spatie';
    }

    /**
     * Used with permission; original file https://github.com/spatie/spatie.be/blob/master/.php_cs
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '@PSR2' => true,
            'array_syntax' => ['syntax' => 'short'],
            'ordered_imports' => ['sortAlgorithm' => 'alpha'],
            'no_unused_imports' => true,
            'not_operator_with_successor_space' => true,
            'trailing_comma_in_multiline_array' => true,
            'phpdoc_scalar' => true,
            'unary_operator_spaces' => true,
            'binary_operator_spaces' => true,
            'blank_line_before_statement' => [
                'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
            ],
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_var_without_name' => true,
            'class_attributes_separation' => [
                'elements' => [
                    'method',
                ],
            ],
            'method_argument_space' => [
                'on_multiline' => 'ensure_fully_multiline',
                'keep_multiple_spaces_after_comma' => true,
            ],
            'single_trait_insert_per_statement' => true,
        ];
    }
}