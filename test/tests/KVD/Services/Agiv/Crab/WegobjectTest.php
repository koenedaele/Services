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
 * Unit test voor Wegobject.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class WegobjectTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->centroid = new A\Centroid( 10,10 );
        $this->boundingbox = new A\BoundingBox( 0, 0, 20, 20 );

        $this->gemeenteNamen = array( 'nl' => 'TestGemeente' );
        $this->straatNamen = array ( 'nl' => 'Nieuwstraat',
                                     'fr' => 'Rue Neuve' );
        $this->gemeente = new Gemeente( 666, 39999,
                                        $this->gemeenteNamen,
                                        'nl');
        $this->straat = new Straat( 1, $this->gemeente, 'Nieuwstraat',
                                    $this->straatNamen, 'nl' );
        $this->wegobject = new WegObject( 123456789, $this->straat, 4,
                                          $this->centroid, $this->boundingbox );

    }

    public function tearDown( )
    {
        $this->gemeente = null;
        $this->centroid = null;
        $this->boundingbox = null;
        $this->straat = null;
        $this->wegobject = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 123456789, $this->wegobject->getId( ) );
        $this->assertSame( $this->straat, $this->wegobject->getStraat( ) );
        $this->assertEquals( 4, $this->wegobject->getAard( ) );
    }

    public function testLazyLoadCentroid( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\Crab\CrabGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getWegobjectById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getWegobjectById' )
                ->with( $this->straat, 123456789 )
                ->will( $this->returnValue( $this->wegobject ) );
        $wegobject = new Wegobject( 123456789, $this->straat, 4);
        $wegobject->setGateway( $gateway );
        $this->assertSame( $this->centroid, $wegobject->getCentroid( ) );
    }

    public function testLazyLoadBoundingBox( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\Crab\CrabGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getWegobjectById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getWegobjectById' )
                ->with( $this->straat, 123456789 )
                ->will( $this->returnValue( $this->wegobject ) );
        $wegobject = new Wegobject( 123456789, $this->straat, 4);
        $wegobject->setGateway( $gateway );
        $this->assertSame( $this->boundingbox, $wegobject->getBoundingBox( ) );
    }

    /**
     * @expectedException LogicException
     */
    public function testLazyLoadWithoutGateway( )
    {
        $wegobject = new Wegobject( 123456789, $this->straat, 4);
        $this->assertSame( $this->centroid, $wegobject->getBoundingBox( ) );
    }
}
?>
