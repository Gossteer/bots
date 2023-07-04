<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Squiz\Sniffs\NamingConventions\ValidVariableNameSniff;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/migrations',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);

    $ecsConfig->rule(ValidVariableNameSniff::class);
    $ecsConfig->skip([
        ValidVariableNameSniff::class . '.PrivateNoUnderscore',
    ]);

    // SetList::SPACES без некоторых правил
    $ecsConfig->rules([
        \Symplify\CodingStandard\Fixer\Spacing\StandaloneLinePromotedPropertyFixer::class,
        \PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer::class,
        \PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer::class,
        \PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer::class,
        \PhpCsFixer\Fixer\ClassNotation\SingleTraitInsertPerStatementFixer::class,
        \PhpCsFixer\Fixer\FunctionNotation\FunctionTypehintSpaceFixer::class,
        \PhpCsFixer\Fixer\ClassNotation\NoBlankLinesAfterClassOpeningFixer::class,
        \PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer::class,
        \PhpCsFixer\Fixer\Phpdoc\PhpdocSingleLineVarSpacingFixer::class,
        \PhpCsFixer\Fixer\NamespaceNotation\NoLeadingNamespaceWhitespaceFixer::class,
        \PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer::class,
        \PhpCsFixer\Fixer\Whitespace\NoWhitespaceInBlankLineFixer::class,
        \PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer::class,
        \PhpCsFixer\Fixer\Semicolon\SpaceAfterSemicolonFixer::class,
        \PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer::class,
        \PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer::class,
        \PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\LanguageConstructSpacingSniff::class
    ]);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\CastNotation\CastSpacesFixer::class, ['space' => 'none']);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer::class, ['elements' => ['method' => 'one']]);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\Operator\ConcatSpaceFixer::class, ['spacing' => 'one']);
    $ecsConfig->ruleWithConfiguration(\PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SuperfluousWhitespaceSniff::class, ['ignoreBlankLines' => \false]);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer::class, ['operators' => ['=>' => 'single_space', '=' => 'single_space']]);

    $ecsConfig->sets([
        // run and fix, one by one
        SetList::PSR_12,
        SetList::DOCBLOCK,
        SetList::DOCTRINE_ANNOTATIONS,
        SetList::CLEAN_CODE,
        SetList::STRICT,
        SetList::ARRAY,
    ]);
};
