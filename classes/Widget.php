<?php namespace ChatwingFloatUI\IntegrationPlugins\WordPress;
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Class Widget
 * @package ChatwingFloatUI\IntegrationPlugins\WordPress
 * @author chatwing
 */

class Widget extends \WP_Widget
{
    function __construct()
    {
        parent::__construct('chatwing_float_ui_cb', __('Chatwing Float Chatbox', CHATWING_FLOAT_UI_TEXTDOMAIN));
    }

    public function widget($args, $instance)
    {
        $defaultAttributes = array(
            'title' => ''
        );

        $instance = array_merge($defaultAttributes, $instance);
        echo $args['before_widget'];
        echo $args['before_title'] . $instance['title'] . $args['after_title'];
        echo ShortCode::render(array(
            'id' => $instance['floatUI'],
            'width' => !empty($instance['width']) ? $instance['width'] : '',
            'height' => !empty($instance['height']) ? $instance['height'] : '',
            'enable_custom_login' => !empty($instance['enable_custom_login']) ? $instance['enable_custom_login'] : false,
            'custom_login_secret' => !empty($instance['custom_login_secret']) ? $instance['custom_login_secret'] : ''
        ));
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $boxes = DataModel::getInstance()->getBoxList();
        $currentID = !empty($instance['floatUI']) ? $instance['floatUI'] : null;
        ?>
        <p>
            <label
                for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title", CHATWING_FLOAT_UI_TEXTDOMAIN); ?></label>
            <input type="text" class="widefat"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo !empty($instance['title']) ? $instance['title'] : '' ?>"/>
        </p>
        <p>
            <label
                for="<?php echo $this->get_field_id('floatUI'); ?>"><?php _e('Chatbox', CHATWING_FLOAT_UI_TEXTDOMAIN); ?></label>
            <select name="<?php echo $this->get_field_name('floatUI'); ?>"
                    id="<?php echo $this->get_field_id('floatUI'); ?>">
                <?php if (!empty($boxes)): foreach ($boxes as $box): ?>
                    <option
                        value="<?php echo $box['id'] ?>" <?php if ($box['id'] == $currentID) echo 'selected="selected"'; ?>><?php echo $box['alias']; ?></option>
                <?php endforeach;endif; ?>
            </select>
        </p>
    <?php
    }

    public function update($new, $old)
    {
        return $new;
    }
}