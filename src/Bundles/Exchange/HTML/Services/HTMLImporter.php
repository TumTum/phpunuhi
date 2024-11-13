<?php

declare(strict_types=1);

namespace PHPUnuhi\Bundles\Exchange\HTML\Services;

use PHPUnuhi\Bundles\Exchange\ImportEntry;
use PHPUnuhi\Bundles\Exchange\ImportResult;
use PHPUnuhi\Services\GroupName\GroupNameService;
use PHPUnuhi\Traits\StringTrait;
use SplFileObject;

class HTMLImporter
{
    use StringTrait;


    public function import(string $filename): ImportResult
    {
        $foundData = [];

        $groupNameService = new GroupNameService();

        foreach (new SplFileObject($filename) as $line) {
            if ($line === false) {
                $line = '';
            }

            $line = str_replace(PHP_EOL, '', $line);

            if (is_array($line)) {
                $line = '';
            }

            if (trim($line) === '') {
                continue;
            }

            $fullKeyWithLocale = explode('=', $line)[0];

            $localeID = '';

            $key = $groupNameService->getPropertyName($fullKeyWithLocale);
            $group = $groupNameService->getGroupID($fullKeyWithLocale);

            if ($this->stringDoesContain($key, '--')) {
                $localeID = explode('--', $key)[1];
                $key = explode('--', $key)[0];
            }

            $value = explode('=', $line)[1];

            $foundData[] = new ImportEntry(
                $localeID,
                $key,
                $group,
                $value
            );
        }

        return new ImportResult($foundData);
    }
}
