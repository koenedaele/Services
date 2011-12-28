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
 * Unit test voor Huisnummer.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class HuisnummerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->gemeenteNamen = array( 'nl' => 'TestGemeente' );
        $this->straatNamen = array ( 'nl' => 'Nieuwstraat',
                                     'fr' => 'Rue Neuve' );
        $this->gemeente = new Gemeente( 666, 39999,
                                        $this->gemeenteNamen,
                                        'nl');
        $this->straat = new Straat( 1, $this->gemeente, 'Nieuwstraat',
                                    $this->straatNamen, 'nl' );
        $this->huisnummer = new Huisnummer( 887821, $this->straat, '68' );
    }

    public function tearDown( )
    {
        $this->gemeente = null;
        $this->straatNamen = null;
        $this->gemeenteNamen = null;
        $this->huisnummer = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 887821, $this->huisnummer->getId( ) );
        $this->assertSame( $this->straat, $this->huisnummer->getStraat( ) );
        $this->assertEquals( '68', $this->huisnummer->getHuisnummer( ) );
    }

    public function testLazyLoad( )
    {
        $gateway = $this->getMockBuilder('KVD\Services\Agiv\Crab\CrabGateway')
                        ->disableOriginalConstructor( )
                        ->setMethods( array( 'getHuisnummerById') )
                        ->getMock( );
        $gateway->expects( $this->exactly( 1 ) )
                ->method( 'getHuisnummerById' )
                ->with( 887821 )
                ->will( $this->returnValue( $this->huisnummer ) );
        $huisnummer = new Huisnummer( 887821, null, '68');
        $huisnummer->setGateway( $gateway );
        $this->assertSame( $this->straat, $huisnummer->getStraat( ) );
    }


}
?>
