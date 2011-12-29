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
 * Class die een huisnummer voorstelt zoals gekend door
 * de Agiv CRAB webservice.
 *
 * @package   KVD.Services.Agiv.Crab
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be>
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Huisnummer
{
    /**
     * id
     *
     * @var integer
     */
    protected $id;

    /**
     * straat
     *
     * @var Straat
     */
    protected $straat;

    /**
     * huisnummer
     *
     * @var string
     */
    protected $huisnummer;

    /**
     * terreinobjecten,
     *
     * @var array
     */
    protected $terreinobjecten;

    /**
     * postkanton
     *
     * @var Postkanton
     */
    protected $postkanton;

    /**
     * gateway
     *
     * @var CrabGateway
     */
    protected $gateway;

    /**
     * __construct
     *
     * @param mixed $id
     * @param Straat $straat
     * @param mixed $huisnummer
     * @return void
     */
    public function __construct( $id, Straat $straat = null, $huisnummer )
    {
        $this->id = $id;
        $this->straat = $straat;
        $this->huisnummer = $huisnummer;
        $this->terreinobjecten = null;
        $this->postkanton = null;
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
        if ( $this->straat === null ) {
            $this->checkGateway( );
            $hnr = $this->gateway->getHuisnummerById( $this->id );
            $this->straat = $hnr->getStraat( );
        }
        return $this->straat;
    }

    /**
     * getHuisnummer
     *
     * @return string
     */
    public function getHuisnummer( )
    {
        return $this->huisnummer;
    }

    /**
     * getTerreinobjecten
     *
     * @return array
     */
    public function getTerreinobjecten( )
    {
        if ( $this->terreinobjecten === null ) {
            $this->checkGateway( );
            $this->terreinobjecten = $this->gateway->listTerreinobjectenByHuisnummer( $this );
        }
    }

    /**
     * getPostkanton
     *
     * @return Postkanton
     */
    public function getPostkanton( )
    {
        if ( $this->postkanton === null ) {
            $this->checkGateway( );
            $this->postkanton = $this->gateway->getPostkantonByHuisnummer( $this );
        }
        return $this->postkanton;
    }
}
