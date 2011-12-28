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
 * Unit test voor Organisatie.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class OrganisatieTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->organisatie = new Organisatie( 1, 'gemeente', 'Gemeente.' );
    }

    public function testGetters( )
    {
        $this->assertEquals( 1, $this->organisatie->getCode( ) );
        $this->assertEquals( 'gemeente', $this->organisatie->getNaam( ) );
        $this->assertEquals( 'Gemeente.', $this->organisatie->getDefinitie( ) );
    }
}
