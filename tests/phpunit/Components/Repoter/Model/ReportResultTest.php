<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Components\Repoter\Model;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Components\Reporter\Model\ReportResult;
use PHPUnuhi\Components\Reporter\Model\ReportSetResult;
use PHPUnuhi\Components\Reporter\Model\ReportTestResult;

class ReportResultTest extends TestCase
{
    private ReportResult $result;



    public function setUp(): void
    {
        $suite1 = new ReportSetResult('test');
        $suite1->addTestResult(
            new ReportTestResult(
                '',
                '',
                '',
                55,
                '',
                '',
                true
            )
        );

        $suite2 = new ReportSetResult('test2');

        $suite2->addTestResult(
            new ReportTestResult(
                '',
                '',
                '',
                55,
                '',
                '',
                true
            )
        );

        $suite2->addTestResult(
            new ReportTestResult(
                '',
                '',
                '',
                55,
                '',
                '',
                false
            )
        );

        $this->result = new ReportResult();
        $this->result->addTranslationSet($suite1);
        $this->result->addTranslationSet($suite2);
    }


    public function testGetSuites(): void
    {
        $this->assertCount(2, $this->result->getSuites());
    }


    public function testGetCount(): void
    {
        $this->assertEquals(3, $this->result->getTestCount());
    }


    public function testFailureCount(): void
    {
        $this->assertEquals(1, $this->result->getFailureCount());
    }
}
