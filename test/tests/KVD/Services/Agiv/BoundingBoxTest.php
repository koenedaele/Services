<?php
/**
 * @package     KVD.Services.Agiv
 * @subpackage  Tests
 * @copyright   2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author      Koen Van Daele <koen_van_daele@telenet.be> 
 * @license     http://www.osor.eu/eupl The European Union Public Licence
 */
namespace KVD\Services\Agiv;

/**
 * Unit test voor BoundingBox .
 * 
 * @package     KVD.Services.Agiv
 * @subpakcage  Tests
 * @version     0.1.0
 * @copyright   2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @license     http://www.osor.eu/eupl The European Union Public Licence
 */
class BoundingBoxTest extends \PHPUnit_Framework_TestCase
{
    public function setUp( )
    {
        $this->box = new BoundingBox( 10, 100, 2, 45 );
    }

    public function tearDown( )
    {
        $this->box = null;
    }

    public function testGetters( )
    {
        $this->assertEquals( 10, $this->box->getMaxX( ) );
        $this->assertEquals( 100, $this->box->getMaxY( ) );
        $this->assertEquals( 2, $this->box->getMinX( ) );
        $this->assertEquals( 45, $this->box->getMinY( ) );
    }

    public function testgetSrid( )
    {
        $this->assertEquals( 31370, $this->box->getSrid( ) );
    }

    public function testToArray( )
    {
        $arr = $this->box->toArray( );
        $this->assertEquals( 10, $arr['maxX'] );
        $this->assertEquals( 100, $arr['maxY'] );
        $this->assertEquals( 2, $arr['minX'] );
        $this->assertEquals( 45, $arr['minY'] );
    }
}
?>
