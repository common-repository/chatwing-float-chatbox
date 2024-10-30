<?php

/**
 * @author  chatwing
 * @package ChatwingFloatUI\SDK
 */
namespace ChatwingFloatUI;
if ( ! defined( 'ABSPATH' ) ) exit;

use ChatwingFloatUI\Exception\ChatwingFloatUIException;
use ChatwingFloatUI\IntegrationPlugins\WordPress\DataModel;

class FloatUI extends Object
{
    /**
     * @var Api
     */
    protected $api;
    protected $id = null;
    protected $key = null;
    protected $alias = null;
    protected $params = array();
    protected $secret = null;

    protected $baseUrl = null;
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function getKeyChatbox() {
      $params = array('id' => $this->getId());
      $chatboxData =  array();
      if (function_exists( 'wp_get_current_user' ) ) {
        $user = wp_get_current_user();
        $params["wp_user"] = $user->data;
        $avatar = get_avatar_data($user->data->ID);
        $params["wp_user"]->avatar = $avatar["url"];
        $params["plugin"] = "wordpress";
      }
      if (is_null($this->baseUrl)) {
        // get chatbox data
        $response = $this->api->call('chatbox/read',$params);
        if(empty($response)) {
            throw new ChatwingFloatUIException(__("Invalid chatbox ID", CHATWING_FLOAT_UI_TEXTDOMAIN));
        } else {
            $chatboxData = $response->get('data');
        }
          
      }
      return $chatboxData["key"];
    }

    /**
     * Return chatbox iframe code
     * @throws ChatwingFloatUIException If no alias or chatbox key is set
     * @return string
     */
    public function getIframe() {
      $api = new Api('');
      $domain = $api->getDomain();
      $key = $this->getKeyChatbox();
      $tokenCustom = $this->customLoginFloatChatbox();
      $url = '<script src="'.$domain[$api->getEnv()];
      $url = $url.'/assets/float_ui/js/production/chatwing.js?chatbox_floating=true&chatbox_key='.$key;
      $url = $url .'&access_token='.$tokenCustom;
      $url = $url .'&plugin=wordpress';
      $url = $url.'" id="rwidgetechatwingn"> </script>';
      return $url;
    }
    /**
      * Getting user login
      */
    public function customLoginFloatChatbox() {
       $params = array('id' => $this->getId());
        $chatboxData =  array();
        $accessToken = "";
        if (function_exists( 'wp_get_current_user' ) ) {
          $user = wp_get_current_user();
          $params["wp_user"] = $user->data;
          $avatar = get_avatar_data($user->data->ID);
          $params["wp_user"]->avatar = $avatar["url"];
          $params["plugin"] = "wordpress";
           // get chatbox data
          $response = $this->api->call('chatbox/read',$params);
          if(empty($response)) {
              throw new ChatwingException(__("Invalid chatbox ID", CHATWING_TEXTDOMAIN));
          } else {
              $chatboxData = $response->get('data');
              $accessToken = $chatboxData["chatuser"]["access_token"];
          }
        }
        return $accessToken;
    }

    /**
     * Set chatbox ID
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set chatbox key
     *
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * get the current chatbox's key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set chatbox alias
     *
     * @param string $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Get current chatbox's alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set chatbox's parameter
     *
     * @param string|array $key 
     * @param string $value
     *
     * @return $this
     */
    public function setParam($key, $value = '')
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->setParam($k, $v);
            }
        } else {
            $this->params[$key] = $value;
        }
        return $this;
    }

    /**
     * Get parameter
     * @param  string $key     
     * @param  null|mixed $default 
     * @return mixed|null
     */
    public function getParam($key = '', $default = null)
    {
        if (empty($key)) {
            return $this->params;
        }
        return isset($this->params[$key]) ? $this->params[$key] : $default;
    }

    /**
     * Get all parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set chatbox secret key
     * @param $s
     *
     * @return $this
     */
    public function setSecret($s)
    {
        $this->secret = $s;
        return $this;
    }

    /**
     * Get secret
     * @return string|null
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Get encrypted session
     * @return string
     */
    public function getEncryptedSession()
    {
        if (isset($this->params['custom_session'])) {
            $customSession = $this->params['custom_session'];
            if (is_string($customSession)) {
                return $customSession;
            }

            if (is_array($customSession) && !empty($customSession) && $this->getSecret()) {
                $session = new CustomSession();
                $session->setSecret($this->getSecret());
                $session->setData($customSession);
                $this->setParam('custom_session', $session->toEncryptedSession());

                return $this->getParam('custom_session');
            }

            unset($this->params['custom_session']);
        }

        return false;
    }
} 