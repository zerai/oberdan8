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

        self::assertSame(false, $sut->getActive());
    }

    /**
     * @test
     * @dataProvider validBoxTypeProvider
     */
    public function shouldAcceptValidBoxType(string $value): void
    {
        $sut = new InfoBox();

        $sut->setBoxType($value);

        self::assertSame($value, $sut->getBoxType());
    }

    public function validBoxTypeProvider(): \Generator
    {
        return [
            yield 'none' => ['none'],
            yield 'info' => ['info'],
            yield 'warning' => ['warning'],
            yield 'danger' => ['danger'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidBoxTypeProvider
     */
    public function shouldRejectInvalidBoxType(string $value): void
    {
        self::expectException(\InvalidArgumentException::class);

        $sut = new InfoBox();

        $sut->setBoxType($value);
    }

    public function invalidBoxTypeProvider(): \Generator
    {
        return [
            yield 'empty' => [''],
            yield 'null as string' => ['null'],
            yield 'string number' => ['1233'],
        ];
    }
}
