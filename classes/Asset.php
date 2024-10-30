<?php
namespace ChatwingFloatUI\IntegrationPlugins\WordPress;
if ( ! defined( 'ABSPATH' ) ) exit;

class Asset
{
    /**
     * @param $file
     * @return string
     */
    public static function link($file)
    {
        return CHATWING_FLOAT_UI_PLG_URL . 'assets/' . $file;
    }
}