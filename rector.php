<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
// use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
// use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
// use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
// use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
// use Rector\Php80\Rector\Class_\StringableForToStringRector;
// use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;
// use Rector\Php80\Rector\FunctionLike\MixedTypeRector;
// use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
// use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
// use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
// use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        // __DIR__ . '/bootstrap',
        // __DIR__ . '/config',
        // __DIR__ . '/lang',
        // __DIR__ . '/node_modules',
        // __DIR__ . '/public',
        // __DIR__ . '/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withPhpSets(php83: true)
    ->withCache()
    ->withMemoryLimit('-1')
    ->withSkip([
        // ClosureToArrowFunctionRector::class,
        // NullToStrictStringFuncCallArgRector::class,
        // MixedTypeRector::class,
        // ReturnNeverTypeRector::class,
        // RemoveExtraParametersRector::class,
        // ClassOnObjectRector::class,
        // StringClassNameToClassConstantRector::class,
        // StringableForToStringRector::class,
        // ClassPropertyAssignToConstructorPromotionRector::class,
        // ReadOnlyPropertyRector::class
    ])
    ->withRules([
        // AddVoidReturnTypeWhereNoReturnRector::class,
    ]);
