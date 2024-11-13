<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Components\Validator\CaseStyle\Style;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Components\Validator\CaseStyle\Style\UpperCaseValidator;

class UpperCaseValidatorTest extends TestCase
{
    private UpperCaseValidator $validator;



    protected function setUp(): void
    {
        $this->validator = new UpperCaseValidator();
    }



    public function testIdentifier(): void
    {
        $this->assertEquals('upper', $this->validator->getIdentifier());
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            [true, 'TEXTSTYLE'],
            [true, 'TEXT-STYLE'],
            [true, 'TEXT-STYLE'],
            [false, 'text-style'],
            [false, 'text'],
            [false, 'textStyle'],
            [false, 'text_style'],
            [false, 'TextStyle'],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testIsValid(bool $expectedValid, string $text): void
    {
        $isValid = $this->validator->isValid($text);

        $this->assertEquals($expectedValid, $isValid);
    }
}
