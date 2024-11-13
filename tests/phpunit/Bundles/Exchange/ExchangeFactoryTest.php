<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Bundles\Exchange;

use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnuhi\Bundles\Exchange\ExchangeFactory;
use PHPUnuhi\Exceptions\ConfigurationException;
use PHPUnuhi\Models\Command\CommandOption;
use PHPUnuhi\Tests\Utils\Fakes\FakeExchangeFormat;

class ExchangeFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        ExchangeFactory::getInstance()->resetExchangeFormats();
    }


    /**
     * @throws ConfigurationException
     */
    public function testGetCustomExchangeFormat(): void
    {
        $custom = new FakeExchangeFormat();
        ExchangeFactory::getInstance()->registerExchangeFormat($custom);

        $exchange = ExchangeFactory::getInstance()->getExchange('fake', []);

        $this->assertSame($custom, $exchange);
    }

    /**
     * @throws ConfigurationException
     */
    public function testDoubleRegistrationThrowsException(): void
    {
        $this->expectException(Exception::class);

        $custom = new FakeExchangeFormat();

        ExchangeFactory::getInstance()->registerExchangeFormat($custom);
        ExchangeFactory::getInstance()->registerExchangeFormat($custom);
    }

    /**
     * @throws ConfigurationException
     */
    public function testUnknownFormatThrowsException(): void
    {
        $this->expectException(Exception::class);

        ExchangeFactory::getInstance()->getExchange('unknown', []);
    }

    /**
     * @throws ConfigurationException
     */
    public function testEmptyFormatThrowsException(): void
    {
        $this->expectException(Exception::class);

        ExchangeFactory::getInstance()->getExchange('', []);
    }


    public function testGetAllOptions(): void
    {
        $options = ExchangeFactory::getInstance()->getAllOptions();

        $expected = [
            new CommandOption('csv-delimiter', true),
        ];

        $this->assertEquals($expected, $options);
    }
}
