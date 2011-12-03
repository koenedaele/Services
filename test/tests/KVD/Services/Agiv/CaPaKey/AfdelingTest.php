<?php
/**
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpackage Tests
 * @version    $Id$
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be> 
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\CaPaKey;

use KVD\Services\Agiv as A;

/**
 * Unit test voor Afdeling.
 * 
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class AfdelingTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->gemeente = new Gemeente( 39999, 'TestGemeente' );
        $this->centroid = new A\Centroid( 10,10 );
        $this->boundingbox = new A\BoundingBox( 0, 0, 20, 20 );
        $this->afdeling = new Afdeling( 39999, 'TestAfdeling', $this->gemeente, $this->centroid, $this->boundingbox );
    }

    public function tearDown( )
    {
        $this->afdeling = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 39999, $this->afdeling->getId( ) );
        $this->assertEquals( 'TestAfdeling', $this->afdeling->getNaam( ) );
        $this->assertSame( $this->gemeente, $this->afdeling->getGemeente( ) );
        $this->assertSame( $this->centroid, $this->afdeling->getCentroid( ) );
        $this->assertSame( $this->boundingbox, $this->afdeling->getBoundingBox( ) );
    }

    public function testLazyLoadCentroid( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getAfdelingById') )
                        ->getMock( );
        $gateway->expects( $this->once( ) )
                ->method( 'getAfdelingById' )
                ->with( 39999 )
                ->will( $this->returnValue( $this->afdeling ) );
        $afdeling = new Afdeling( 39999, 'TestAfdeling', $this->gemeente );
        $afdeling->setGateway( $gateway );
        $this->assertEquals( 39999, $afdeling->getId( ) );
        $this->assertEquals( 'TestAfdeling', $afdeling->getNaam( ) );
        $this->assertSame( $this->gemeente, $afdeling->getGemeente( ) );
        $this->assertSame( $this->centroid, $afdeling->getCentroid( ) );
    }

    public function testLazyLoadBoundingBox( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\CaPaKeyGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getAfdelingById') )
                        ->getMock( );
        $gateway->expects( $this->once( ) )
                ->method( 'getAfdelingById' )
                ->with( 39999 )
                ->will( $this->returnValue( $this->afdeling ) );
        $afdeling = new Afdeling( 39999, 'TestAfdeling', $this->gemeente );
        $afdeling->setGateway( $gateway );
        $this->assertEquals( 39999, $afdeling->getId( ) );
        $this->assertEquals( 'TestAfdeling', $afdeling->getNaam( ) );
        $this->assertSame( $this->gemeente, $afdeling->getGemeente( ) );
        $this->assertSame( $this->boundingbox, $afdeling->getBoundingBox( ) );
    }

    /**
     * @expectedException LogicException
     */
    public function testLazyLoadWithoutGateway( )
    {
        $afdeling = new Afdeling( 39999, 'TestAfdeling', $this->gemeente );
        $this->assertSame( $this->boundingbox, $afdeling->getBoundingBox( ) );
    }
}
?>
