<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Models\Configuration;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Models\Configuration\Filter;

class FilterTest extends TestCase
{
    public function testHasFilters(): void
    {
        $filter = new Filter();
        $this->assertEquals(false, $filter->hasFilters());

        $filter = new Filter();
        $filter->addIncludeKey('custom_field*');
        $this->assertEquals(true, $filter->hasFilters());

        $filter = new Filter();
        $filter->addExcludeKey('custom_field*');
        $this->assertEquals(true, $filter->hasFilters());
    }


    public function testIncludeKey(): void
    {
        $filter = new Filter();
        $filter->addIncludeKey('custom_field*');

        $isAllowed = $filter->isKeyAllowed('custom_field');

        $this->assertEquals(true, $isAllowed);
    }


    public function testExcludeKey(): void
    {
        $filter = new Filter();
        $filter->addExcludeKey('custom_field*');

        $isAllowed = $filter->isKeyAllowed('custom_field');

        $this->assertEquals(false, $isAllowed);
    }


    public function testIncludeKeyWithWildcard(): void
    {
        $filter = new Filter();
        $filter->addIncludeKey('meta_*');

        $isAllowed = $filter->isKeyAllowed('meta_custom');

        $this->assertEquals(true, $isAllowed);
    }


    public function testExcludeKeyWithWildcard(): void
    {
        $filter = new Filter();
        $filter->addExcludeKey('meta_*');

        $isAllowed = $filter->isKeyAllowed('meta_custom');

        $this->assertEquals(false, $isAllowed);
    }

    /**
     * This test verifies, that once we have an include list,
     * the exclude-list will not be considered anymore.
     *
     */
    public function testIncludeRulesOverExclude(): void
    {
        $filter = new Filter();
        $filter->addIncludeKey('field_a');
        $filter->addExcludeKey('field_b');

        $isAllowedFieldA = $filter->isKeyAllowed('field_a');
        $isAllowedFieldB = $filter->isKeyAllowed('field_b');

        $this->assertEquals(true, $isAllowedFieldA);
        $this->assertEquals(false, $isAllowedFieldB);
    }


    public function testIncludeCanBeIncludedMultipleTimes(): void
    {
        $filter = new Filter();
        $filter->addIncludeKey('abc');
        $filter->addIncludeKey('abc');

        $isAllowed = $filter->isKeyAllowed('abc');

        $this->assertEquals(true, $isAllowed);
    }


    public function testIncludeCanBeExcludedMultipleTimes(): void
    {
        $filter = new Filter();
        $filter->addExcludeKey('abc');
        $filter->addExcludeKey('abc');

        $isAllowed = $filter->isKeyAllowed('abc');

        $this->assertEquals(false, $isAllowed);
    }
}
