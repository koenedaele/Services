<?php
/**
 * @package   KVD.Services.Agiv.CaPaKey
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\CaPaKey;

use KVD\Services\Agiv as A;

/**
 * Deze class stelt een kadastrale sectie (A, B, ...) binnen een afdeling voor. 
 * 
 * @package   KVD.Services.Agiv.CaPaKey
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Sectie
{
    /**
     * id 
     * 
     * @var string
     */
    protected $id;

    /**
     * afdeling 
     * 
     * @var Afdeling
     */
    protected $afdeling;

    /**
     * centroid 
     * 
     * @var A\Centroid
     */
    protected $centroid;

    /**
     * boundingbox 
     * 
     * @var A\BoundingBox
     */
    protected $boundingbox;

    /**
     * gateway 
     * 
     * @var CaPaKeyGateway
     */
    protected $gateway;

    /**
     * __construct 
     * 
     * @param string $id 
     * @param Afdeling $afdeling 
     * @param A\Centroid $centroid 
     * @param A\BoundingBox $box 
     * @return void
     */
    public function __construct( $id, Afdeling $afdeling, A\Centroid $centroid = null, A\BoundingBox $box = null)
    {
        $this->id = $id;
        $this->afdeling = $afdeling;
        $this->centroid = $centroid;
        $this->boundingbox = $box;
    }

    /**
     * setGateway 
     * 
     * @param CaPaKeyGateway $gateway 
     * @return void
     */
    public function setGateway( CaPaKeyGateway $gateway )
    {
        $this->gateway = $gateway;
    }

    protected function checkGateway()
    {
        if ( !$this->gateway instanceof CaPaKeyGateway ) {
            throw new \LogicException ( 'Er is geen gateway ingesteld om extra info op te vragen.' );
        }
    }

    protected function doLazyLoad( )
    {
        $this->checkGateway( );
        $sectie = $this->gateway->getSectieByIdAndAfdeling( $this->id, $this->afdeling );
        $this->centroid = $sectie->getCentroid( );
        $this->boundingbox = $sectie->getBoundingBox( );
    }

    /**
     * getId 
     * 
     * @return string
     */
    public function getId( )
    {
        return $this->id;
    }

    /**
     * getAfdeling 
     * 
     * @return Afdeling
     */
    public function getAfdeling( )
    {
        return $this->afdeling;
    }

    /**
     * getCentroid 
     * 
     * @return A\Centroid
     */
    public function getCentroid( )
    {
        if ( $this->centroid === null ) {
            $this->doLazyLoad( );
        }
        return $this->centroid;
    }

    /**
     * getBoundingBox 
     * 
     * @return A\BoundingBox
     */
    public function getBoundingBox( )
    {
        if ( $this->boundingbox === null ) {
            $this->doLazyLoad( );
        }
        return $this->boundingbox;
    }
}
?>
