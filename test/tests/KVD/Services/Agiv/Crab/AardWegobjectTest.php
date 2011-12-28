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
 * Unit test voor AardWebobject.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class AardWegobjectTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->aard = new AardWegobject( 2,
                                         'grbWegverbinding',
                                         'Wegverbinding volgens het GRB.' );
    }

    public function testGetters( )
    {
        $this->assertEquals( 2, $this->aard->getCode( ) );
        $this->assertEquals( 'grbWegverbinding', $this->aard->getNaam( ) );
        $this->assertEquals( 'Wegverbinding volgens het GRB.', $this->aard->getDefinitie( ) );
    }
}
