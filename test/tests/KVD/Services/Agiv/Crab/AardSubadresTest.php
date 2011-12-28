<?php
/**
 * @package    KVD.Services.Agiv.Crab
 * @subpackage Tests
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

/**
 * Unit test voor AardSubadres.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class AardSubadresTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->aard = new AardSubadres( 1, 'appartementNummer', 'Nummer van het appartement.' );
    }

    public function testGetters( )
    {
        $this->assertEquals( 1, $this->aard->getCode( ) );
        $this->assertEquals( 'appartementNummer', $this->aard->getNaam( ) );
        $this->assertEquals( 'Nummer van het appartement.', $this->aard->getDefinitie( ) );
    }
}
