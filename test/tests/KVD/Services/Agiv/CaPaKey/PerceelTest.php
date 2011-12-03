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
 * Unit test voor Perceel.
 * 
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be> 
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class PerceelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->gemeente = new Gemeente( 40613, 'Kruibeke' );
        $this->afdeling = new Afdeling( 46013, 'Kruibeke', $this->gemeente );
        $this->sectie = new Sectie( 'A', $this->afdeling );
        $this->centroid = new A\Centroid( 10,10 );
        $this->boundingbox = new A\BoundingBox( 8, 8, 12, 12 );
        $this->perceel = new Perceel( '1154/02C000', $this->sectie, 
                                      '40613A1154/02C000', '40613_A_1154_C_000_02',
                                      'capaty', 'cashkey',
                                      $this->centroid, $this->boundingbox );
    }

    public function tearDown( )
    {
        $this->perceel = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( '1154/02C000', $this->perceel->getId( ) );
        $this->assertEquals( '1154', $this->perceel->getGrondnummer( ) );
        $this->assertEquals( '02', $this->perceel->getBisnummer( ) );
        $this->assertEquals( 'C', $this->perceel->getExponent( ) );
        $this->assertEquals( '000', $this->perceel->getMacht( ) );
        $this->assertEquals( '40613A1154/02C000', $this->perceel->getCaPaKey( ) );
        $this->assertEquals( '40613_A_1154_C_000_02', $this->perceel->getPercId( ) );
        $this->assertEquals( 'capaty', $this->perceel->getCaPaType( ) );
        $this->assertEquals( 'cashkey', $this->perceel->getCaShKey( ) );
        $this->assertSame( $this->sectie, $this->perceel->getSectie( ) );
        $this->assertSame( $this->centroid, $this->perceel->getCentroid( ) );
        $this->assertSame( $this->boundingbox, $this->perceel->getBoundingBox( ) );
    }

    public function testSplitCaPaKeyWithoutExponent( )
    {
        $this->perceel = new Perceel( '1154/02C000', $this->sectie, 
                                      '40613A1154/02_000', '40613_A_1154___000_02',
                                      'capaty', 'cashkey',
                                      $this->centroid, $this->boundingbox );
        $this->assertEquals( '1154', $this->perceel->getGrondnummer( ) );
        $this->assertEquals( '02', $this->perceel->getBisnummer( ) );
        $this->assertEquals( '_', $this->perceel->getExponent( ) );
        $this->assertEquals( '000', $this->perceel->getMacht( ) );
    }

    public function testLazyLoadCentroid( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getPerceelByIdAndSectie') )
                        ->getMock( );
        $gateway->expects( $this->once( ) )
                ->method( 'getPerceelByIdAndSectie' )
                ->with( '1154/02C000', $this->sectie )
                ->will( $this->returnValue( $this->perceel ) );
        $perceel = new Perceel( '1154/02C000', $this->sectie,
                                '40613A1154/02C000', '40613_A_1154_C_000_02' );
        $perceel->setGateway( $gateway );
        $this->assertEquals( '1154/02C000', $perceel->getId( ) );
        $this->assertSame( $this->sectie, $perceel->getSectie( ) );
        $this->assertSame( $this->centroid, $perceel->getCentroid( ) );
    }

    public function testLazyLoadBoundingBox( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getPerceelByIdAndSectie') )
                        ->getMock( );
        $gateway->expects( $this->once( ) )
                ->method( 'getPerceelByIdAndSectie' )
                ->with( '1154/02C000', $this->sectie )
                ->will( $this->returnValue( $this->perceel ) );
        $perceel = new Perceel( '1154/02C000', $this->sectie,
                                '40613A1154/02C000', '40613_A_1154_C_000_02' );
        $perceel->setGateway( $gateway );
        $this->assertEquals( '1154/02C000', $perceel->getId( ) );
        $this->assertSame( $this->sectie, $perceel->getSectie( ) );
        $this->assertSame( $this->boundingbox, $perceel->getBoundingBox( ) );
    }

    public function testLazyLoadCapatype( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getPerceelByIdAndSectie') )
                        ->getMock( );
        $gateway->expects( $this->once( ) )
                ->method( 'getPerceelByIdAndSectie' )
                ->with( '1154/02C000', $this->sectie )
                ->will( $this->returnValue( $this->perceel ) );
        $perceel = new Perceel( '1154/02C000', $this->sectie,
                                '40613A1154/02C000', '40613_A_1154_C_000_02' );
        $perceel->setGateway( $gateway );
        $this->assertEquals( '1154/02C000', $perceel->getId( ) );
        $this->assertSame( $this->sectie, $perceel->getSectie( ) );
        $this->assertEquals( 'capaty', $perceel->getCaPaType( ) );
    }

    public function testLazyLoadCashkey( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getPerceelByIdAndSectie') )
                        ->getMock( );
        $gateway->expects( $this->once( ) )
                ->method( 'getPerceelByIdAndSectie' )
                ->with( '1154/02C000', $this->sectie )
                ->will( $this->returnValue( $this->perceel ) );
        $perceel = new Perceel( '1154/02C000', $this->sectie,
                                '40613A1154/02C000', '40613_A_1154_C_000_02' );
        $perceel->setGateway( $gateway );
        $this->assertEquals( '1154/02C000', $perceel->getId( ) );
        $this->assertSame( $this->sectie, $perceel->getSectie( ) );
        $this->assertEquals( 'cashkey', $perceel->getCaShKey( ) );
    }

     /**
     * @expectedException LogicException
     */
    public function testLazyLoadWithoutGateway( )
    {
        $perceel = new Perceel( '1154/02C000', $this->sectie,
                                '40613A1154/02C000', '40613_A_1154_C_000_02' );
        $perceel->getBoundingBox( );
    }
}
?>
