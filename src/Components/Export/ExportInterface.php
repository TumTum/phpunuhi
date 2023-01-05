<?php

namespace PHPUnuhi\Export;

use PHPUnuhi\Models\Translation\TranslationSet;

interface ExportInterface
{

    /**
     * @param TranslationSet $set
     * @param string $outputDir
     * @return void
     */
    function export(TranslationSet $set, string $outputDir): void;

}