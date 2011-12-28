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
 * Class die een straat voorstelt zoals gekend door de Agiv CRAB webservice. 
 * 
 * @package   KVD.Services.Agiv.Crab
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be> 
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Straat
{
    /**
     * id 
     * 
     * @var integer
     */
    protected $id;

    /**
     * gemeente
     *
     * @var Gemeente
     */
    protected $gemeente;

    /**
     * De naam van de straat in de eerste taal, zonder het eventuele 
     * achtervoegsel dat de naam uniek maakt.
     *
     * @var string
     */
    protected $label;

    /**
     * namen
     *
     * @var array Een array waarin elke sleutel een taal is en elke waarde de 
     *            naam van de gemeente.
     */
    protected $namen;

    /**
     * Code die aangeeft welke de eerste taal is voor deze straat.
     *
     * @var string
     */
    protected $taalCode;

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
     * @param integer       $id
     * @param Gemeente      $gemeente
     * @param string        $label
     * @param array         $namen
     * @param string        $taalCode
     * @return void
     */
    public function __construct( $id, Gemeente $gemeente, $label,
                                 array $namen, $taalCode )
    {
        $this->id = $id;
        $this->gemeente = $gemeente;
        $this->label = $label;
        $this->namen = $namen;
        $this->taalCode = $taalCode;
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
     * getGemeente
     *
     * @return Gemeente
     */
    public function getGemeente( )
    {
        return $this->gemeente;
    }

    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel( )
    {
        return $this->label;
    }

    /**
     * getNaam
     *
     * @return string
     */
    public function getNaam( $taal = 'nl' )
    {
        if ( isset( $this->namen[$taal] ) ) {
            return $this->namen[$taal];
        } else {
            return $this->namen['nl'];
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
     * getHuisnummers
     *
     * @return array
     */
    public function getHuisnummers( )
    {
        if ( $this->huisnummers === null ) {
            $this->checkGateway( );
            $this->huisnummers = $this->gateway
                                      ->listHuisnummersByStraat( $this );
        }
        return $this->huisnummers;
    }

}
?>
