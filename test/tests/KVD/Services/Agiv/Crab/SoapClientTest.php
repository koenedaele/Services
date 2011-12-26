<?php
/**
 * @package    KVD.Services.Agiv.Crab
 * @subpackage Tests
 * @version    $Id$
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be> 
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

/**
 * Unit test voor de SoapClient.
 * 
 * @package    KVD.Services.Agiv.Crab
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
        if ( CRAB_RUN_INTEGRATION_TESTS === false  ) {
            $this->markTestSkipped( );
        }
        $wsdl = 'http://ws.agiv.be/crabws/nodataset.asmx?WSDL';
        $this->client = new SoapClient( $wsdl,
                                        array( 'trace' => 1,
                                               'exceptions' => 1 ) );
        $this->client->setAuthentication( CRAB_USER, CRAB_PASSWORD );

    }

    public function tearDown( )
    {
        $this->client = null;
    }

    public function testListGemeenten( )
    {
        $this->client->ListGemeentenByGewestId( 2 );
    }

    /**
     * @expectedException LogicException
     */
    public function testMustBeAuthenticated( )
    {
        $wsdl = 'http://ws.agiv.be/crabws/nodataset.asmx?WSDL';
        $this->client = new SoapClient( $wsdl,
                                        array( 'trace' => 1,
                                               'exceptions' => 1 ) );
        $this->client->ListGemeentenByGewestId( 2 );
    }

    /**
     * @expectedException SoapFault
     */
    public function testAuthenticationIsValid( )
    {
        $wsdl = 'http://ws.agiv.be/crabws/nodataset.asmx?WSDL';
        $this->client = new SoapClient( $wsdl,
                                        array( 'trace' => 1,
                                               'exceptions' => 1 ) );
        $this->client->setAuthentication( CRAB_USER, 'invalid' );
        $this->client->ListGemeentenByGewestId( 2 );
    }
}
?>
