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

/**
 * Unit test voor de SoapClient.
 * 
 * @package    KVD.Services.Agiv.CaPaKey
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be> 
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class SoapClientTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        if ( CAPAKEY_RUN_INTEGRATION_TESTS === false  ) {
            $this->markTestSkipped( );
        }
        $wsdl = 'http://ws.agiv.be/capakeyws/nodataset.asmx?WSDL';
        $this->client = new SoapClient( $wsdl,
                                        array( 'trace' => 1,
                                               'exceptions' => 1 ) );
        $this->client->setAuthentication( CAPAKEY_USER, CAPAKEY_PASSWORD );

    }

    public function tearDown( )
    {
        $this->client = null;
    }

    public function testListAdmGemeenten( )
    {
        $this->client->ListAdmGemeenten( 1 );
    }

    /**
     * @expectedException LogicException
     */
    public function testMustBeAuthenticated( )
    {
        $wsdl = 'http://ws.agiv.be/capakeyws/nodataset.asmx?WSDL';
        $this->client = new SoapClient( $wsdl,
                                        array( 'trace' => 1,
                                               'exceptions' => 1 ) );
        $this->client->ListAdmGemeenten( 1 );
    }

    /**
     * @expectedException SoapFault
     */
    public function testAuthenticationIsValid( )
    {
        $wsdl = 'http://ws.agiv.be/capakeyws/nodataset.asmx?WSDL';
        $this->client = new SoapClient( $wsdl,
                                        array( 'trace' => 1,
                                               'exceptions' => 1 ) );
        $this->client->setAuthentication( CAPAKEY_USER, 'invalid' );
        $this->client->ListAdmGemeenten( 1 );
    }
}
?>
