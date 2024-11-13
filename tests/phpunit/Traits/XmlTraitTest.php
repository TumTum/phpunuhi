<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Traits;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Traits\XmlTrait;
use SimpleXMLElement;

class XmlTraitTest extends TestCase
{
    use XmlTrait;



    public function testHasAttributeFalse(): void
    {
        $xmlNode = new SimpleXMLElement('<root attr1="value1" attr2="value2"/>');

        $hasAttribute = $this->hasAttribute('fqp', $xmlNode);

        $this->assertEquals(false, $hasAttribute);
    }


    public function testHasAttributeFalseNoAttributes(): void
    {
        $xmlNode = new SimpleXMLElement('<root/>');

        $hasAttribute = $this->hasAttribute('fqp', $xmlNode);

        $this->assertEquals(false, $hasAttribute);
    }


    public function testHasAttributeTrue(): void
    {
        $xmlNode = new SimpleXMLElement('<root attr1="value1" attr2="value2"/>');

        $hasAttribute = $this->hasAttribute('attr2', $xmlNode);

        $this->assertEquals(true, $hasAttribute);
    }


    public function testGetAttribute(): void
    {
        $xmlNode = new SimpleXMLElement('<root attr1="value1" attr2="value2"/>');

        $attr = $this->getAttribute('attr1', $xmlNode);

        $this->assertEquals('attr1', $attr->getName());
        $this->assertEquals('value1', $attr->getValue());
    }


    public function testGetAttributeNotFoundLeadsToEmpty(): void
    {
        $xmlNode = new SimpleXMLElement('<root attr1="value1" attr2="value2"/>');

        $attr = $this->getAttribute('notExisting', $xmlNode);

        $this->assertEquals('notExisting', $attr->getName());
        $this->assertEquals('', $attr->getValue());
    }


    public function testGetAttributes(): void
    {
        $xmlNode = new SimpleXMLElement('<root attr1="value1" attr2="value2"/>');

        $attributes = $this->getAttributes($xmlNode);

        $this->assertCount(2, $attributes);

        $this->assertEquals('attr1', $attributes[0]->getName());
        $this->assertEquals('value1', $attributes[0]->getValue());

        $this->assertEquals('attr2', $attributes[1]->getName());
        $this->assertEquals('value2', $attributes[1]->getValue());
    }
}
