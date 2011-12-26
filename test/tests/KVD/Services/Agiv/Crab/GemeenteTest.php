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
 * Unit test voor Gemeente.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class GemeenteTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->centroid = new A\Centroid( 10,10 );
        $this->boundingbox = new A\BoundingBox( 0, 0, 20, 20 );
        $this->gemeente = new Gemeente( 666, 39999,
                                        'TestGemeente', 'nl', 
                                        'nl', null, 
                                        $this->centroid, 
                                        $this->boundingbox );
    }

    public function tearDown( )
    {
        $this->gemeente = null;
        $this->centroid = null;
        $this->boundingbox = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 666, $this->gemeente->getId( ) );
        $this->assertEquals( 39999, $this->gemeente->getNisCode( ) );
        $this->assertEquals( 'TestGemeente', $this->gemeente->getNaam( ) );
        $this->assertSame( $this->centroid, $this->gemeente->getCentroid( ) );
        $this->assertSame( $this->boundingbox, $this->gemeente->getBoundingBox( ) );
    }

    public function testLazyLoadCentroid( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\Crab\CrabGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getGemeenteById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getGemeenteById' )
                ->with( 666 )
                ->will( $this->returnValue( $this->gemeente ) );
        $gemeente = new Gemeente( 666, 39999, 'TestGemeente', 'nl', 'nl', null );
        $gemeente->setGateway( $gateway );
        $this->assertSame( $this->centroid, $gemeente->getCentroid( ) );
    }

    public function testLazyLoadBoundingBox( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\Crab\CrabGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getGemeenteById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getGemeenteById' )
                ->with( 666 )
                ->will( $this->returnValue( $this->gemeente ) );
        $gemeente = new Gemeente( 666, 39999, 'TestGemeente', 'nl', 'nl', null );
        $gemeente->setGateway( $gateway );
        $this->assertSame( $this->boundingbox, $gemeente->getBoundingBox( ) );
    }

    /**
     * @expectedException LogicException
     */
    public function testLazyLoadWithoutGateway( )
    {
        $gemeente = new Gemeente( 666, 39999, 'TestGemeente', 'nl', 'nl', null );
        $gemeente->getCentroid( );
    }
}
?>
