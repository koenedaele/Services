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
 * Class die een wegobject voorstelt zoals gekend door de Agiv CRAB webservice. 
 * 
 * @package   KVD.Services.Agiv.Crab
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be> 
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Wegobject
{
    /**
     * id 
     * 
     * @var string
     */
    protected $id;

    /**
     * straat
     *
     * @var Straat
     */
    protected $straat;

    /**
     * aard
     *
     * @var mixed Integer of Wegobject
     */
    protected $aard;

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
     * @param integer       $string
     * @param String        $straat
     * @param integer       $aard
     * @param A\Centroid    $center
     * @param A\BoundingBox $box
     * @return void
     */
    public function __construct( $id, Straat $straat, $aard,
                                 A\Centroid $center = null,
                                 A\BoundingBox $box = null)
    {
        $this->id = $id;
        $this->straat = $straat;
        $this->aard = $aard;
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
        $wegobject = $this->gateway->getWegobjectById( $this->straat, $this->id );
        $this->centroid = $wegobject->getCentroid( );
        $this->boundingbox = $wegobject->getBoundingBox( );
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
     * getStraat
     *
     * @return Straat
     */
    public function getStraat( )
    {
        return $this->straat;
    }

    /**
     * getAard
     *
     * @return integer
     */
    public function getAard( )
    {
        return $this->aard;
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
