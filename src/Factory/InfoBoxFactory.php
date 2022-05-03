<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\InfoBox;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static InfoBox|Proxy createOne(array $attributes = [])
 * @method static InfoBox[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static InfoBox|Proxy find($criteria)
 * @method static InfoBox|Proxy findOrCreate(array $attributes)
 * @method static InfoBox|Proxy first(string $sortedField = 'id')
 * @method static InfoBox|Proxy last(string $sortedField = 'id')
 * @method static InfoBox|Proxy random(array $attributes = [])
 * @method static InfoBox|Proxy randomOrCreate(array $attributes = [])
 * @method static InfoBox[]|Proxy[] all()
 * @method static InfoBox[]|Proxy[] findBy(array $attributes)
 * @method static InfoBox[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static InfoBox[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method InfoBox|Proxy create($attributes = [])
 */
final class InfoBoxFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
            'title' => 'test infobox',
            //self::faker()->text(),
            'body' => self::faker()->text(),
            'active' => 1,
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(InfoBox $infoBox) {})
        ;
    }

    protected static function getClass(): string
    {
        return InfoBox::class;
    }
}
