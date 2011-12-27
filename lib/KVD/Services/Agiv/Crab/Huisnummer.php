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
    public function __construct( $id, Straat $straat, $huisnummer )
    {
        $this->id = $id;
        $this->straat = $straat;
        $this->huisnummer = $huisnummer;
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
}
