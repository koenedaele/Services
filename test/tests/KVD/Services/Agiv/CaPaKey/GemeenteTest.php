<?php
/**
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpackage Tests
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be> 
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\CaPaKey;

use KVD\Services\Agiv as A;

/**
 * Unit test voor Gemeente.
 * 
 * @package    KVD.Services.Agiv.CaPaKey
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
        $this->gemeente = new Gemeente( 39999, 'TestGemeente', $this->centroid, $this->boundingbox );
    }

    public function tearDown( )
    {
        $this->gemeente = null;
        $this->centroid = null;
        $this->boundingbox = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 39999, $this->gemeente->getId( ) );
        $this->assertEquals( 'TestGemeente', $this->gemeente->getNaam( ) );
        $this->assertSame( $this->centroid, $this->gemeente->getCentroid( ) );
        $this->assertSame( $this->boundingbox, $this->gemeente->getBoundingBox( ) );
    }

    public function testLazyLoadCentroid( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getGemeenteById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getGemeenteById' )
                ->with( 39999 )
                ->will( $this->returnValue( $this->gemeente ) );
        $gemeente = new Gemeente( 39999, 'TestGemeente' );
        $gemeente->setGateway( $gateway );
        $this->assertSame( $this->centroid, $gemeente->getCentroid( ) );
    }

    public function testLazyLoadBoundingBox( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getGemeenteById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getGemeenteById' )
                ->with( 39999 )
                ->will( $this->returnValue( $this->gemeente ) );
        $gemeente = new Gemeente( 39999, 'TestGemeente' );
        $gemeente->setGateway( $gateway );
        $this->assertSame( $this->boundingbox, $gemeente->getBoundingBox( ) );
    }

    public function testLazyLoadNaam( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getGemeenteById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getGemeenteById' )
                ->with( 39999 )
                ->will( $this->returnValue( $this->gemeente ) );
        $gemeente = new Gemeente( 39999 );
        $gemeente->setGateway( $gateway );
        $this->assertSame( 'TestGemeente', $gemeente->getNaam( ) );
    }

    /**
     * @expectedException LogicException
     */
    public function testLazyLoadWithoutGateway( )
    {
        $gemeente = new Gemeente( 39999, 'TestGemeente' );
        $gemeente->getCentroid( );
    }
}
?>
