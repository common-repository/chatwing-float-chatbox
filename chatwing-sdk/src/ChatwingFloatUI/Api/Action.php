<?php
/**
 * @author chatwing <dev@chatwing.com>
 * @package ChatwingFloatUI\SDK\Api
 */
namespace ChatwingFloatUI\Api;
if ( ! defined( 'ABSPATH' ) ) exit;

use \ChatwingFloatUI\Object;
use \ChatwingFloatUI\Exception\ChatwingFloatUIException;

/**
 * Class Action
 *
 * @package ChatwingFloatUI\Api
 * @method getType() string
 */
class Action extends Object
{
    private static $actionList = array();

    /**
     * Constructor of Action object. Throw exception if action is not found
     *
     * @param       $name
     * @param array $params
     *
     * @throws \ChatwingFloatUI\Exception\ChatwingFloatUIException
     */
    public function __construct($name, $params = array())
    {
        if (empty(self::$actionList)) {
            self::loadActionList();
        }

        $this->setCurrentAction($name);
        $this->setData('params', $params);
    }

    public function getParams()
    {
        return $this->getData('params');
    }

    /**
     * @return null
     */
    public function getActionUri()
    {
        return $this->getData('name');
    }

    /**
     * @param $actionName
     * @return bool
     */
    public function isActionValid($actionName)
    {
        return isset(self::$actionList[$actionName]) && !empty(self::$actionList[$actionName]);
    }

    public function isAuthenticationRequired()
    {
        return $this->hasData('auth') && $this->getData('auth');
    }

    /**
     * @param null $path
     * @throws ChatwingFloatUIException
     */
    protected static function loadActionList($path = null)
    {
        if (is_null($path)) {
            if (!defined('CHATWING_FLOAT_UI_BASE_DIR')) {
                define('CHATWING_FLOAT_UI_BASE_DIR', dirname(dirname(__FILE__)));
            }
            $path = dirname(CHATWING_FLOAT_UI_BASE_DIR) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'actions.php';
        }

        if (file_exists($path)) {
            self::$actionList = include $path;
        } else {
            throw new ChatwingFloatUIException(array('message' => __("Action list not found", CHATWING_FLOAT_UI_TEXTDOMAIN), 'code' => 0));
        }
    }

    /**
     * @param $actionName
     * @throws ChatwingFloatUIException
     */
    private function setCurrentAction($actionName)
    {
        if (!$this->isActionValid($actionName)) {
            throw new \InvalidArgumentException('Invalid action');
        }
        $this->setData('name', $actionName);
        foreach (self::$actionList[$actionName] as $key => $value) {
            $this->setData($key, $value);
        }
    }
} 