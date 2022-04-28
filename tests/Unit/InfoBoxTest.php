<?php declare(strict_types=1);


namespace App\Tests\Unit;

use App\Entity\InfoBox;
use PHPUnit\Framework\TestCase;

class InfoBoxTest extends TestCase
{
    /**
     * @test
     */
    public function shouldUseNoneTypeAsDefaultType(): void
    {
        $sut = new InfoBox();

        self::assertSame('none', $sut->getBoxType());
    }

    /**
     * @test
     */
    public function shouldBeDeactivatedWhenCreated(): void
    {
        $sut = new InfoBox();

        self::assertFalse($sut->getActive());
    }

    /**
     * @test
     * @testWith ["none"]
     *          ["info"]
     *          ["warning"]
     *          ["danger"]
     */
    public function shouldAcceptValidBoxType(string $boxType): void
    {
        $sut = new InfoBox();

        $sut->setBoxType($boxType);

        self::assertSame($boxType, $sut->getBoxType());
    }

    /**
     * @test
     * @testWith [" "]
     *          ["null"]
     *          ["1234"]
     */
    public function shouldRejectInvalidBoxType(string $boxType): void
    {
        self::expectException(\InvalidArgumentException::class);

        $sut = new InfoBox();

        $sut->setBoxType($boxType);
    }
}
