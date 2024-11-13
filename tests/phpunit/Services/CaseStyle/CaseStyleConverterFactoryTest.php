<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Services\CaseStyle;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Components\Validator\CaseStyle\Exception\CaseStyleNotFoundException;
use PHPUnuhi\Services\CaseStyle\CaseStyleConverterFactory;
use PHPUnuhi\Services\CaseStyle\UpperCaseConverter;

class CaseStyleConverterFactoryTest extends TestCase
{
    /**
     * @throws CaseStyleNotFoundException
     */
    public function testGetConverter(): void
    {
        $factory = new CaseStyleConverterFactory();
        $converter = $factory->fromIdentifier('upper');

        $this->assertInstanceOf(UpperCaseConverter::class, $converter);
    }

    /**
     * @throws CaseStyleNotFoundException
     */
    public function testUnknownConverterThrowsException(): void
    {
        $this->expectException(CaseStyleNotFoundException::class);

        $factory = new CaseStyleConverterFactory();
        $factory->fromIdentifier('unknown');
    }
}
