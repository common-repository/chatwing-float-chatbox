<?php

/**
 * @author chatwing
 * @package ChatwingFloatUI_SDK
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if (!defined('CW_DEBUG')) {
    define('CW_DEBUG', false);
}

define('CW_SDK_VESION', '1.0');
define('CW_ENV_DEVELOPMENT', 'development');
define('CW_ENV_PRODUCTION', 'production');

use ChatwingFloatUI\Application as App;

$app = App::getInstance();
$app->bind(
    'api',
    function (\ChatwingFloatUI\Container $container) {
        $app = new ChatwingFloatUI\Api($container->get('client_id'));
        
        $app->setEnv(
            defined('CW_USE_STAGING') && CW_USE_STAGING ? CW_ENV_DEVELOPMENT : CW_ENV_PRODUCTION
        );

        if ($container->has('access_token')) {
            $app->setAccessToken($container->get('access_token'));
        }

        return $app;
    }
);

$app->factory(
    'floatUI',
    function (\ChatwingFloatUI\Container $container) {
        return new \ChatwingFloatUI\FloatUI($container->get('api'));
    }
);