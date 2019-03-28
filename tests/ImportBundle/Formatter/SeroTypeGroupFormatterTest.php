<?php

namespace NS\ImportBundle\Tests\Formatter;

use NS\ImportBundle\Formatter\SeroTypeGroupFormatter;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\NmSerogroup;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\PropertyAccess\PropertyPath;

class SeroTypeGroupFormatterTest extends TestCase
{
    /**
     * @param $data
     * @param $expected
     *
     * @dataProvider getDataValues
     */
    public function testSupports($data, $expected): void
    {
        $formatter = new SeroTypeGroupFormatter();
        self::assertEquals($expected, $formatter->supports($data));

    }

    public function getDataValues(): array
    {
        return [
            [new NmSerogroup(), true],
            [new SpnSerotype(), true],
            [new HiSerotype(), true],
            ['', false],
            [null, false],
            [new stdClass(), false],
        ];
    }

    /**
     * @param $data
     * @dataProvider getData
     */
    public function testNoSelection($data): void
    {
        $propertyPath = $this->createMock(PropertyPath::class);
        $formatter = new SeroTypeGroupFormatter();
        self::assertNull($formatter->format($data, $propertyPath));
    }

    public function getData(): array
    {
        return [
            [new NmSerogroup()],
            [new SpnSerotype()],
            [new HiSerotype()],
        ];
    }
}
