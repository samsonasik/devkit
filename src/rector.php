<?php

use Rector\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector;
use Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use Rector\CodeQuality\Rector\For_\ForToForeachRector;
use Rector\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector;
use Rector\CodeQuality\Rector\FuncCall\AddPregQuoteDelimiterRector;
use Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\If_\ShortenElseIfRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector;
use Rector\CodeQuality\Rector\Ternary\UnnecessaryTernaryExpressionRector;
use Rector\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector;
use Rector\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector;
use Rector\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector;
use Rector\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Rector\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php73\Rector\FuncCall\StringifyStrNeedlesRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // Rule sets to apply
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(LevelSetList::UP_TO_PHP_73);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_SPECIFIC_METHOD);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_80);

    $parameters = $containerConfigurator->parameters();

    // The paths to refactor (can also be supplied with CLI arguments)
    $parameters->set(Option::PATHS, [
        __DIR__ . '/app',
        __DIR__ . '/tests',
    ]);

    // Do you need to include constants, class aliases, or a custom autoloader?
    $parameters->set(Option::BOOTSTRAP_FILES, [
        realpath(getcwd()) . '/vendor/codeigniter4/framework/system/Test/bootstrap.php',
    ]);

    // Set the target version for refactoring
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_73);

    // Auto-import fully qualified class names
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // Are there files or rules you need to skip?
    $parameters->set(Option::SKIP, [
        __DIR__ . '/app/Views',

        JsonThrowOnErrorRector::class,
        StringifyStrNeedlesRector::class,

        // Note: requires php 8
        RemoveUnusedPromotedPropertyRector::class,

        // Ignore tests that might make calls without a result
        RemoveEmptyMethodCallRector::class => [
            __DIR__ . '/tests',
        ],

        // May load view files directly when detecting classes
        StringClassNameToClassConstantRector::class,

        // May be uninitialized on purpose
        AddDefaultValueForUndefinedVariableRector::class,
    ]);

    // Additional rules to apply
    $services = $containerConfigurator->services();
    $services->set(SimplifyUselessVariableRector::class);
    $services->set(RemoveAlwaysElseRector::class);
    $services->set(CountArrayToEmptyArrayComparisonRector::class);
    $services->set(ForToForeachRector::class);
    $services->set(ChangeNestedForeachIfsToEarlyContinueRector::class);
    $services->set(ChangeIfElseValueAssignToEarlyReturnRector::class);
    $services->set(SimplifyStrposLowerRector::class);
    $services->set(CombineIfRector::class);
    $services->set(SimplifyIfReturnBoolRector::class);
    $services->set(InlineIfToExplicitIfRector::class);
    $services->set(PreparedValueToEarlyReturnRector::class);
    $services->set(ShortenElseIfRector::class);
    $services->set(SimplifyIfElseToTernaryRector::class);
    $services->set(UnusedForeachValueToArrayKeysRector::class);
    $services->set(ChangeArrayPushToArrayAssignRector::class);
    $services->set(UnnecessaryTernaryExpressionRector::class);
    $services->set(AddPregQuoteDelimiterRector::class);
    $services->set(SimplifyRegexPatternRector::class);
    $services->set(FuncGetArgsToVariadicParamRector::class);
    $services->set(MakeInheritedMethodVisibilitySameAsParentRector::class);
    $services->set(SimplifyEmptyArrayCheckRector::class);
};
