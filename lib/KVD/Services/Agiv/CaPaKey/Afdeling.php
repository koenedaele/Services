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
 * Een kadastrale afdeling. 
 * 
 * @package   KVD.Services.Agiv.CaPaKey
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be>
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Afdeling
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
     * gemeente 
     * 
     * @var Gemeente
     */
    protected $gemeente;

    /**
     * centroid 
     * 
     * @var A\Centroid
     */
    protected $centroid;

    /**
     * box 
     * 
     * @var A\BoundingBox
     */
    protected $box;

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
     * @param Gemeente $gemeente
     * @param A\Centroid $centroid
     * @param A\BoundingBox $box
     * @return void
     */
    public function __construct( $id,
                                 $naam,
                                 Gemeente $gemeente,
                                 A\Centroid $centroid = null,
                                 A\BoundingBox $box = null )
    {
        $this->id = $id;
        $this->naam = $naam;
        $this->gemeente = $gemeente;
        $this->centroid = $centroid;
        $this->box = $box;
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
            throw new \LogicException (
                'Er is geen gateway ingesteld om extra info op te vragen.'
            );
        }
    }

    protected function doLazyLoad( )
    {
        $this->checkGateway( );
        $afdeling = $this->gateway->getAfdelingById( $this->id );
        $this->centroid = $afdeling->getCentroid( );
        $this->box = $afdeling->getBoundingBox( );
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
        return $this->naam;
    }

    /**
     * getGemeente 
     * 
     * @return Gemeente
     */
    public function getGemeente( )
    {
        return $this->gemeente;
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
        if ( $this->box === null ) {
            $this->doLazyLoad( );
        }
        return $this->box;
    }
}
?>
