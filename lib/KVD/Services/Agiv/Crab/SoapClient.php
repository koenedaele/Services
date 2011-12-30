<?php
/**
 * @package   KVD.Services.Agiv.Crab
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

require_once( __DIR__ . '/../../../../../vendor/wse-php/soap-wsa.php' );
require_once( __DIR__ . '/../../../../../vendor/wse-php/soap-wsse.php' );

/**
 * Uitbreiding op de standaard Soap client om correcte security headers te 
 * kunnen sturen. 
 * 
 * @package   KVD.Services.Agiv.Crab
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class SoapClient extends \SoapClient
{

    /**
     * user 
     * 
     * @var string
     */
    protected $user = null;
    
    /**
     * password 
     * 
     * @var string
     */
    protected $password = null;

    /**
     * setAuthentication
     *  
     * @param string $user 
     * @param string $password 
     */
    public function setAuthentication ( $user, $password )
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * __doRequest 
     * 
     * @param string $request 
     * @param string $location 
     * @param string $saction 
     * @param integer $version 
     * @return string
     */
    public function __doRequest( $request, $location, $saction, $version, $one_way = 0 )
    {
        if ( is_null( $this->user ) || is_null( $this->password ) ) {
            throw new \LogicException ( 'U hebt geen authenticatie credentials opgegeven.' );
        }
        $dom = new \DOMDocument( '1.0' );
        $dom->loadXML( $request );

        $wsa = new \WSASoap($dom);
        $wsa->addAction( $saction );
        $wsa->addTo( $location );
        $wsa->addMessageID( );
        $wsa->addReplyTo( );

        $dom = $wsa->getDoc( );

        $wsse = new \WSSESoap( $dom );

        $wsse->addUserToken( $this->user, $this->password, true );

        $req = $wsse->saveXML( );

        return parent::__doRequest( $req, $location, $saction, $version, $one_way );

    }
}
?>
