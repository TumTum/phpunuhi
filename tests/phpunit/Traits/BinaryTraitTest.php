<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Traits;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Traits\BinaryTrait;

class BinaryTraitTest extends TestCase
{
    use BinaryTrait;


    public function testBinaryToString(): void
    {
        $hex = $this->stringToBinary('0d1eeedd6d22436385580e2ff42431b9');
        $string = $this->binaryToString($hex);

        $this->assertEquals('0d1eeedd6d22436385580e2ff42431b9', $string);
    }


    public function testStringToBinary(): void
    {
        $binary = $this->stringToBinary('0d1eeedd6d22436385580e2ff42431b9');

        $hex = $this->binaryToString($binary);

        $this->assertEquals('0d1eeedd6d22436385580e2ff42431b9', $hex);
    }


    public function testStringToBinaryWithEmpty(): void
    {
        $hex = $this->stringToBinary('');

        $this->assertEquals('', $hex);
    }


    public function testIsBinaryTrue(): void
    {
        $binary = $this->stringToBinary('0d1eeedd6d22436385580e2ff42431b9');

        $isBinary = $this->isBinary($binary);

        $this->assertTrue($isBinary);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function nonBinaryStrings(): array
    {
        return [
            [''],
            ['Some Content'],
            ['Content with umlauts: äöüß'],
            ['Sóme Cotént'],
            ['这是一些内容']
        ];
    }

    /**
     * @dataProvider nonBinaryStrings
     *
     */
    public function testIsBinaryFalse(string $string): void
    {
        $isBinary = $this->isBinary($string);

        $this->assertFalse($isBinary);
    }
}
