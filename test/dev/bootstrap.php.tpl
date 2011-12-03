<?php

error_reporting( E_ALL | E_STRICT );

spl_autoload_register(function($class)
{
    $file = __DIR__.'/../lib/'.strtr( $class, '\\', '/').'.php';
    if ( file_exists( $file)) {
        require $file;
        return true;
    }
});



define('CRAB_USER', '@@CRAB_USER@@');
define('CRAB_PASSWORD', '@@CRAB_PWD@@');

define('CRAB_RUN_INTEGRATION_TESTS', @@CRAB_RUN_INTEGRATION_TESTS@@ );

define('CAPAKEY_USER', '@@CAPAKEY_USER@@');
define('CAPAKEY_PASSWORD', '@@CAPAKEY_PWD@@');

define('CAPAKEY_RUN_INTEGRATION_TESTS', @@CAPAKEY_RUN_INTEGRATION_TESTS@@ );

define('CRAB_PROXY_HOST', '@@CRAB_PROXY_HOST@@');
define('CRAB_PROXY_PORT', '@@CRAB_PROXY_PORT@@');

define('CAPAKEY_PROXY_HOST', '@@CAPAKEY_PROXY_HOST@@');
define('CAPAKEY_PROXY_PORT', '@@CAPAKEY_PROXY_PORT@@');

?>
