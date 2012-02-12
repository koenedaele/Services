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
                                    'cache' => array ( 'active' => false ) );

    /**
     * Create the proxy.
     *
     * The following parameters exist:
     * <ul>
     *  <li><strong>url: </strong>Url to the base of the service. Required</li>
     *  <li><strong>version: </strong>Version of the TMS service. Defaults to 
     *  1.0.0</li>
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
                if ( !is_integer( $parameters['cache']['cache_days'] ) ) {
                    throw new \InvalidArgumentException( 'cache_days must be an integer.' );
                }
            } else {
                $parameters['cache']['cache_days'] = 30;
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
     * @param string  $version Defaults to 1.0.0
     * @return string String containing the tile.
     */
    public function getTile( $layer, $z, $x, $y, $version = null )
    {
        if ( $version == null ) {
            $version = $this->parameters['version'];
        }
        if ( $this->parameters['cache']['active'] == true ) {
            $this->checkCacheDirExists( $version, $layer, $z, $x );
            $extension = $this->getExtensionForMimeType( $this->parameters['mime-type'] );
            $file = $this->parameters['cache']['cache_dir'] . 
                    "/${version}/${layer}/${z}/${x}/${y}.${extension}";
            $cache_days = isset( $this->parameters['cache']['cache_days'] );
            if ( is_file( $file) && filemtime($file) > time()-(86400*$cache_days) ) {
                return file_get_contents( $file );
            }
            $url = $this->getTileUrl( $version, $layer, $z, $x, $y );
            $ch = $this->getCurl( $url );
            curl_setopt( $ch, CURLOPT_HEADER, false);
            $fp = fopen( $file, "w");
            curl_setopt( $ch, CURLOPT_FILE, $fp );
        } else {
            $url = $this->getTileUrl( $version, $layer, $z, $x, $y );
            $ch = $this->getCurl( $url );
            curl_setopt( $ch, CURLOPT_HEADER, false);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        }
        if ( $this->parameters['cache']['active'] == false ) {
            $response = curl_exec( $ch);
            curl_close( $ch);
            return $response;
        } else {
            $response = curl_exec( $ch);
            fflush( $fp );
            fclose( $fp );
            curl_close( $ch);
            return file_get_contents( $file );
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
