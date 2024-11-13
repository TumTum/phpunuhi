<?php

declare(strict_types=1);

namespace PHPUnuhi\Services\Coverage\Models;

use PHPUnuhi\Services\Coverage\Traits\CoverageDataTrait;
use RuntimeException;

class CoverageTranslationSet
{
    use CoverageDataTrait;

    private string $name;

    /**
     * @var CoverageLocale[]
     */
    private array $localeCoverages;


    /**
     * @param CoverageLocale[] $localeCoverages
     */
    public function __construct(string $name, array $localeCoverages)
    {
        $this->name = $name;
        $this->localeCoverages = $localeCoverages;

        $this->calculate();
    }



    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return CoverageLocale[]
     */
    public function getLocaleCoverages(): array
    {
        return $this->localeCoverages;
    }

    public function getLocaleCoverage(string $locale): CoverageLocale
    {
        foreach ($this->localeCoverages as $coverage) {
            if ($coverage->getLocaleName() === $locale) {
                return $coverage;
            }
        }

        throw new RuntimeException('Locale not found');
    }



    private function calculate(): void
    {
        $this->countAll = 0;
        $this->countTranslated = 0;
        $this->countWords = 0;

        foreach ($this->localeCoverages as $coverage) {
            $this->countTranslated += $coverage->getCountTranslated();
            $this->countAll += $coverage->getCountAll();
            $this->countWords += $coverage->getWordCount();
        }
    }
}
