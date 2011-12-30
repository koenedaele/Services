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
 * Class die een kadastraal perceel voorstelt. 
 * 
 * @package   KVD.Services.Agiv.CaPaKey
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be>
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Perceel
{
    /**
     * id 
     * 
     * @var string
     */
    protected $id;

    /**
     * sectie 
     * 
     * @var Sectie
     */
    protected $sectie;

    /**
     * capakey 
     * 
     * @var string
     */
    protected $capakey;

    /**
     * percid 
     * 
     * @var string
     */
    protected $percid;

    /**
     * capatype 
     * 
     * @var string
     */
    protected $capatype;

    /**
     * cashkey 
     * 
     * @var string
     */
    protected $cashkey;

    /**
     * centroid 
     * 
     * @var A\Centroid
     */
    protected $centroid;

    /**
     * boundingbox 
     * 
     * @var A\Centroid
     */
    protected $boundingbox;

    /**
     * gateway 
     * 
     * @var CaPaKeyGateway
     */
    protected $gateway;

    /**
     * @param string $id
     * @param Sectie $sectie
     * @param string $capakey
     * @param string $percid
     * @param string $capatype
     * @param string $cashkey
     * @param A\Centroid $centroid
     * @param A\BoundingBox $boundingbox
     */
    public function __construct( $id, Sectie $sectie, $capakey, $percid, 
                                 $capatype = null, $cashkey = null,
                                 A\Centroid $centroid = null, A\BoundingBox $boundingbox = null)
    {
        $this->id = $id;
        $this->sectie = $sectie;
        $this->capakey = $capakey;
        $this->percid = $percid;
        $this->capatype = $capatype;
        $this->cashkey = $cashkey;
        $this->centroid = $centroid;
        $this->boundingbox = $boundingbox;
        $this->splitsCaPaKey( );
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
        $perceel = $this->gateway->getPerceelByIdAndSectie( $this->id, $this->sectie );
        $this->centroid = $perceel->getCentroid( );
        $this->boundingbox = $perceel->getBoundingBox( );
        $this->capatype = $perceel->getCaPaType( );
        $this->cashkey = $perceel->getCashKey( );
    }

    /**
     * Splits de CaPaKey in zijn verschillende stukken. 
     * 
     * Methode de uit een CaPaKey het grondnummer, de cijfer- en de letter 
     * exponent haalt en het bisnummer.
     * @return void
     */
    protected function splitsCaPaKey( )
    {
        $regex = '#^[0-9]{5}[A_Z]{1}([0-9]{4})\/([0-9]{2})([A-Z\_]{1})([0-9]{3})$#';
        $matches = array( );
        preg_match( $regex, $this->capakey, $matches );
        $this->grondnummer = $matches[1];
        $this->bisnummer = $matches[2];
        $this->exponent = $matches[3];
        $this->macht = $matches[4];
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
     * getSectie 
     * 
     * @return Sectie
     */
    public function getSectie( )
    {
        return $this->sectie;
    }

    /**
     * getGrondnummer 
     * 
     * @return string
     */
    public function getGrondnummer( )
    {
        return $this->grondnummer;
    }

    /**
     * getExponent 
     * 
     * @return string
     */
    public function getExponent( )
    {
        return $this->exponent;
    }

    /**
     * getBisnummer 
     * 
     * @return string
     */
    public function getBisnummer( )
    {
        return $this->bisnummer;
    }

    /**
     * getMacht 
     * 
     * @return string
     */
    public function getMacht( )
    {
        return $this->macht;
    }

    /**
     * getCaPaKey 
     * 
     * @return string
     */
    public function getCaPaKey( )
    {
        return $this->capakey;
    }

    /**
     * getPercId 
     * 
     * @return string
     */
    public function getPercId( )
    {
        return $this->percid;
    }

    /**
     * getCaPaType 
     * 
     * @return string
     */
    public function getCaPaType( )
    {
        if ( $this->capatype === null ) {
            $this->doLazyLoad( );
        }
        return $this->capatype;
    }

    /**
     * getCaShKey 
     * 
     * @return string
     */
    public function getCaShKey( )
    {

        if ( $this->cashkey === null ) {
            $this->doLazyLoad( );
        }
        return $this->cashkey;
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
