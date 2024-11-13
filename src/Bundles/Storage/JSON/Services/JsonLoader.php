<?php

declare(strict_types=1);

namespace PHPUnuhi\Bundles\Storage\JSON\Services;

use Exception;
use PHPUnuhi\Bundles\Storage\StorageHierarchy;
use PHPUnuhi\Models\Translation\Locale;
use PHPUnuhi\Traits\ArrayTrait;

class JsonLoader
{
    use ArrayTrait;



    public function loadTranslations(Locale $locale, StorageHierarchy $hierarchy): void
    {
        $snippetJson = (string)file_get_contents($locale->getFilename());

        $foundTranslations = [];

        if ($snippetJson !== '' && $snippetJson !== '0') {
            $foundTranslations = json_decode($snippetJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error decoding JSON file: ' . json_last_error_msg());
            }

            if ($foundTranslations === false) {
                $foundTranslations = [];
            }
        }

        if ($foundTranslations === null) {
            $foundTranslations = [];
        }

        if ($hierarchy->isNestedStorage()) {
            $foundTranslationsFlat = $this->getFlatArray($foundTranslations, $hierarchy->getDelimiter());
        } else {
            $foundTranslationsFlat = $foundTranslations;
        }

        foreach ($foundTranslationsFlat as $key => $value) {
            $locale->addTranslation($key, $value, '');
        }

        // We start with one as a properly formatted JSON file will always have the first line as the opening bracket
        $locale->setLineNumbers(
            $this->getLineNumbers($foundTranslations, $hierarchy->getDelimiter(), '', 1, true)
        );
    }
}
