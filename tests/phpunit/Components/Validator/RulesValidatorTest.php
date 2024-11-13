<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Components\Validator;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Bundles\Storage\JSON\JsonStorage;
use PHPUnuhi\Components\Validator\DuplicateContent\DuplicateContent;
use PHPUnuhi\Components\Validator\RulesValidator;
use PHPUnuhi\Models\Configuration\Rule;
use PHPUnuhi\Models\Configuration\Rules;
use PHPUnuhi\Models\Translation\Locale;
use PHPUnuhi\Tests\Utils\Traits\TranslationSetBuilderTrait;

class RulesValidatorTest extends TestCase
{
    use TranslationSetBuilderTrait;


    public function testTypeIdentifier(): void
    {
        $validator = new RulesValidator();

        $this->assertEquals('RULE', $validator->getTypeIdentifier());
    }


    public function testValidationWithoutTranslationsIsOkay(): void
    {
        $storage = new JsonStorage();

        $locale = new Locale('de', false, '', '');

        $set = $this->buildTranslationSet(
            [
                $locale
            ],
            [
                new Rule(Rules::NESTING_DEPTH, 2),
            ]
        );

        $validator = new RulesValidator();
        $result = $validator->validate($set, $storage);

        $this->assertEquals(true, $result->isValid());
    }

    /**
     * @return array<mixed>
     */
    public function ruleValidationDataProvider(): array
    {
        return [
            [Rules::NESTING_DEPTH, 2],
            [Rules::KEY_LENGTH, 5],
            [Rules::DISALLOWED_TEXT, 'Cancel'],
            [Rules::DUPLICATE_CONTENT, [new DuplicateContent('*', false)]],
        ];
    }

    /**
     * @dataProvider ruleValidationDataProvider
     * @param mixed $ruleValue
     */
    public function testRulesCorrectlyValidate(string $ruleName, $ruleValue): void
    {
        $storage = new JsonStorage();

        $locale = new Locale('de', false, '', '');
        $locale->addTranslation('card.overview.btnCancel', 'Cancel', '');
        $locale->addTranslation('btnCancel', 'Cancel', '');
        $locale->addTranslation('btnCancelEmpty', '', '');

        $set = $this->buildTranslationSet(
            [
                $locale
            ],
            [
                new Rule($ruleName, $ruleValue),
            ]
        );


        $validator = new RulesValidator();
        $result = $validator->validate($set, $storage);

        $this->assertEquals(false, $result->isValid());
    }
}
