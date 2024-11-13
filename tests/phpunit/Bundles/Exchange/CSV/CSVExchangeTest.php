<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Bundles\Exchange\CSV;

use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnuhi\Bundles\Exchange\CSV\CSVExchange;
use PHPUnuhi\Exceptions\TranslationNotFoundException;
use PHPUnuhi\Models\Command\CommandOption;
use PHPUnuhi\Models\Configuration\CaseStyleSetting;
use PHPUnuhi\Models\Configuration\Filter;
use PHPUnuhi\Models\Configuration\Protection;
use PHPUnuhi\Models\Translation\Locale;
use PHPUnuhi\Models\Translation\TranslationSet;
use PHPUnuhi\Tests\Utils\Fakes\FakeCSVWriter;

class CSVExchangeTest extends TestCase
{
    private FakeCSVWriter $fakeWriter;


    private CSVExchange $csv;



    public function setUp(): void
    {
        $this->fakeWriter = new FakeCSVWriter();

        $this->csv = new CSVExchange($this->fakeWriter);
    }



    public function testGetName(): void
    {
        $this->assertEquals('csv', $this->csv->getName());
    }


    public function testPossibleOptions(): void
    {
        $expected = [
            new CommandOption('csv-delimiter', true),
        ];

        $this->assertEquals($expected, $this->csv->getOptions());
    }

    /**
     * @throws Exception
     */
    public function testSetOptionsWithMissingDelimiterUsesDefaultDelimiter(): void
    {
        $options = [
            'csv-delimiter' => ' '
        ];

        $this->csv->setOptionValues($options);

        $this->assertEquals(',', $this->csv->getCsvDelimiter());
    }

    /**
     * @throws Exception
     */
    public function testSetOptions(): void
    {
        $options = [
            'csv-delimiter' => 'A'
        ];

        $this->csv->setOptionValues($options);

        $this->assertEquals('A', $this->csv->getCsvDelimiter());
    }


    /**
     * This test verifies that we can correctly export a CSV.
     * The export does not consider groups
     *
     * @throws TranslationNotFoundException
     */
    public function testExportWithoutGroups(): void
    {
        $localesDE = new Locale('de-DE', false, '', '');
        $localesDE->addTranslation('btnCancel', 'Abbrechen', '');
        $localesDE->addTranslation('btnOK', 'OK', '');

        $localesEN = new Locale('en-GB', false, '', '');
        $localesEN->addTranslation('btnCancel', 'Cancel', '');


        $set = new TranslationSet(
            '',
            'json',
            new Protection(),
            [$localesDE, $localesEN],
            new Filter(),
            [],
            new CaseStyleSetting([], []),
            []
        );

        $this->csv->export($set, '', false);

        $expected = [
            [
                'Key',
                'de-DE',
                'en-GB',
            ],
            [
                'btnCancel',
                'Abbrechen',
                'Cancel',
            ],
            [
                'btnOK',
                'OK',
                '',
            ]
        ];

        $this->assertEquals($expected, $this->fakeWriter->getWrittenLines());
    }

    /**
     * This test verifies that we can correctly export a CSV.
     * The export considers groups
     *
     * @throws TranslationNotFoundException
     */
    public function testExportWithGroups(): void
    {
        $localesDE = new Locale('de-DE', false, '', '');
        $localesDE->addTranslation('title', 'T-Shirt', 'ProductA');
        $localesDE->addTranslation('size', 'Mittel', 'ProductA');
        $localesDE->addTranslation('title', 'Hose', 'ProductB');

        $localesEN = new Locale('en-GB', false, '', '');
        $localesEN->addTranslation('size', 'Medium', 'ProductA');


        $set = new TranslationSet(
            '',
            'json',
            new Protection(),
            [$localesDE, $localesEN],
            new Filter(),
            [],
            new CaseStyleSetting([], []),
            []
        );

        $this->csv->export($set, '', false);

        $expected = [
            [
                'Group',
                'Key',
                'de-DE',
                'en-GB',
            ],
            [
                'ProductA',
                'title',
                'T-Shirt',
                '',
            ],
            [
                'ProductA',
                'size',
                'Mittel',
                'Medium',
            ],
            [
                'ProductB',
                'title',
                'Hose',
                '',
            ]
        ];

        $this->assertEquals($expected, $this->fakeWriter->getWrittenLines());
    }


    /**
     * This test verifies that we can correctly export a CSV.
     * In this case we only export empty translations
     *
     * @throws TranslationNotFoundException
     */
    public function testExportOnlyEmpty(): void
    {
        $de = new Locale('de-DE', false, '', '');
        $en = new Locale('en-GB', false, '', '');

        # must not be exported
        $de->addTranslation('title', 'Titel', '');
        $en->addTranslation('title', 'Title', '');

        # should be exported because 1 translation is missing
        $de->addTranslation('size', '', '');
        $en->addTranslation('size', 'Medium', '');

        # should be exported because 1 translation is missing (vice-versa)
        $de->addTranslation('subtitle', 'Untertitel', '');
        $en->addTranslation('subtitle', '', '');


        $set = new TranslationSet(
            '',
            'json',
            new Protection(),
            [$de, $en],
            new Filter(),
            [],
            new CaseStyleSetting([], []),
            []
        );

        $this->csv->export($set, '', true);

        $expected = [
            [
                'Key',
                'de-DE',
                'en-GB',
            ],
            [
                'size',
                '',
                'Medium',
            ],
            [
                'subtitle',
                'Untertitel',
                '',
            ],
        ];

        $this->assertEquals($expected, $this->fakeWriter->getWrittenLines());
    }
}
