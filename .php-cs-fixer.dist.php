<?php

$finder = \PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

return (new \PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'concat_space' => false,
        'phpdoc_summary' => false,
        'yoda_style' => false,
        'return_type_declaration' => ['space_before' => 'one'],
        'global_namespace_import' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(false)
;
