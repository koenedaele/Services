<?php
/**
 * @package    KVD.Services.Agiv.Crab
 * @subpackage Tests
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

use KVD\Services\Agiv as A;

/**
 * Unit test voor Straat.
 *
 * @package    KVD.Services.Agiv.Crab
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class StraatTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->gemeenteNamen = array( 'nl' => 'TestGemeente' );
        $this->straatNamen = array ( 'nl' => 'Nieuwstraat',
                                     'fr' => 'Rue Neuve' );
        $this->gemeente = new Gemeente( 666, 39999,
                                        $this->gemeenteNamen,
                                        'nl');
        $this->straat = new Straat( 1, $this->gemeente, 'Nieuwstraat',
                                    $this->straatNamen, 'nl' );
    }

    public function tearDown( )
    {
        $this->gemeente = null;
        $this->straatNamen = null;
        $this->gemeenteNamen = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 1, $this->straat->getId( ) );
        $this->assertEquals( 'Nieuwstraat', $this->straat->getNaam( ) );
        $this->assertEquals( 'Rue Neuve', $this->straat->getNaam( 'fr' ) );
        $this->assertEquals( 'nl', $this->straat->getTaalCode( ) );
        $this->assertEquals( 'Nieuwstraat', $this->straat->getLabel( ) );
        $this->assertEquals( $this->gemeente, $this->straat->getGemeente( ) );
    }

    public function testNameInUnexistingLanguage( )
    {
        $this->assertEquals( 'Nieuwstraat', $this->straat->getNaam( 'en' ) );
    }

}
?>
