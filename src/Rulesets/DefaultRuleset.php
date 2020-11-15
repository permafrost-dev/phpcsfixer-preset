<?php

namespace Permafrost\PhpCsFixerRules\Rulesets;

class DefaultRuleset implements RuleSet
{
    public function allowRisky(): bool
    {
        return false;
    }

    public function name(): string
    {
        return 'default';
    }

    public function rules(): array
    {
        return [
            'psr0' => false,
            '@PSR2' => true,
            '@Symfony' => true,
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'blank_line_before_statement' => [
                'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
            ],
            'blank_line_after_namespace' => true,
            'binary_operator_spaces' => [
                'operators' => ['=>' => 'single_space'],
            ],
            'braces' => [
                'allow_single_line_closure' => true,
            ],
            'cast_spaces' => [
                'space' => 'none',
            ],
            'class_definition' => true,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'function_declaration' => [
                'closure_function_spacing' => 'none',
            ],
            'indentation_type' => true,
            'linebreak_after_opening_tag' => true,
            'line_ending' => true,
            'lowercase_constants' => false,
            'lowercase_keywords' => true,
            'method_argument_space' => [
                'ensure_fully_multiline' => true,
            ],
            'no_break_comment' => false,
            'no_closing_tag' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_inside_parenthesis' => true,
            'no_superfluous_phpdoc_tags' => false,
            'no_trailing_whitespace' => true,
            'no_trailing_whitespace_in_comment' => true,
            'no_unused_imports' => true,
            'not_operator_with_successor_space' => false,
            'ordered_imports' => [
                'sortAlgorithm' => 'alpha',
            ],
            'phpdoc_align' => [
                'align' => 'left',
            ],
            'phpdoc_no_alias_tag' => [
                'replacements' => ['type' => 'var'],
            ],
            'phpdoc_var_without_name' => false,
            'short_scalar_cast' => true,
            'single_blank_line_at_eof' => true,
            'single_class_element_per_statement' => [
                'elements' => ['property'],
            ],
            'single_import_per_statement' => true,
            'single_line_after_imports' => true,
            'single_trait_insert_per_statement' => false,
            'switch_case_semicolon_to_colon' => true,
            'switch_case_space' => true,
            'ternary_to_null_coalescing' => true,
            'trim_array_spaces' => false,
            'visibility_required' => true,
            'encoding' => true,
            'full_opening_tag' => true,
            'yoda_style' => false,
        ];
    }
}
