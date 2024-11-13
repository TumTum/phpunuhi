<?php

declare(strict_types=1);

namespace PHPUnuhi\Configuration\Services;

use PHPUnuhi\Bundles\Storage\StorageFactory;
use PHPUnuhi\Models\Translation\Locale;

class AutoCreateTranslationFile
{
    /**
     * @var CommandPrompt
     */
    private $commandPrompt;

    public function __construct(CommandPrompt $commandPrompt)
    {
        $this->commandPrompt = $commandPrompt;
    }

    /**
     * @param Locale $locale
     * @return bool
     */
    public function ensureExists(Locale $locale): bool
    {
        if (file_exists($locale->getFilename())) {
            return true;
        }

        $question =
            "Not found: <comment>{$locale->getFilename()}</comment>" . PHP_EOL .
            ' Should be generated?';

        if ($this->commandPrompt->askYesNoQuestion($question) === false) {
            return false;
        }

        return $this->createFile($locale->getFilename());
    }

    /**
     * @param $filename
     * @return bool
     */
    private function createFile(string $filename): bool
    {
        $basedir = dirname($filename);

        if (is_dir($basedir) === false) {
            mkdir($basedir, 0755, true);
        };

        $content = StorageFactory::getInstance()->getStorageFileTemplate($filename);

        return file_put_contents($filename, $content) !== false;
    }
}
