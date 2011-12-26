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
     * naam 
     * 
     * @var string
     */
    protected $naam;

    /**
     * taalCodeGemeenteNaam
     *
     * @var string
     */
    protected $taalCodeGemeenteNaam;

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
     * @param integer $id
     * @param integer $niscode
     * @param string $naam
     * @param string $taalCodeGemeenteNaam
     * @param string $taalCode
     * @param string $taalCodeTweedeTaal
     * @param A\Centroid $center
     * @param A\BoundingBox $box
     * @return void
     */
    public function __construct( $id, $niscode,
                                 $naam, $taalCodeGemeenteNaam,
                                 $taalCode, $taalCodeTweedeTaal,
                                 A\Centroid $center = null,
                                 A\BoundingBox $box = null)
    {
        $this->id = $id;
        $this->niscode = $niscode;
        $this->naam = $naam;
        $this->taalCodeGemeenteNaam = $taalCodeGemeenteNaam;
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
            throw new \LogicException ( 'Er is geen gateway ingesteld om extra info op te vragen.' );
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
    public function getNaam( )
    {
        return $this->naam;
    }

    /**
     * getTaalCodeGemeenteNaam
     *
     * @return string
     */
    public function getTaalCodeGemeenteNaam( )
    {
        return $this->taalCodeGemeenteNaam;
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
