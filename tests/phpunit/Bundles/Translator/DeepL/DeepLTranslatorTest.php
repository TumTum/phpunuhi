<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Bundles\Translator\DeepL;

use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnuhi\Bundles\Translator\DeepL\DeeplTranslator;

class DeepLTranslatorTest extends TestCase
{
    public function testGetName(): void
    {
        $translator = new DeeplTranslator();

        $this->assertEquals('deepl', $translator->getName());
    }


    public function testGetOptions(): void
    {
        $translator = new DeeplTranslator();

        $foundOptions = $translator->getOptions();

        $this->assertEquals('deepl-key', $foundOptions[0]->getName());
        $this->assertEquals(true, $foundOptions[0]->hasValue());

        $this->assertEquals('deepl-formal', $foundOptions[1]->getName());
        $this->assertEquals(false, $foundOptions[1]->hasValue());
    }

    /**
     * @throws Exception
     */
    public function testSetOptionsWithMissingKeyThrowsException(): void
    {
        $this->expectException(Exception::class);

        $options = [
            'deepl-key' => ' '
        ];

        $translator = new DeeplTranslator();
        $translator->setOptionValues($options);
    }

    /**
     * @throws Exception
     */
    public function testSetOptions(): void
    {
        $options = [
            'deepl-key' => 'key-123',
            'deepl-formal' => true,
        ];

        $translator = new DeeplTranslator();
        $translator->setOptionValues($options);

        $this->assertEquals('key-123', $translator->getApiKey());
        $this->assertTrue($translator->isFormality());
    }



    public function testAllowedFormality(): void
    {
        $expected = [
            'de',
            'nl',
            'fr',
            'it',
            'pl',
            'ru',
            'es',
            'pt'
        ];

        $this->assertEquals($expected, DeeplTranslator::ALLOWED_FORMALITY);
    }
}
