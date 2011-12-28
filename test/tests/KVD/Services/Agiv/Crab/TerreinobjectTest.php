<?php
/**
 * @package    KVD.Services.Agiv.Crab
 * @subpackage Tests
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

use KVD\Services\Agiv as A;

/**
 * Unit test voor Terreinobject.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class TerreinobjectTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->centroid = new A\Centroid( 10,10 );
        $this->boundingbox = new A\BoundingBox( 0, 0, 20, 20 );

        $this->terreinobject = new Terreinobject(
                                '31013_D_0239_C_004_00', 1,
                                $this->centroid, $this->boundingbox );

    }

    public function tearDown( )
    {
        $this->centroid = null;
        $this->boundingbox = null;
        $this->terreinobject = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( '31013_D_0239_C_004_00', $this->terreinobject->getId( ) );
        $this->assertEquals( 1, $this->terreinobject->getAard( ) );
        $this->assertSame( $this->centroid, $this->terreinobject->getCentroid( ) );
        $this->assertSame( $this->boundingbox, $this->terreinobject->getBoundingBox( ) );
    }

    public function testLazyLoadCentroid( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\Crab\CrabGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getTerreinobjectById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getTerreinobjectById' )
                ->with( '31013_D_0239_C_004_00')
                ->will( $this->returnValue( $this->terreinobject ) );
        $terreinobject = new Terreinobject( '31013_D_0239_C_004_00', 1);
        $terreinobject->setGateway( $gateway );
        $this->assertSame( $this->centroid, $terreinobject->getCentroid( ) );
    }

    public function testLazyLoadBoundingBox( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\Crab\CrabGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getTerreinobjectById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getTerreinobjectById' )
                ->with( '31013_D_0239_C_004_00')
                ->will( $this->returnValue( $this->terreinobject ) );
        $terreinobject = new Terreinobject( '31013_D_0239_C_004_00', 1);
        $terreinobject->setGateway( $gateway );
        $this->assertSame( $this->boundingbox, $terreinobject->getBoundingBox( ) );
    }

    /**
     * @expectedException LogicException
     */
    public function testLazyLoadWithoutGateway( )
    {
        $terreinobject = new Terreinobject( '31013_D_0239_C_004_00', 1);
        $this->assertSame( $this->centroid, $terreinobject->getCentroid( ) );
    }
}
?>
