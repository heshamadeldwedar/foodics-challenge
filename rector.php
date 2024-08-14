<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;


return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withPhpSets(php83: true)
    ->withCache()
    ->withMemoryLimit('-1')
    ->withRules([
        TypedPropertyFromStrictConstructorRector::class
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true
    );
