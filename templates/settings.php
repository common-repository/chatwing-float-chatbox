<?php

use ChatwingFloatUI\IntegrationPlugins\WordPress\Asset;
use ChatwingFloatUI\IntegrationPlugins\WordPress\DataModel;
use ChatwingFloatUI\IntegrationPlugins\WordPress\ShortCode;
if ( ! defined( 'ABSPATH' ) ) exit;
$model = DataModel::getInstance();
$count = 0;
?>
<h2 class="chatwing_float_ui"><?php _e("Chatboxes", BOOKING_CHATWING_TEXTDOMAIN) ?></h2>
<div class="wrap">
    <table class="widefat">
        <thead>
        <tr>
            <th>#</th>
            <th><?php _e('Name', CHATWING_FLOAT_UI_TEXTDOMAIN) ?></th>
            <th><?php _e('Alias', CHATWING_FLOAT_UI_TEXTDOMAIN); ?></th>
            <th><?php _e('ID', CHATWING_FLOAT_UI_TEXTDOMAIN); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($boxes)): ?>
            <?php foreach ($boxes as $box): ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $box['name']; ?></td>
                    <td><?php echo $box['alias']; ?></td>
                    <td><?php echo $box['id']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"><?php _e('No box', CHATWING_FLOAT_UI_TEXTDOMAIN); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <h2 class="chatwing_float_ui"><?php _e("Settings ", CHATWING_FLOAT_UI_TEXTDOMAIN) ?><i><a target="_blank" href="https://youtu.be/49NPvx9XAGc">(tutorial)</a></i></h2>

    <div id="poststuff" style="max-width: 800px;">
        <form class="pure-form pure-form-aligned pure-g" method="post"
              action="<?php echo admin_url('admin.php') ?>">
            <fieldset>
                <div class="pure-control-group">
                    <label
                        for="token"><?php _e('Access token', CHATWING_FLOAT_UI_TEXTDOMAIN) ?></label>
                    <input id="token" type="text" name="token">
                    <label for="">
                        <input type="checkbox" name="remove_token" id="remove_token" value="1">
                    Delete current token ?
                    </label>
                </div>
                <div class="pure-controls">
                    <input type="submit" onclick="myFunction()" class="pure-button pure-button-primary" value="<?php _e('Save', CHATWING_FLOAT_UI_TEXTDOMAIN) ?>">
                </div>
            </fieldset>
            <div style="display: none">
                <input type="hidden" name="action" value="chatwing_float_ui_save_settings">
                <?php wp_nonce_field('settings_save', 'nonce' ); ?>
            </div>
        </form>
    </div>
</div>