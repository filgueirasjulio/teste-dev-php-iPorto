<?php

use PhpCsFixer\Finder;

// definimos as pastas que serão excluídas da verificação
$finder = PhpCsFixer\Finder::create()
    ->exclude([
        'bootstrap/cache',
        'docker',
        'docs',
        'docsrc',
        'html-coverage',
        'node_modules',
        'public',
        'resources',
        'storage',
        'stubs',
        'vendor',
    ])
    ->notName([
        '_ide_helper.php',
        '_ide_helper_models.php',
        '.phpstorm.meta.php',
        'server.php',
    ])
    ->in(__DIR__);

// definimos a configuração
$config = new PhpCsFixer\Config();

return $config
    ->setFinder($finder) // configuramos o finder
    ->setRules([ // definimos as regras usadas
        '@Symfony:risky' => true,
        '@Symfony' => true,
        'no_superfluous_phpdoc_tags' => false, // evita que tags @param e @return sejam removidas de blocos PHPDoc,
        'single_line_comment_style' => false, // permite comentários # (region)
        'php_unit_method_casing' => false,
        'ordered_class_elements' => true,
        'no_space_around_double_colon' => false,
    ])
    ->setLineEnding("\n")
    ->setRiskyAllowed(true);
