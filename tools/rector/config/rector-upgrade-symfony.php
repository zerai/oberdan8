<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonyLevelSetList;
use Rector\Symfony\Set\SymfonySetList;

/**
 * Configurazione Rector per upgrade a symfony symfony 4.4
 *
 * copiare il file nella directory principale del progetto,
 * eseguire il comando:
 * vendor/bin/rector -c rector-upgrade-symfony.php process --dry-run
 */


return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/core/booking/src',
    ]);

    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/srcApp_KernelDevDebugContainer.xml');

    $rectorConfig->sets([

        SymfonyLevelSetList::UP_TO_SYMFONY_44,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);
};
