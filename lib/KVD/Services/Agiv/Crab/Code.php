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
 * Abstract class die dient voor alle keuzelijsten aangeboden door de crab ws. 
 *
 * @package   KVD.Services.Agiv.Crab
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be>
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
abstract class Code
{
    /**
     * code
     *
     * @var string
     */
    protected $code;

    /**
     * naam
     *
     * @var string
     */
    protected $naam;

    /**
     * definitie
     *
     * @var string
     */
    protected $definitie;

    /**
     * __construct
     *
     * @param mixed $code
     * @param string $naam
     * @param string $definitie
     * @return void
     */
    public function __construct( $code, $naam, $definitie )
    {
        $this->code = $code;
        $this->naam = $naam;
        $this->definitie = $definitie;
    }

    /**
     * getCode
     *
     * @return mixed
     */
    public function getCode( )
    {
        return $this->code;
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
     * getDefinitie
     *
     * @return string
     */
    public function getDefinitie( )
    {
        return $this->definitie;
    }
}
?>
