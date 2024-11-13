<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Components\Validator\Rules;

use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnuhi\Bundles\Storage\INI\IniStorage;
use PHPUnuhi\Components\Validator\Rules\EmptyContentRule;
use PHPUnuhi\Models\Configuration\CaseStyleSetting;
use PHPUnuhi\Models\Configuration\Filter;
use PHPUnuhi\Models\Configuration\Protection;
use PHPUnuhi\Models\Translation\Locale;
use PHPUnuhi\Models\Translation\TranslationSet;

class EmptyContentRuleTest extends TestCase
{
    public function getRuleIdentifier(): void
    {
        $validator = new EmptyContentRule();

        $this->assertEquals('EMPTY_CONTENT', $validator->getRuleIdentifier());
    }

    /**
     * @throws Exception
     */
    public function testEmptyContentIsAlwaysTrue(): void
    {
        $localeDE = new Locale('de-DE', false, '', '');
        $localeDE->addTranslation('btn-Cancel1', 'Abbrechen', 'group1');
        $localeDE->addTranslation('btn-Cancel2', '', 'group1');

        $set = $this->buildSet([$localeDE]);

        $storageSingleLevel = new IniStorage();

        $validator = new EmptyContentRule();
        $result = $validator->validate($set, $storageSingleLevel);

        $this->assertTrue($result->isValid());
    }

    /**
     * @param Locale[] $locales
     */
    private function buildSet(array $locales): TranslationSet
    {
        return new TranslationSet(
            '',
            'json',
            new Protection(),
            $locales,
            new Filter(),
            [],
            new CaseStyleSetting([], []),
            []
        );
    }
}
