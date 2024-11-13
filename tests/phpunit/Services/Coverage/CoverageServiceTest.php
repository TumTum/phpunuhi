<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Services\Coverage;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Models\Configuration\CaseStyleSetting;
use PHPUnuhi\Models\Configuration\Filter;
use PHPUnuhi\Models\Configuration\Protection;
use PHPUnuhi\Models\Translation\Locale;
use PHPUnuhi\Models\Translation\TranslationSet;
use PHPUnuhi\Services\Coverage\CoverageService;

class CoverageServiceTest extends TestCase
{
    /**
     * @var TranslationSet[]
     */
    private array $sets;



    public function setUp(): void
    {
        $locale1 = new Locale('en', false, 'English', '');
        $locale1->addTranslation('btnSave', 'Save', '');
        $locale1->addTranslation('btnCancel', '', '');

        $locale2 = new Locale('en', false, 'English', '');
        $locale2->addTranslation('btnSave', 'Save', '');
        $locale2->addTranslation('btnCancel', 'Cancel', '');
        $locale2->addTranslation('btnSubmit', '', '');

        $this->sets[] = new TranslationSet(
            'Storefront',
            'json',
            new Protection(),
            [$locale1],
            new Filter(),
            [],
            new CaseStyleSetting([], []),
            []
        );

        $this->sets[] = new TranslationSet(
            'Admin',
            'json',
            new Protection(),
            [$locale2],
            new Filter(),
            [],
            new CaseStyleSetting([], []),
            []
        );
    }



    public function testGetCoverage(): void
    {
        $service = new CoverageService();

        $coverage = $service->getCoverage($this->sets);

        $this->assertEquals(60, $coverage->getCoverage());
    }
}
