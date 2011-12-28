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
 * Unit test voor AardTerreinobject.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class AardTerreinobjectTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->aard = new AardTerreinobject( 4,
                                             'grbAdmperceel', 
                                             'Perceel volgens het GRB.' );
    }

    public function testGetters( )
    {
        $this->assertEquals( 4, $this->aard->getCode( ) );
        $this->assertEquals( 'grbAdmperceel', $this->aard->getNaam( ) );
        $this->assertEquals( 'Perceel volgens het GRB.', $this->aard->getDefinitie( ) );
    }
}
