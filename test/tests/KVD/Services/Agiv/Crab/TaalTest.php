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
 * Unit test voor Taal.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class TaalTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->taal = new Taal( 'nl', 'Nederlands', 'De nederlandsche taal.' );
    }

    public function testGetters( )
    {
        $this->assertEquals( 'nl', $this->taal->getCode( ) );
        $this->assertEquals( 'Nederlands', $this->taal->getNaam( ) );
        $this->assertEquals( 'De nederlandsche taal.', $this->taal->getDefinitie( ) );
    }
}
