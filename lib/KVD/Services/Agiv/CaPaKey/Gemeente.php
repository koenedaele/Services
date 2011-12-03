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
 * Class die een gemeente voorstelt. 
 * 
 * @package   KVD.Services.Agiv.CaPaKey
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be> 
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Gemeente
{
    /**
     * id 
     * 
     * @var integer
     */
    protected $id;

    /**
     * naam 
     * 
     * @var string
     */
    protected $naam;

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
     * @var Gateway
     */
    protected $gateway;

    /**
     * __construct 
     * 
     * @param integer $id 
     * @param string $naam 
     * @param A\Centroid $center 
     * @param A\BoundingBox $box 
     * @return void
     */
    public function __construct( $id, $naam = null, A\Centroid $center = null, A\BoundingBox $box = null)
    {
        $this->id = $id;
        $this->naam = $naam;
        $this->centroid = $center;
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

    private function checkGateway()
    {
        if ( !$this->gateway instanceof CaPaKeyGateway ) {
            throw new \LogicException ( 'Er is geen gateway ingesteld om extra info op te vragen.' );
        }
    }

    protected function doLazyLoad( )
    {
        $this->checkGateway( );
        $gemeente = $this->gateway->getGemeenteById( $this->id );
        $this->naam = $gemeente->getNaam( ); 
        $this->centroid = $gemeente->getCentroid( );
        $this->boundingbox = $gemeente->getBoundingBox( );
    }

    /**
     * getId 
     * 
     * @return integer
     */
    public function getId( )
    {
        return $this->id;
    }

    /**
     * getNaam 
     * 
     * @return string
     */
    public function getNaam( )
    {
        if ( $this->naam === null ) {
            $this->doLazyLoad( );
        }
        return $this->naam;
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
