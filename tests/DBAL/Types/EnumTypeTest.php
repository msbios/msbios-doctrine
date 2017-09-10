<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBiosTest\Doctrine\DBAL\Types;

use MSBios\Doctrine\DBAL\Types\EnumType;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumTypeTest
 * @package MSBiosTest\Doctrine\DBAL\Types
 */
class EnumTypeTest extends TestCase
{
    /**
     *
     */
    public function testGetAutoloaderConfig()
    {
        // /** @var \PHPUnit_Framework_MockObject_MockObject $mockObject */
        // $mockObject = $this->getMockForAbstractClass(EnumType::class);
        // $mockObject->expects($this->any())
        //     ->method('getValues')
        //     ->will($this->returnValue(true));
        //
        // $this->assertTrue($mockObject->getValues());
    }
}
