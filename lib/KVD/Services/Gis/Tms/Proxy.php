<?php
/**
 * @package   KVD.Services.Gis.Tms
 * @copyright 2012 Koen Van Daele <koen_van_daele@telenet.be> 
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Gis\Tms;

/**
 * Class that can proxy a TileMapService. Usefull for local caching.
 *
 * This is not a complete interface to a TileMapService, just a quick way to 
 * build a local proxy.
 *
 * @since     0.1.0
 * @package   KVD.Services.Gis.Tms
 * @copyright 2012 Koen Van Daele <koen_van_daele@telenet.be> 
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class Proxy
{
    /**
     * parameters
     *
     * @var array
     */
    protected $parameters = array ( 'version' => '1.0.0',
                                    'mime-type' => 'image/png',
                                    'return' => 'string',
                                    'cache' => array ( 'active' => false ) );

    /**
     * Create the proxy.
     *
     * The following parameters exist:
     * <ul>
     *  <li><strong>url: </strong>Url to the base of the service. Required</li>
     *  <li><strong>version: </strong>Version of the TMS service. Defaults to 
     *  1.0.0</li>
     *  <li><strong>mime-type: </strong>The mime type of the tiles to be 
     *  requested. Currently only image/png is supported. Optional and defaults 
     *  to image/png.</li>
     *  <li><strong>return: </strong> Shoudl the getTile method return the 
     *  contents of tiles as in memory strings (string) or as streams (stream). 
     *  Optional and defaults to string.</li>
     *  <li><strong>cache: </strong>An array that determines if caching is
     *  needed and where to cache.</li>
     *  <li><strong>cache.active: </strong>Switch to decide if caching is on or 
     *  off. Defaults to false.</li>
     *  <li><strong>cache.days: </strong>The number of days a tile should be
     *  cached. Please bear in mind caching ignores any http caching related
     *  headers. If caching is on, this defaults to 30 days.</li>
     *  <li><strong>cache.cache_dir: </strong>Directory to cache the tiles in. 
     *  If caching is on, this parameter is required.</li>
     *  <li><strong>proxy_host: </strong>Host of proxy server needed to access 
     *  the service. Optional.</li>
     *  <li><strong>proxy_port: </strong>Port of proxy server needed to access 
     *  the service. Optional.</li>
     * </ul>
     *
     * @param array $parameters
     * @return void
     */
    public function __construct( array $parameters )
    {
        if ( !isset( $parameters['url'] ) ) {
            throw new \InvalidArgumentException( 'Missing url for TMS service' );
        }
        if ( isset( $parameters['cache']['active'] ) && ( $parameters['cache']['active'] == true ) ) {
            if (!isset( $parameters['cache']['cache_dir'] ) ) {
                throw new \InvalidArgumentException( 'cache_dir must be present if using cache.' );
            } else {
                if ( !is_writeable( $parameters['cache']['cache_dir'] ) ) {
                    throw new \InvalidArgumentException( 'The directory cache_dir is not writeable' );
                }
            }
            if ( isset( $parameters['cache']['cache_days'] ) ) {
                if ( !is_numeric( $parameters['cache']['cache_days'] ) ) {
                    throw new \InvalidArgumentException( 'cache_days must be a number.' );
                }
            } else {
                $parameters['cache']['cache_days'] = 30;
            }
        }
        if ( isset( $parameters['return'] ) ) {
            if ( !($parameters['return'] == 'string' || $parameters['return'] == 'stream' ) ) {
                throw new \InvalidArgumentException( 'return parameter must be string or stream.' );
            }
        }
        $this->parameters = array_merge( $this->parameters, $parameters );
    }

    /**
     * getTile
     *
     * @param string  $layer
     * @param integer $z
     * @param integer $x
     * @param integer $y
     * @param array   $options Can contain version or return parameter. 
     *                         Overrides the class default.
     * @return string String containing the tile.
     */
    public function getTile( $layer, $z, $x, $y, $options = array( ) )
    {
        $version = isset( $options['version'] ) ?  $options['version'] : $this->parameters['version'];
        $return = isset( $options['return'] ) ?  $options['return'] : $this->parameters['return'];
        if ( $return == 'stream' && !$this->parameters['cache']['active'] ) {
            throw new \LogicException( 'Returning streams is only supported when using caching.' );
        }
        // Check if we're caching stuff.
        if ( $this->parameters['cache']['active'] ) {
            $this->checkCacheDirExists( $version, $layer, $z, $x );
            $extension = $this->getExtensionForMimeType( $this->parameters['mime-type'] );
            $file = $this->parameters['cache']['cache_dir'] . 
                    "/${version}/${layer}/${z}/${x}/${y}.${extension}";
            $cache_days = isset( $this->parameters['cache']['cache_days'] );
            // if we have it in cache and it's not stale, return it
            if ( is_file( $file) && filemtime($file) > time()-(86400*$cache_days) ) {
                if ( $return == 'stream' ) {
                    return fopen( $file, 'rb' );
                } else {
                    return file_get_contents( $file );
                }
            }
            // get it, cache it and return
            $url = $this->getTileUrl( $version, $layer, $z, $x, $y );
            $ch = $this->getCurl( $url );
            curl_setopt( $ch, CURLOPT_HEADER, false);
            $fp = fopen( $file, "w");
            curl_setopt( $ch, CURLOPT_FILE, $fp );
            $response = curl_exec( $ch);
            fflush( $fp );
            fclose( $fp );
            curl_close( $ch);
            if ( $return == 'stream' ) {
                return fopen( $file, 'rb' );
            } else {
                return file_get_contents( $file );
            }
        } else {
            $url = $this->getTileUrl( $version, $layer, $z, $x, $y );
            $ch = $this->getCurl( $url );
            curl_setopt( $ch, CURLOPT_HEADER, false);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $response = curl_exec( $ch);
            curl_close( $ch);
            return $response;
        }
    }

    /**
     * checkCacheDirExists
     *
     * @param string $version
     * @param integer $layer
     * @param integer $z
     * @param integer $x
     * @return void
     */
    protected function checkCacheDirExists( $version, $layer, $z, $x )
    {
        $cache_dir = $this->parameters['cache']['cache_dir'];
        if ( !is_dir( $cache_dir . "/$version" ) ) {
            mkdir( $cache_dir . "/$version" );
        }
        if ( !is_dir( $cache_dir . "/$version/$layer" ) ) {
            mkdir( $cache_dir . "/$version/$layer" );
        }
        if ( !is_dir( $cache_dir . "/$version/$layer/$z" ) ) {
            mkdir( $cache_dir . "/$version/$layer/$z" );
        }
        if ( !is_dir( $cache_dir . "/$version/$layer/$z/$x" ) ) {
            mkdir( $cache_dir . "/$version/$layer/$z/$x" );
        }
    }

    protected function getCurl( $url )
    {
        $ch = curl_init( $url);
        // @codeCoverageIgnoreStart
        if ( isset( $this->parameters['proxy_host'] ) ) {
            $proxy = $this->parameters['proxy_host'];
            if ( isset( $this->parameters['proxy_port'] ) ) {
                $proxy .= ':' . $this->parameters['proxy_port'];
            }
            curl_setopt( $ch, CURLOPT_PROXY, $proxy );
        }
        // @codeCoverageIgnoreEnd
        return $ch;
    }

    protected function getTileUrl( $version, $layer, $z, $x, $y )
    {
        $extension = $this->getExtensionForMimeType( $this->parameters['mime-type'] );
        return sprintf( $this->parameters['url'] . '/%s/%s/%d/%d/%d.%s',
                        $version, $layer,
                        $z, $x, $y, $extension );
    }

    /**
     * getExtensionForMimeType
     *
     * @todo Fix for other mime types.
     *
     * @param string $mime
     * @return string
     */
    protected function getExtensionForMimeType( $mime )
    {
        return 'png';
    }
}
