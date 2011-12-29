<?php
/**
 * @package   KVD.Services.Agiv.Crab
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be> 
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

/**
 * Class die een postkanton voorstelt zoals gekend door de Agiv CRAB webservice. 
 *
 * @package   KVD.Services.Agiv.Crab
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be> 
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Postkanton
{
    /**
     * id
     *
     * @var integer
     */
    protected $id;

    /**
     * code
     *
     * @var integer
     */
    protected $code;

    /**
     * __construct
     *
     * @param integer $id
     * @param integer $code
     * @return void
     */
    public function __construct( $id, $code )
    {
        $this->id = $id;
        $this->code = $code;
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
     * getCode
     *
     * @return integer
     */
    public function getCode( )
    {
        return $this->code;
    }
}
