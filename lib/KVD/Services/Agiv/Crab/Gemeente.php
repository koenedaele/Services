<?php
/**
 * @package   KVD.Services.Agiv.Crab
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be> 
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

use KVD\Services\Agiv as A;

/**
 * Class die een gemeente voorstelt zoals gekend door de Agiv CRAB webservice. 
 * 
 * @package   KVD.Services.Agiv.Crab
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
     * niscode
     *
     * @var integer
     */
    protected $niscode;

    /**
     * namen
     *
     * @var array Een array waarin elke sleutel een taal is en elke waarde de 
     *            naam van de gemeente.
     */
    protected $namen;

    /**
     * taalCode
     *
     * @var string
     */
    protected $taalCode;

    /**
     * taalCodeTweedeTaal
     *
     * @var string
     */
    protected $taalCodeTweedeTaal;

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
     * @param integer       $id
     * @param integer       $niscode
     * @param array         $namen
     * @param string        $taalCode
     * @param string        $taalCodeTweedeTaal
     * @param A\Centroid    $center
     * @param A\BoundingBox $box
     * @return void
     */
    public function __construct( $id, $niscode, array $namen,
                                 $taalCode, $taalCodeTweedeTaal = null,
                                 A\Centroid $center = null,
                                 A\BoundingBox $box = null)
    {
        $this->id = $id;
        $this->niscode = $niscode;
        $this->namen = $namen;
        $this->taalCode = $taalCode;
        $this->taalCodeTweedeTaal = $taalCodeTweedeTaal;
        $this->centroid = $center;
        $this->boundingbox = $box;
    }

    /**
     * setGateway 
     * 
     * @param CrabGateway $gateway 
     * @return void
     */
    public function setGateway( CrabGateway $gateway )
    {
        $this->gateway = $gateway;
    }

    private function checkGateway()
    {
        if ( !$this->gateway instanceof CrabGateway ) {
            throw new \LogicException (
                'Er is geen gateway ingesteld om extra info op te vragen.' 
            );
        }
    }

    protected function doLazyLoad( )
    {
        $this->checkGateway( );
        $gemeente = $this->gateway->getGemeenteById( $this->id );
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
     * getNisCode
     *
     * @return integer
     */
    public function getNisCode( )
    {
        return $this->niscode;
    }

    /**
     * getNaam
     *
     * @return string
     */
    public function getNaam( $taal = null )
    {
        if ( $taal === null ) {
            $taal = $this->taalCode;
        }
        if ( isset( $this->namen[$taal] ) ) {
            return $this->namen[$taal];
        } else {
            return $this->namen[$this->taalCode];
        }
    }

    /**
     * getTaalCode
     *
     * @return string
     */
    public function getTaalCode( )
    {
        return $this->taalCode;
    }

    /**
     * getTaalCodeTweedeTaal
     *
     * @return string
     */
    public function getTaalCodeTweedeTaal( )
    {
        return $this->taalCodeTweedeTaal;
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
