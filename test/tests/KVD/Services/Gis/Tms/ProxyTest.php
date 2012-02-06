<?php

namespace KVD\Services\Gis\Tms;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    protected $parameters;

    public function setUp( )
    {
        $this->parameters = array( 'url' => 'http://grb.agiv.be/geodiensten/raadpleegdiensten/geocache/tms/' );
        if ( defined ( 'TMS_PROXY_HOST' ) && TMS_PROXY_HOST != '' ) {
            $this->parameters['proxy_host'] = TMS_PROXY_HOST;
            if ( defined( 'TMS_PROXY_PORT' ) && TMS_PROXY_PORT != '') {
                $this->parameters['proxy_port'] = TMS_PROXY_PORT;
            }
        }
        $this->proxy = new Proxy( $this->parameters );
    }

    public function tearDown( )
    {
        $this->proxy = null;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testActiveCacheRequiresCacheDir()
    {
        $this->parameters['cache'] ['active'] = true;
        $this->proxy = new Proxy( $this->parameters );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCacheDirMustBeWriteable( )
    {
        $this->parameters['cache'] ['active'] = true;
        $this->parameters['cache'] ['cache_dir'] = '/';
        $this->proxy = new Proxy( $this->parameters );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCacheDaysMustBeInteger( )
    {
        $this->parameters['cache'] ['active'] = true;
        $this->parameters['cache'] ['cache_dir'] = '.';
        $this->parameters['cache'] ['cache_days'] = 'tien';
        $this->proxy = new Proxy( $this->parameters );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUrlIsRequired( )
    {
        unset( $this->parameters['url'] );
        $this->proxy = new Proxy( $this->parameters );
    }

    public function testGetTile( )
    {
        $layer = 'grb_bsk@BPL72VL';
        $z = 1;
        $x = 0;
        $y = 0;

        $res = $this->proxy->getTile($layer, $z, $x, $y );
        $this->assertNotEmpty( $res );
    }

    public function testWriteCache( )
    {
        $this->parameters['cache']['active'] = true;
        $this->parameters['cache']['cache_dir'] = __DIR__ . '/cache';
        mkdir( $this->parameters['cache']['cache_dir'] );
        $this->proxy = new Proxy( $this->parameters );

        $layer = 'grb_bsk@BPL72VL';
        $z = 1;
        $x = 0;
        $y = 0;

        $res = $this->proxy->getTile( $layer, $z, $x, $y );
        $this->assertNotEmpty( $res );

        $version = '1.0.0';

        $this->assertFileExists( $this->parameters['cache']['cache_dir'] . 
            "/${version}/${layer}/${z}/${x}/${y}.png" );
        unlink( $this->parameters['cache']['cache_dir'] .
            "/${version}/${layer}/${z}/${x}/${y}.png" );
        rmdir( $this->parameters['cache']['cache_dir'] .
            "/${version}/${layer}/${z}/${x}" );
        rmdir( $this->parameters['cache']['cache_dir'] .
            "/${version}/${layer}/${z}" );
        rmdir( $this->parameters['cache']['cache_dir'] . 
            "/${version}/${layer}" );
        rmdir( $this->parameters['cache']['cache_dir'] . 
            "/${version}" );
        rmdir( $this->parameters['cache']['cache_dir'] );
    }

    public function testReadCache( )
    {

        $this->parameters['cache']['active'] = true;
        $this->parameters['cache']['cache_dir'] = __DIR__ . '/cache';
        $this->parameters['cache']['cache_days'] = 10;
        mkdir( $this->parameters['cache']['cache_dir'] );
        $this->proxy = new Proxy( $this->parameters );

        $layer = 'grb_bsk@BPL72VL';
        $z = 1;
        $x = 0;
        $y = 0;

        $res = $this->proxy->getTile( $layer, $z, $x, $y );
        $this->assertNotEmpty( $res );

        $version = '1.0.0';

        $this->assertFileExists( $this->parameters['cache']['cache_dir'] . 
            "/${version}/${layer}/${z}/${x}/${y}.png" );

        //Tweede request
        //Voorlopig geen idee hoe we kunnen checken dat de cache effectief werd 
        //aangesproken.
        $res = $this->proxy->getTile( $layer, $z, $x, $y );
        $this->assertNotEmpty( $res );

        unlink( $this->parameters['cache']['cache_dir'] .
            "/${version}/${layer}/${z}/${x}/${y}.png" );
        rmdir( $this->parameters['cache']['cache_dir'] .
            "/${version}/${layer}/${z}/${x}" );
        rmdir( $this->parameters['cache']['cache_dir'] .
            "/${version}/${layer}/${z}" );
        rmdir( $this->parameters['cache']['cache_dir'] . 
            "/${version}/${layer}" );
        rmdir( $this->parameters['cache']['cache_dir'] . 
            "/${version}" );
        rmdir( $this->parameters['cache']['cache_dir'] );
    }
 
}
?>
