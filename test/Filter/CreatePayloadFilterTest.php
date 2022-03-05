<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLibTest\Filter;

use Dujche\MezzioHelperLib\Filter\CreatePayloadFilter;
use PHPUnit\Framework\TestCase;

class CreatePayloadFilterTest extends TestCase
{
    private CreatePayloadFilter $instance;

    public function setUp(): void
    {
        parent::setUp();
        $this->instance = new class () extends CreatePayloadFilter {
            public function __construct()
            {
                $this->addIntegerValidator('id');
                $this->addStringValidator('firstName', 50, 2);
                $this->addDateValidator('dateJoined');
            }
        };
    }

    public function testConstructor(): void
    {
        $this->assertTrue($this->instance->has('id'));
        $this->assertTrue($this->instance->has('firstName'));
        $this->assertTrue($this->instance->has('dateJoined'));
    }

    /**
     * @dataProvider isValidDataProvider
     */
    public function testIsValid(bool $expectedResult, array $samplePayload): void
    {
        $this->instance->setData($samplePayload);
        $this->assertSame($expectedResult, $this->instance->isValid());
    }

    public function isValidDataProvider(): array
    {
        return [
            'faulty payload' => [
                false,
                [
                    'foo' => 'bar'
                ]
            ],
            'incomplete payload' => [
                false,
                [
                    'id' => 100,
                    'firstName' => 'John',
                ]
            ],
            'firstName not long enough' => [
                false,
                [
                    'id' => 100,
                    'firstName' => 'J',
                    'dateJoined' => '2022-01-01'
                ]
            ],
            'valid payload' => [
                true,
                [
                    'id' => 100,
                    'firstName' => 'John',
                    'dateJoined' => '2022-01-01'
                ]
            ]
        ];
    }
}
