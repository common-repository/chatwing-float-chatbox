<?php
/**
 * @author  chatwing <dev@chatwing.com>
 * @package ChatwingFloatUI\SDK\Exception
 */
namespace ChatwingFloatUI\Exception;
if ( ! defined( 'ABSPATH' ) ) exit;
class ChatwingFloatUIException extends \Exception
{
    protected $httpCode = 0;
    protected $params = array();

    /**
     * @param array   $errorData [description]
     * @param integer $httpCode  [description]
     */
    public function __construct($errorData = array(), $httpCode = 0)
    {
        if (is_string($errorData)) {
            $errorData = array('message' => $errorData);
        }
        $message = isset($errorData['message']) ? $errorData['message'] : '';
        $code = isset($errorData['code']) ? $errorData['code'] : 0;
        parent::__construct($message, $code, null);

        if (isset($errorData['params'])) {
            $this->params = $errorData['params'];
        }
        if ($httpCode) {
            $this->httpCode = $httpCode;
        }
    }

    public function getParams()
    {
        return $this->params;
    }
} 