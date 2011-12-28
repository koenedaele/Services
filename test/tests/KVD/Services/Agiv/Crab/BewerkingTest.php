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
 * Unit test voor Bewerking.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class BewerkingTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->bewerking = new Bewerking( 1,
                                          'invoer',
                                          'Invoer in de databank.' );
    }

    public function testGetters( )
    {
        $this->assertEquals( 1, $this->bewerking->getCode( ) );
        $this->assertEquals( 'invoer',
                             $this->bewerking->getNaam( ) );
        $this->assertEquals( 'Invoer in de databank.',
                             $this->bewerking->getDefinitie( ) );
    }
}
