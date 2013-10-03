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
        $this->cache_dir = __DIR__ . '/cache';
        if ( is_dir($this->cache_dir) ) {
            $this->delTree( $this->cache_dir );
        }
        mkdir( $this->cache_dir );
    }

    public function tearDown( )
    {
        $this->proxy = null;
        if ( is_dir($this->cache_dir) ) {
            $this->delTree( $this->cache_dir );
        }
    }

    private static function delTree( $dir) {
        $files = array_diff( scandir( $dir), array( '.','..'));
        foreach ( $files as $file) {
            ( is_dir( "$dir/$file")) ? self::delTree( "$dir/$file") : unlink( "$dir/$file");
        }
        return rmdir( $dir);
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
    public function testCacheDaysMustNotBeString( )
    {
        $this->parameters['cache'] ['active'] = true;
        $this->parameters['cache'] ['cache_dir'] = '.';
        $this->parameters['cache'] ['cache_days'] = 'tien';
        $this->proxy = new Proxy( $this->parameters );
    }

    public function testCacheDaysMustBeNumeric( )
    {
        $this->parameters['cache'] ['active'] = true;
        $this->parameters['cache'] ['cache_dir'] = '.';
        $this->parameters['cache'] ['cache_days'] = '25';
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

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReturnMustBeStringOrStream( )
    {
        $this->parameters['return'] = 'somethingElse';
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
        $this->parameters['cache']['cache_dir'] = $this->cache_dir;
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
    }

    public function testReadCache( )
    {

        $this->parameters['cache']['active'] = true;
        $this->parameters['cache']['cache_dir'] = $this->cache_dir;
        $this->parameters['cache']['cache_days'] = 10;
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
    }

    /**
     * testReturnStreamWithoutCacheThrowsException
     * @expectedException LogicException
     */
    public function testReturnStreamWithoutCacheThrowsException( )
    {
        $this->parameters['return'] = 'stream';
        $this->proxy = new Proxy( $this->parameters );

        $layer = 'grb_bsk@BPL72VL';
        $z = 1;
        $x = 0;
        $y = 0;

        $res = $this->proxy->getTile( $layer, $z, $x, $y );
    }

    public function testReturnStreamAndWriteCache( )
    {
        $this->parameters['cache']['active'] = true;
        $this->parameters['cache']['cache_dir'] = $this->cache_dir;
        $this->proxy = new Proxy( $this->parameters );

        $layer = 'grb_bsk@BPL72VL';
        $z = 1;
        $x = 0;
        $y = 0;

        $res = $this->proxy->getTile( $layer, $z, $x, $y, array ( 'return' => 'stream' ) );
        $this->assertTrue( is_resource( $res ) );

        $version = '1.0.0';

        $this->assertFileExists( $this->parameters['cache']['cache_dir'] . 
            "/${version}/${layer}/${z}/${x}/${y}.png" );
    }

    public function testReadCacheAndReturnStream( )
    {

        $this->parameters['cache']['active'] = true;
        $this->parameters['cache']['cache_dir'] = $this->cache_dir;
        $this->parameters['cache']['cache_days'] = 10;
        $this->proxy = new Proxy( $this->parameters );

        $layer = 'grb_bsk@BPL72VL';
        $z = 1;
        $x = 0;
        $y = 0;

        $res = $this->proxy->getTile( $layer, $z, $x, $y, array ( 'return' => 'stream' ) );
        $this->assertTrue( is_resource( $res ) );

        $version = '1.0.0';

        $this->assertFileExists( $this->parameters['cache']['cache_dir'] . 
            "/${version}/${layer}/${z}/${x}/${y}.png" );

        //Tweede request
        //Voorlopig geen idee hoe we kunnen checken dat de cache effectief werd 
        //aangesproken.
        $res = $this->proxy->getTile( $layer, $z, $x, $y, array ( 'return' => 'stream' ) );
        $this->assertTrue( is_resource( $res ) );
    }
 
}
?>
