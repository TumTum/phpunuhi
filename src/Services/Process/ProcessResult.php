<?php

declare(strict_types=1);

namespace PHPUnuhi\Services\Process;

class ProcessResult
{
    private string $output;

    private string $errorOutput;

    /**
     * @var string[]
     */
    private $_cachedLines;



    public function __construct(string $output, string $errorOutput)
    {
        $this->output = trim($output);
        $this->errorOutput = trim($errorOutput);
    }

    public function isSuccess(): bool
    {
        return $this->errorOutput === '' || $this->errorOutput === '0';
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @return string[]
     */
    public function getOutputLines(): array
    {
        if ($this->_cachedLines === null) {

            # split into lines
            /** @var string[] $tmpArray */
            $tmpArray = (array)preg_split('/\r\n|\r|\n/', $this->output);

            $this->_cachedLines = $tmpArray;

            # now remove any empty lines
            $this->_cachedLines = array_filter($this->_cachedLines, function ($line): bool {
                return trim($line) !== '';
            });
        }

        return $this->_cachedLines;
    }

    public function getErrorOutput(): string
    {
        return $this->errorOutput;
    }
}
