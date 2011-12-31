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
 * Unit test voor de CaPaKeyGateway die effectief connecteert met de webservice..
 * 
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class CaPaKeyGatewayIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        if ( CAPAKEY_RUN_INTEGRATION_TESTS === false  ) {
            $this->markTestSkipped( );
        }
        $wsdl = 'http://ws.agiv.be/capakeyws/nodataset.asmx?WSDL';
        $this->client = new SoapClient( $wsdl,
                                        array( 'trace' => 1 ) );
        $this->client->setAuthentication( CAPAKEY_USER, CAPAKEY_PASSWORD );
        $this->gateway = new CaPaKeyGateway( $this->client );
    }

    public function tearDown( )
    {
        $this->client = null;
        $this->gateway = null;
    }

    public function testListGemeenten(  )
    {
        $gemeenten = $this->gateway->listGemeenten( );
        $this->assertInternalType( 'array', $gemeenten );
        $this->assertGreaterThan( 300, count( $gemeenten ) );
    }

    public function testGetGemeenteById( )
    {
        $gemeente = $this->gateway->getGemeenteById( 44021 );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Gemeente', $gemeente );
        $this->assertEquals( 44021, $gemeente->getId( ) );
        $this->assertEquals( 'Gent', $gemeente->getNaam( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\Centroid', $gemeente->getCentroid( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\BoundingBox', $gemeente->getBoundingBox( ) );
    }

    public function testListKadastraleAfdelingen(  )
    {
        $afdelingen = $this->gateway->listKadastraleAfdelingen( );
        $this->assertInternalType( 'array', $afdelingen );
        $this->assertGreaterThan( 300, count( $afdelingen ) );
    }

    public function testListKadastraleAfdelingenByGemeente( )
    {
        $gemeente = $this->gateway->getGemeenteById( 44021 );
        $afdelingen = $this->gateway->listKadastraleAfdelingenByGemeente( $gemeente );
        $this->assertInternalType( 'array', $afdelingen );
        $this->assertGreaterThan( 0, count( $afdelingen ) );
        $this->assertLessThan( 40, count( $afdelingen ) );
    }

    public function testGetKadastraleAfdelingBydId( )
    {
        $afdeling = $this->gateway->getKadastraleAfdelingById( 44021 );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Afdeling', $afdeling );
        $this->assertEquals( 44021, $afdeling->getId( ) );
        $this->assertInternalType( 'string', $afdeling->getNaam( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\Centroid', $afdeling->getCentroid( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\BoundingBox', $afdeling->getBoundingBox( ) );
    }

    public function testListSectiesByAfdeling( )
    {
        $afdeling = $this->gateway->getKadastraleAfdelingById( 44021 );
        $secties = $this->gateway->listSectiesByAfdeling( $afdeling );
        $this->assertInternalType( 'array', $secties );
        $this->assertGreaterThan( 0, count( $secties ) );
        $sectie = $secties[0];
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Sectie', $sectie );
        $this->assertSame( $afdeling, $sectie->getAfdeling( ) );
    }

    public function testGetSectieByIdAndAfdeling(  )
    {
        $afdeling = $this->gateway->getKadastraleAfdelingById( 44021 );
        $sectie = $this->gateway->getSectieByIdAndAfdeling( 'A', $afdeling );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Sectie', $sectie );
        $this->assertEquals( 'A', $sectie->getId( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Afdeling', $sectie->getAfdeling( ) );
        $this->assertEquals( 44021, $sectie->getAfdeling( )->getId( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\Centroid', $afdeling->getCentroid( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\BoundingBox', $afdeling->getBoundingBox( ) );
    }

    public function testListPercelenBySectie( )
    {
        $afdeling = $this->gateway->getKadastraleAfdelingById( 44021 );
        $sectie = $this->gateway->getSectieByIdAndAfdeling( 'A', $afdeling );
        $percelen = $this->gateway->listPercelenBySectie( $sectie );
        $this->assertInternalType( 'array', $percelen );
        $this->assertGreaterThan( 0, count( $percelen ) );
        $perceel = $percelen[0];
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Perceel', $perceel );
        $this->assertSame( $sectie, $perceel->getSectie( ) );
    }

    public function testGetPerceelByIdAndSectie( )
    {
        $afdeling = $this->gateway->getKadastraleAfdelingById( 44021 );
        $sectie = $this->gateway->getSectieByIdAndAfdeling( 'A', $afdeling );
        $percelen = $this->gateway->listPercelenBySectie( $sectie );
        $perc = $percelen[0];
        $perceel = $this->gateway->getPerceelByIdAndSectie( $perc->getId( ), $sectie);
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Perceel', $perceel );
        $this->assertSame( $sectie, $perceel->getSectie( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\Centroid', $afdeling->getCentroid( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\BoundingBox', $afdeling->getBoundingBox( ) );
    }

    public function testGetPerceelByCaPaKey( )
    {
        $afdeling = $this->gateway->getKadastraleAfdelingById( 44021 );
        $sectie = $this->gateway->getSectieByIdAndAfdeling( 'A', $afdeling );
        $percelen = $this->gateway->listPercelenBySectie( $sectie );
        $perc = $percelen[0];
        $perceel = $this->gateway->getPerceelByCaPaKey( $perc->getCaPaKey() );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Perceel', $perceel );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Sectie', $perceel->getSectie( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\Centroid', $afdeling->getCentroid( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\BoundingBox', $afdeling->getBoundingBox( ) );
    }

    public function testGetPerceelByPercid( )
    {
        $afdeling = $this->gateway->getKadastraleAfdelingById( 44021 );
        $sectie = $this->gateway->getSectieByIdAndAfdeling( 'A', $afdeling );
        $percelen = $this->gateway->listPercelenBySectie( $sectie );
        $perc = $percelen[0];
        $perceel = $this->gateway->getPerceelByPercid( $perc->getPERCID() );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Perceel', $perceel );
        $this->assertInstanceOf( 'KVD\Services\Agiv\CaPaKey\Sectie', $perceel->getSectie( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\Centroid', $afdeling->getCentroid( ) );
        $this->assertInstanceOf( 'KVD\Services\Agiv\BoundingBox', $afdeling->getBoundingBox( ) );
    }
}
?>
