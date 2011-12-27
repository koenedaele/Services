<?php
/**
 * @package    KVD.Services.Agiv.Crab
 * @subpackage Tests
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

/**
 * Unit test voor de Crab gateway.
 *
 * Deze unit test is niet afhankelijk van een werkende soapclient en kan dus 
 * volledig offline draaien.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpackage Tests
 * @since      0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class CaPaKeyGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function testListGemeentenByGewestId( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\Crab\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'ListGemeentenByGewestId') )
                       ->getMock( );
        $gateway = new CrabGateway( $client );

        $p = new \StdClass();
        $p->GewestId = 2;
        $p->SorteerVeld = 1;
        $pWrapper = new \SoapParam ( $p , "ListGemeentenByGewestId" );
        $gemeenten = array();
        $tg = new \StdClass( );
        $tg->GemeenteId = 666;
        $tg->NISGemeenteCode = 39999;
        $tg->GemeenteNaam = 'TestGemeente';
        $tg->TaalCodeGemeenteNaam = 'nl';
        $tg->TaalCode = 'nl';
        $gemeenten[] = $tg;
        $res = new \StdClass( );
        $res->ListGemeentenByGewestIdResult = new \StdClass( );
        $res->ListGemeentenByGewestIdResult->GemeenteItem = $gemeenten;
        $client->expects( $this->once( ) )
               ->method( 'ListGemeentenByGewestId' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $resultaat = $gateway->listGemeentenByGewestId(2, 1);
        $this->assertInternalType( 'array', $resultaat );
        $this->assertEquals( 1, count( $resultaat ) );
        $first = $resultaat[0];
        $this->assertInstanceOf( 'KVD\Services\Agiv\Crab\Gemeente', $first );
        $this->assertEquals( 666, $first->getId( ) );
        $this->assertEquals( 'nl', $first->getTaalCode( ) );
        $this->assertEquals( 'TestGemeente', $first->getNaam( ) );
    }
    /*
    public function testGetGemeenteById( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'GetAdmGemeenteByNiscode') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->Niscode = 39999; 
        $pWrapper = new \SoapParam ( $p , "GetAdmGemeenteByNiscode" );
        $gem = new \StdClass( );
        $gem->Niscode = 39999;
        $gem->AdmGemeentenaam = 'TestGemeente';
        $gem->CenterX = 10.0;
        $gem->CenterY = 10.0;
        $gem->MinimumX = 0.0;
        $gem->MinimumY = 0.0;
        $gem->MaximumX = 20.0;
        $gem->MaximumY = 20.0;
        $res = new \StdClass( );
        $res->GetAdmGemeenteByNiscodeResult = $gem;
        $client->expects( $this->once( ) )
               ->method( 'GetAdmGemeenteByNiscode' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $resultaat = $gateway->getGemeenteById( 39999 );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Gemeente', $resultaat );
        $this->assertEquals( 39999, $resultaat->getId( ) );
        $this->assertEquals( 'TestGemeente', $resultaat->getNaam( ) );
    }
    */

}
?>
