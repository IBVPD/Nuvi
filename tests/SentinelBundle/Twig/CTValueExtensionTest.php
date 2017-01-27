<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 26/01/17
 * Time: 4:55 PM
 */

namespace NS\SentinelBundle\Tests\Twig;

use NS\SentinelBundle\Twig\CTValueExtension;

class CTValueExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $input
     * @param $output
     * @dataProvider getCTValues
     */
    public function testRenderCTValue($input,$output)
    {
        $extension = new CTValueExtension();
        $this->assertEquals($output,$extension->renderCTValue($input));
    }

    public function getCTValues()
    {
        return [
            ["-3.0","No CT Value"],
            ["-2.0","Negative"],
            ["-1.0","Undetermined"],
            ["100","100"],
            ["-400","-400"],
            ["Whatever","Whatever"],
        ];

    }
}
