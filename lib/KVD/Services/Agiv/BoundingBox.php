<?php
/**
 * @package   KVD.Services.Agiv
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv;

/**
 * Object dat een omslaande rechthoek van een geografisch object (
 * gemeente, afdeling, sectie, peceel) voorstelt. 
 * 
 * @package   KVD.Services.Agiv
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class BoundingBox
{
    /**
     * maxX 
     * 
     * @var float
     */
    protected $maxX;

    /**
     * maxY 
     * 
     * @var float
     */
    protected $maxY;

    /**
     * minX 
     * 
     * @var float
     */
    protected $minX;

    /**
     * minY 
     * 
     * @var float
     */
    protected $minY;

    /**
     * __construct 
     * 
     * @param float $maxX 
     * @param float $maxY 
     * @param float $minX 
     * @param float $minY 
     * @return void
     */
    public function __construct( $maxX, $maxY, $minX, $minY )
    {
        $this->maxX = $maxX;
        $this->maxY = $maxY;
        $this->minX = $minX;
        $this->minY = $minY;
    }

    /**
     * De maximale X-waarde van de rechthoek
     * 
     * @return float
     */
    public function getMaxX( )
    {
        return $this->maxX;
    }

    /**
     * De maximale Y-waarde van de rechthoek
     * 
     * @return float
     */
    public function getMaxY( )
    {
        return $this->maxY;
    }

    /**
     * De minimale X-waarde van de rechthoek
     * 
     * @return float
     */
    public function getMinX( )
    {
        return $this->minX;
    }

    /**
     * De minimale Y-waarde van de rechthoek
     * 
     * @return float
     */
    public function getMinY( )
    {
        return $this->minY;
    }

    /**
     * De SRID van de rechthoek
     *
     * De SRID is een identifier die aangeeft in welke projectie de 
     * coördinaten moeten gelezen worden. Voor België is dit standaard Belge 
     * Lambert, met als code 31370.
     * 
     * @return integer
     */
    public function getSrid( )
    {
        return 31370;
    }

    /**
     * Zet de rechthoek om in een array.
     * 
     * Geeft een array terug met 4 sleutels:
     * <ul>
     * <li><strong>maxX</strong> De maximale X-waarde</li>
     * <li><strong>maxY</strong> De maximale Y-waarde</li>
     * <li><strong>minX</strong> De minimale X-waarde</li>
     * <li><strong>minY</strong> De minimale Y-waarde</li>
     * </ul>
     * @return array
     */
    public function toArray( )
    {
        return array( 'maxX' => $this->maxX,
                      'maxY' => $this->maxY,
                      'minX' => $this->minX,
                      'minY' => $this->minY );
    }

}
?>
