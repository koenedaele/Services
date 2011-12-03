<?php
/**
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpackage Tests
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\CaPaKey;

/**
 * Unit test voor de CaPaKey gateway.
 *
 * Deze unit test is niet afhankelijk van een werkende soapclient en kan dus 
 * volledig offline draaien.
 * 
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpackage Tests
 * @since      0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class CaPaKeyGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function testListGemeenten( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'ListAdmGemeenten') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->SorteerVeld = 1; 
        $pWrapper = new \SoapParam ( $p , "ListAdmGemeenten" );
        $gemeenten = array();
        $tg = new \StdClass( );
        $tg->Niscode = 39999;
        $tg->AdmGemeentenaam = 'TestGemeente';
        $gemeenten[] = $tg;
        $res = new \StdClass( );
        $res->ListAdmGemeentenResult = new \StdClass( );
        $res->ListAdmGemeentenResult->AdmGemeenteItem = $gemeenten;
        $client->expects( $this->once( ) )
               ->method( 'ListAdmGemeenten' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $resultaat = $gateway->listGemeenten( 1 );
        $this->assertInternalType( 'array', $resultaat );
        $this->assertEquals( 1, count( $resultaat ) );
        $first = $resultaat[0];
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Gemeente', $first );
        $this->assertEquals( 39999, $first->getId( ) );
        $this->assertEquals( 'TestGemeente', $first->getNaam( ) );
    }

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


    public function testListKadastraleAfdelingen( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'ListKadAfdelingen') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->SorteerVeld = 1; 
        $pWrapper = new \SoapParam ( $p , "ListKadAfdelingen" );
        $afdelingen = array();
        $ta = new \StdClass( );
        $ta->Niscode = 39999;
        $ta->KadAfdelingcode = 39999;
        $ta->KadAfdelingnaam = 'TestGemeente AFD 1';
        $afdelingen[] = $ta;
        $res = new \StdClass( );
        $res->ListKadAfdelingenResult = new \StdClass( );
        $res->ListKadAfdelingenResult->KadAfdelingItem = $afdelingen;
        $client->expects( $this->once( ) )
               ->method( 'ListKadAfdelingen' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $resultaat = $gateway->listKadastraleAfdelingen( 1 );
        $this->assertInternalType( 'array', $resultaat );
        $this->assertEquals( 1, count( $resultaat ) );
        $first = $resultaat[0];
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Afdeling', $first );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Gemeente', $first->getGemeente( ) );
        $this->assertEquals( 39999, $first->getId( ) );
        $this->assertEquals( 39999, $first->getGemeente( )->getId( ) );
        $this->assertEquals( 'TestGemeente AFD 1', $first->getNaam( ) );
    }

    public function testListKadastraleAfdelingenByGemeente( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'ListKadAfdelingenByNiscode') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->Niscode = 39999;
        $p->SorteerVeld = 1; 
        $pWrapper = new \SoapParam ( $p , "ListKadAfdelingenByNiscode" );
        $afdelingen = array();
        $ta = new \StdClass( );
        $ta->KadAfdelingcode = 39999;
        $ta->KadAfdelingnaam = 'TestGemeente AFD 1';
        $afdelingen[] = $ta;
        $res = new \StdClass( );
        $res->ListKadAfdelingenByNiscodeResult = new \StdClass( );
        $res->ListKadAfdelingenByNiscodeResult->KadAfdelingItem = $afdelingen;
        $client->expects( $this->once( ) )
               ->method( 'ListKadAfdelingenByNiscode' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $gemeente = new Gemeente( 39999, 'TestGemeente' );
        $resultaat = $gateway->listKadastraleAfdelingenByGemeente( $gemeente, 1 );
        $this->assertInternalType( 'array', $resultaat );
        $this->assertEquals( 1, count( $resultaat ) );
        $first = $resultaat[0];
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Afdeling', $first );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Gemeente', $first->getGemeente( ) );
        $this->assertEquals( 39999, $first->getId( ) );
        $this->assertEquals( $gemeente->getId( ), $first->getGemeente( )->getId( ) );
        $this->assertEquals( $gemeente->getNaam( ), $first->getGemeente( )->getNaam( ) );
        $this->assertEquals( 'TestGemeente AFD 1', $first->getNaam( ) );
    }

    public function testGetKadastraleAfdelingById( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'GetKadAfdelingByKadAfdelingcode') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->KadAfdelingcode = 39999; 
        $pWrapper = new \SoapParam ( $p , "GetKadAfdelingByKadAfdelingcode" );
        $afd = new \StdClass( );
        $afd->Niscode = 39999;
        $afd->KadAfdelingcode = 39999;
        $afd->KadAfdelingnaam = 'TestGemeente AFD 1';
        $afd->CenterX = 10.0;
        $afd->CenterY = 10.0;
        $afd->MinimumX = 0.0;
        $afd->MinimumY = 0.0;
        $afd->MaximumX = 20.0;
        $afd->MaximumY = 20.0;
        $res = new \StdClass( );
        $res->GetKadAfdelingByKadAfdelingcodeResult = $afd;
        $client->expects( $this->once( ) )
               ->method( 'GetKadAfdelingByKadAfdelingcode' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $resultaat = $gateway->getKadastraleAfdelingById( 39999 );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Afdeling', $resultaat );
        $this->assertEquals( 39999, $resultaat->getId( ) );
        $this->assertEquals( 'TestGemeente AFD 1', $resultaat->getNaam( ) );
        $this->assertEquals( 39999, $resultaat->getGemeente( )->getId( ) );
    }

    public function testListSectiesByAfdeling( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'ListKadSectiesByKadAfdelingcode') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->KadAfdelingcode = 39999;
        $pWrapper = new \SoapParam ( $p , "ListKadSectiesByKadAfdelingcode" );
        $secties = array();
        $sa = new \StdClass( );
        $sa->KadSectiecode = 'A';
        $secties[] = $sa;
        $sb = new \StdClass( );
        $sb->KadSectiecode = 'B';
        $secties[] = $sb;
        $res = new \StdClass( );
        $res->ListKadSectiesByKadAfdelingcodeResult = new \StdClass( );
        $res->ListKadSectiesByKadAfdelingcodeResult->KadSectieItem = $secties;
        $client->expects( $this->once( ) )
               ->method( 'ListKadSectiesByKadAfdelingcode' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $gemeente = new Gemeente( 39999, 'TestGemeente' );
        $afdeling = new Afdeling( 39999, 'TestGemeente AFD 1', $gemeente );
        $resultaat = $gateway->listSectiesByAfdeling( $afdeling, 1 );
        $this->assertInternalType( 'array', $resultaat );
        $this->assertEquals( 2, count( $resultaat ) );
        $first = $resultaat[0];
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Sectie', $first );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Afdeling', $first->getAfdeling( ) );
        $this->assertEquals( 'A', $first->getId( ) );
        $this->assertEquals( $afdeling->getId( ), $first->getAfdeling( )->getId( ) );
        $this->assertEquals( $afdeling->getNaam( ), $first->getAfdeling( )->getNaam( ) );
    }

    public function testGetSectieByIdAndAfdeling( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'GetKadSectieByKadSectiecode') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->KadAfdelingcode = 39999;
        $p->KadSectieCode = 'A';
        $pWrapper = new \SoapParam ( $p , "GetKadSectieByKadSectiecode" );
        $afd = new \StdClass( );
        $afd->KadSectiecode = 'A';
        $afd->KadAfdelingcode = 39999;
        $afd->CenterX = 10.0;
        $afd->CenterY = 10.0;
        $afd->MinimumX = 0.0;
        $afd->MinimumY = 0.0;
        $afd->MaximumX = 20.0;
        $afd->MaximumY = 20.0;
        $res = new \StdClass( );
        $res->GetKadSectieByKadSectiecodeResult = $afd;
        $client->expects( $this->once( ) )
               ->method( 'GetKadSectieByKadSectiecode' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $gemeente = new Gemeente( 39999, 'TestGemeente' );
        $afdeling = new Afdeling( 39999, 'TestGemeente AFD 1', $gemeente );
        $resultaat = $gateway->getSectieByIdAndAfdeling( 'A', $afdeling );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Sectie', $resultaat );
        $this->assertEquals( 'A', $resultaat->getId( ) );
        $this->assertEquals( 'TestGemeente', $resultaat->getAfdeling()->getGemeente( )->getNaam( ) );
        $this->assertEquals( 39999, $resultaat->getAfdeling( )->getGemeente( )->getId( ) );
    }

    public function testListPercelenBySectie( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'ListKadPerceelsnummersByKadSectiecode') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->KadAfdelingcode = 39999;
        $p->KadSectieCode = 'A';
        $p->SorteerVeld = 1;
        $pWrapper = new \SoapParam ( $p , "ListKadPerceelsnummersByKadSectiecode" );
        $percelen = array();
        $pa = new \StdClass( );
        $pa->KadPerceelsnummer = '1154/02C000';
        $pa->CaPaKey = '39999A1154/02C000';
        $pa->PERCID = '39999_A_1154_C_000_02';
        $percelen[] = $pa;
        $res = new \StdClass( );
        $res->ListKadPerceelsnummersByKadSectiecodeResult = new \StdClass( );
        $res->ListKadPerceelsnummersByKadSectiecodeResult->KadPerceelsnummerItem = $percelen;
        $client->expects( $this->once( ) )
               ->method( 'ListKadPerceelsnummersByKadSectiecode' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $gemeente = new Gemeente( 39999, 'TestGemeente' );
        $afdeling = new Afdeling( 39999, 'TestGemeente AFD 1', $gemeente );
        $sectie = new Sectie ( 'A', $afdeling );
        $resultaat = $gateway->listPercelenBySectie( $sectie, 1 );
        $this->assertInternalType( 'array', $resultaat );
        $this->assertEquals( 1, count( $resultaat ) );
        $first = $resultaat[0];
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Perceel', $first );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Sectie', $first->getSectie( ) );
        $this->assertEquals( '1154/02C000', $first->getId( ) );
        $this->assertEquals( $sectie->getId( ), $first->getSectie( )->getId( ) );
    }

    public function testGetPerceelByIdAndSectie( )
    {
        $client = $this->getMockBuilder('KVD\Services\Agiv\CaPaKey\SoapClient')
                       ->disableOriginalConstructor( )
                       ->setMethods( array( 'GetKadPerceelsnummerByKadPerceelsnummer') )
                       ->getMock( );
        $gateway = new CaPaKeyGateway( $client );

        $p = new \StdClass();
        $p->KadAfdelingcode = 39999;
        $p->KadSectieCode = 'A';
        $p->KadPerceelsnummer = '1154/02C000';
        $pWrapper = new \SoapParam ( $p , "GetKadPerceelsnummerByKadPerceelsnummer" );
        $perc = new \StdClass( );
        $perc->KadSectiecode = 'A';
        $perc->KadAfdelingcode = 39999;
        $perc->KadPerceelsnummer = '1154/02C000';
        $perc->CaPaKey = '39999A1154/02C000';
        $perc->PERCID = '39999_A_1154_C_000_02';
        $perc->CaPaTy = 'CaPaTy';
        $perc->CaShKey = 'CaShKey';
        $perc->CenterX = 10.0;
        $perc->CenterY = 10.0;
        $perc->MinimumX = 0.0;
        $perc->MinimumY = 0.0;
        $perc->MaximumX = 20.0;
        $perc->MaximumY = 20.0;
        $res = new \StdClass( );
        $res->GetKadPerceelsnummerByKadPerceelsnummerResult = $perc;
        $client->expects( $this->once( ) )
               ->method( 'GetKadPerceelsnummerByKadPerceelsnummer' )
               ->with( $pWrapper )
               ->will( $this->returnValue( $res ) );
        $gemeente = new Gemeente( 39999, 'TestGemeente' );
        $afdeling = new Afdeling( 39999, 'TestGemeente AFD 1', $gemeente );
        $sectie = new Sectie ( 'A', $afdeling );
        $resultaat = $gateway->getPerceelByIdAndSectie( '1154/02C000', $sectie );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Perceel', $resultaat );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Sectie', $resultaat->getSectie( ) );
        $this->assertEquals( '1154/02C000', $resultaat->getId( ) );
        $this->assertEquals( $sectie->getId( ), $resultaat->getSectie( )->getId( ) );
    }

}

?>
