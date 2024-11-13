<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Services\Coverage\Models;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Models\Translation\Locale;
use PHPUnuhi\Services\Coverage\Models\CoverageLocale;
use PHPUnuhi\Services\Coverage\Models\CoverageTranslationSet;
use RuntimeException;

class CoverageSetTest extends TestCase
{
    /**
     * @var CoverageLocale[]
     */
    private array $locales;



    public function setUp(): void
    {
        $locale1 = new Locale('en', false, 'English', '');
        $locale1->addTranslation('title', 'Title Title', '');
        $locale1->addTranslation('text2', '', '');

        $locale2 = new Locale('de', false, 'German', '');
        $locale2->addTranslation('title', 'Title Title Car', '');
        $locale2->addTranslation('text2', '', '');

        $this->locales[] = new CoverageLocale($locale1);
        $this->locales[] = new CoverageLocale($locale2);
    }


    public function testName(): void
    {
        $coverage = new CoverageTranslationSet('Storefront', $this->locales);

        $value = $coverage->getName();

        $this->assertEquals('Storefront', $value);
    }


    public function testLocaleCoverages(): void
    {
        $coverage = new CoverageTranslationSet('Storefront', $this->locales);

        $value = $coverage->getLocaleCoverages();

        $this->assertCount(2, $value);
    }


    public function testLocaleCoverage(): void
    {
        $coverage = new CoverageTranslationSet('Storefront', $this->locales);

        $result = $coverage->getLocaleCoverage('de');

        $this->assertEquals(50, $result->getCoverage());
    }


    public function testLocaleCoverageNotFound(): void
    {
        $this->expectException(RuntimeException::class);

        $coverage = new CoverageTranslationSet('Storefront', $this->locales);

        $coverage->getLocaleCoverage('missing');
    }


    public function testCoverage(): void
    {
        $coverage = new CoverageTranslationSet('Storefront', $this->locales);

        $value = $coverage->getCoverage();

        $this->assertEquals(50, $value);
    }


    public function testCountAll(): void
    {
        $coverage = new CoverageTranslationSet('Storefront', $this->locales);

        $value = $coverage->getCountAll();

        $this->assertEquals(4, $value);
    }


    public function testCountTranslated(): void
    {
        $coverage = new CoverageTranslationSet('Storefront', $this->locales);

        $value = $coverage->getCountTranslated();

        $this->assertEquals(2, $value);
    }


    public function testWordCount(): void
    {
        $coverage = new CoverageTranslationSet('Storefront', $this->locales);

        $value = $coverage->getWordCount();

        $this->assertEquals(5, $value);
    }
}
