<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

/**
 * La configurazione rimane volutamente incompiuta, Rector viene usato solo manualmente in locale
 * e non tramite CI.
 *
 * Data la natura specifica del tool sono distribuiti dei file di conf. specicifici per il tipo di
 * refactoring o upgrade.
 *
 * Usare o aggiungere i file di configurazione presenti nella directory in tools/rector/config
 *
 *
 */


return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/core/booking/src',
    ]);

    $rectorConfig->autoloadPaths([__DIR__ . '/vendor/bin/.phpunit/phpunit/vendor/autoload.php']);
    //$rectorConfig->importNames();
    //$rectorConfig->importShortClasses();
    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/srcApp_KernelDevDebugContainer.xml');
    $rectorConfig->phpVersion(PhpVersion::PHP_74);


    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        /**
         * GENERAL
         */
        //SetList::CODE_QUALITY,

        /**
         * PHP
         */
        LevelSetList::UP_TO_PHP_74,

        /**
         * SYMFONY
         */
        SymfonySetList::SYMFONY_44,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        SymfonySetList::SYMFONY_STRICT,

        /**
         * DOCTRINE
         */
        DoctrineSetList::DOCTRINE_25,
        DoctrineSetList::DOCTRINE_COMMON_20,
        DoctrineSetList::DOCTRINE_DBAL_211,
        DoctrineSetList::DOCTRINE_ORM_29,
        //DoctrineSetList::DOCTRINE_CODE_QUALITY,

        /**
         * PHPUNIT
         */
        PHPUnitSetList::PHPUNIT_91,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::PHPUNIT_EXCEPTION,
        PHPUnitSetList::PHPUNIT_YIELD_DATA_PROVIDER,
    ]);
};
