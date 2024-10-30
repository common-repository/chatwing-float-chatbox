<?php
/**
 * @package ChatwingFloatUI\IntegrationPlugins\Wordpress
 */

/*
Plugin Name: Chatwing Float Chatbox
Description: Chatwing offers an unlimited live website or blog chat experience. This chat widget specializes in delivering real-time communication at any given time. Engage in a free chat with visitors and friends!
Version: 0.0.1
Author: chatwing
Author URI: https://chatwing.com/
License: GPLv2 or later
Text Domain: chatwing_float_chatbox
*/
if ( ! defined( 'ABSPATH' ) ) exit;
define('CHATWING_FLOAT_UI_VERSION', '1.0.0');
define('CHATWING_FLOAT_UI_TEXTDOMAIN', 'chatwing_float_ui');
define('CHATWING_FLOAT_UI_PATH', dirname(__FILE__));
define('CHATWING_FLOAT_UI_CLASS_PATH', CHATWING_FLOAT_UI_PATH . '/classes');
define('CHATWING_FLOAT_UI_TPL_PATH', CHATWING_FLOAT_UI_PATH . '/templates');
define('CHATWING_FLOAT_UI_PLG_MAIN_FILE', __FILE__);
define('CHATWING_FLOAT_UI_PLG_URL', plugin_dir_url(__FILE__));

define('CHATWING_FLOAT_UI_DEBUG', false);
define('CW_USE_STAGING', false);

define('CHATWING_FLOAT_UI_CLIENT_ID', 'wordpress');

require_once CHATWING_FLOAT_UI_PATH . '/chatwing-sdk/src/ChatwingFloatUI/autoloader.php';
require_once CHATWING_FLOAT_UI_PATH . '/chatwing-sdk/src/ChatwingFloatUI/start.php';
$keyPath = CHATWING_FLOAT_UI_PATH . '/key.php';
if (file_exists($keyPath)) {
    require $keyPath;
}

/**
 * Plugin class autoloader
 * @param  $className
 * @return bool
 * @throws Exception
 */
function chatwingFloatUIAutoloader($className)
{
    $prefix = 'ChatwingFloatUI\\IntegrationPlugins\\WordPress\\';

    if ($pos = strpos($className, $prefix) !== 0) {
        return false;
    }

    $filePath = CHATWING_FLOAT_UI_CLASS_PATH . '/' . str_replace('\\', '/', substr($className, strlen($prefix))) . '.php';
    if (file_exists($filePath)) {
        require_once($filePath);

        if (!class_exists($className)) {
            throw new Exception(__("Class {$className} doesn't exist ", CHATWING_FLOAT_UI_TEXTDOMAIN));
        }

        return true;
    } else {
        throw new Exception(__("Cannot find file at {$filePath} ", CHATWING_FLOAT_UI_TEXTDOMAIN));
    }
}

function chatwing_float_ui_text_domain() {
    load_plugin_textdomain( 'chatwing_float_ui', WP_PLUGIN_DIR . '/chatwing-float-ui/'. 'languages', basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'chatwing_float_ui_text_domain' );
add_action( 'admin_print_styles', 'register_plugin_styles_float_chatbox' );
/**
 * Register style sheet.
 */
function register_plugin_styles_float_chatbox() {
    wp_register_style( 'formStyleFloatChatbox', plugins_url( 'chatwing-float-chatbox/assets/forms-min.css' ));
    wp_register_style( 'buttonStyleFloatChatbox', plugins_url( 'chatwing-float-chatbox/assets/buttons-min.css' ));
    wp_enqueue_style( 'formStyleFloatChatbox' );
    wp_enqueue_style( 'buttonStyleFloatChatbox' );
}

spl_autoload_register('chatwingFloatUIAutoloader');

use ChatwingFloatUI\Application as ChatwingFloatUI;
use ChatwingFloatUI\IntegrationPlugins\WordPress\Application;
use ChatwingFloatUI\IntegrationPlugins\WordPress\DataModel;

ChatwingFloatUI::getInstance()->bind('client_id', CHATWING_FLOAT_UI_CLIENT_ID);
$app = new Application(DataModel::getInstance());
$app->run();