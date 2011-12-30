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
 * Class die een terreinobject voorstelt zoals gekend door
 * de Agiv CRAB webservice.
 *
 * @package   KVD.Services.Agiv.Crab
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be>
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Terreinobject
{
    /**
     * id
     *
     * @var string
     */
    protected $id;

    /**
     * aard
     *
     * @var mixed Integer of AardTerreinobject
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
     * huisnummers
     *
     * @var array
     */
    protected $huisnummers;

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
     * @param integer       $aard
     * @param A\Centroid    $center
     * @param A\BoundingBox $box
     * @return void
     */
    public function __construct( $id, $aard,
                                 A\Centroid $center = null,
                                 A\BoundingBox $box = null)
    {
        $this->id = $id;
        $this->aard = $aard;
        $this->centroid = $center;
        $this->boundingbox = $box;
        $this->huisnummers = null;
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
        $terreinobject = $this->gateway->
                                getTerreinobjectById( $this->id );
        $this->centroid = $terreinobject->getCentroid( );
        $this->boundingbox = $terreinobject->getBoundingBox( );
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

    /**
     * getHuisnummers
     *
     * @return array
     */
    public function getHuisnummers( )
    {
        if ( $this->huisnummers === null ) {
            $this->checkGateway( );
            $this->huisnummers = $this->gateway
                                      ->listHuisnummersByIdentificatorTerreinobject( $this->id );
        }
    }
}
?>
