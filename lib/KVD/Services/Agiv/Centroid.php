<?php
/**
 * @package   KVD.Services.Agiv
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv;

/**
 * Object dat een centroïde van een geografisch object (gemeente, afdeling, 
 * sectie, peceel) voorstelt. 
 * 
 * @package   KVD.Services.Agiv
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Centroid
{
    /**
     * x 
     * 
     * @var float
     */
    protected $x;

    /**
     * y 
     * 
     * @var float
     */
    protected $y;

    /**
     * __construct 
     * 
     * @param float $x 
     * @param float $y 
     * @return void
     */
    public function __construct( $x, $y )
    {
        $this->x = (float) $x;
        $this->y = (float) $y;
    }

    /**
     * getX 
     * 
     * @return float
     */
    public function getX( )
    {
        return $this->x;
    }

    /**
     * getY 
     * 
     * @return float
     */
    public function getY( )
    {
        return $this->y;
    }

    /**
     * getSrid 
     * 
     * @return integer
     */
    public function getSrid( )
    {
        return 31370;
    }

    /**
     * Zet de centroïde om naar een array.
     *
     * Geeft een array terug met de volgende sleutels:
     * <ul>
     * <li><strong>x</strong> De x-waarde van het punt.</li>
     * <li><strong>y</strong> De y-waarde van het punt.</li>
     * </ul>
     * 
     * @return array
     */
    public function toArray( )
    {
        return array( 'x' => $this->x,
                      'y' => $this->y );
    }

}
?>
