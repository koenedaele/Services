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
 * Unit test voor Sectie.
 * 
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be> 
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class SectieTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->gemeente = new Gemeente( 39999, 'TestGemeente' );
        $this->centroid = new A\Centroid( 10,10 );
        $this->boundingbox = new A\BoundingBox( 0, 0, 20, 20 );
        $this->afdeling = new Afdeling( 39999, 'TestAfdeling', $this->gemeente );
        $this->sectie = new Sectie( 'A', $this->afdeling, $this->centroid, $this->boundingbox );
    }

    public function tearDown( )
    {
        $this->sectie = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 'A', $this->sectie->getId( ) );
        $this->assertSame( $this->afdeling, $this->sectie->getAfdeling( ) );
        $this->assertSame( $this->centroid, $this->sectie->getCentroid( ) );
        $this->assertSame( $this->boundingbox, $this->sectie->getBoundingBox( ) );
    }

    public function testLazyLoadCentroid( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getSectieByIdAndAfdeling') )
                        ->getMock( );
        $gateway->expects( $this->once( ) )
                ->method( 'getSectieByIdAndAfdeling' )
                ->with( 'A', $this->afdeling )
                ->will( $this->returnValue( $this->sectie ) );
        $sectie = new Sectie( 'A', $this->afdeling );
        $sectie->setGateway( $gateway );
        $this->assertEquals( 'A', $sectie->getId( ) );
        $this->assertSame( $this->afdeling, $sectie->getAfdeling( ) );
        $this->assertSame( $this->centroid, $sectie->getCentroid( ) );
    }

    public function testLazyLoadBoundingBox( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getSectieByIdAndAfdeling') )
                        ->getMock( );
        $gateway->expects( $this->once( ) )
                ->method( 'getSectieByIdAndAfdeling' )
                ->with( 'A', $this->afdeling )
                ->will( $this->returnValue( $this->sectie ) );
        $sectie = new Sectie( 'A', $this->afdeling );
        $sectie->setGateway( $gateway );
        $this->assertEquals( 'A', $sectie->getId( ) );
        $this->assertSame( $this->afdeling, $sectie->getAfdeling( ) );
        $this->assertSame( $this->boundingbox, $sectie->getBoundingBox( ) );
    }

     /**
     * @expectedException LogicException
     */
    public function testLazyLoadWithoutGateway( )
    {
        $sectie = new Sectie( 'A', $this->afdeling );
        $this->assertSame( $this->boundingbox, $sectie->getBoundingBox( ) );
    }


}
?>
