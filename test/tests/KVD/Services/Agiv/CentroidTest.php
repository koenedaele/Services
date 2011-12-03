<?php
/**
 * @package    KVD.Services.Agiv
 * @subpackage Tests
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author     Koen Van Daele <koen_van_daele@telenet.be> 
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
namespace KVD\Services\Agiv;

/**
 * Unit test voor Centroid.
 * 
 * @package    KVD.Services.Agiv
 * @subpakcage Tests
 * @version    0.1.0
 * @copyright  2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @license    http://www.osor.eu/eupl The European Union Public Licence
 */
class CentroidTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->centroid = new Centroid( 2, 45 );
    }

    public function tearDown( )
    {
        $this->centroid = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 2, $this->centroid->getX( ) );
        $this->assertEquals( 45, $this->centroid->getY( ) );
    }

    public function testgetSrid( )
    {
        $this->assertEquals( 31370, $this->centroid->getSrid( ) );
    }

    public function testToArray( )
    {
        $arr = $this->centroid->toArray( );
        $this->assertEquals( 2, $arr['x'] );
        $this->assertEquals( 45, $arr['y'] );
    }
}
?>
