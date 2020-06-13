<?php if (isset($_GET["settings-updated"]) && $_GET["settings-updated"] == "true") { ?>
    <div class="updated settings-error" id="setting-error-settings_updated">
        <p><strong><?php _e("Settings saved.", "gd-bbpress-attachments"); ?></strong></p>
    </div>
<?php } ?>

<form action="" method="post">
    <?php wp_nonce_field("gd-bbpress-attachments"); ?>
    <div class="d4p-settings">
        <fieldset>
            <h3><?php _e("Display of image attachments", "gd-bbpress-attachments"); ?></h3>
            <p><?php _e("Attached images can be displayed as thumbnails, and from here you can control this.", "gd-bbpress-attachments"); ?></p>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="image_thumbnail_active"><?php _e("Activated", "gd-bbpress-attachments"); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["image_thumbnail_active"] == 1) {
                            echo " checked";
                        } ?> name="image_thumbnail_active"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="image_thumbnail_caption"><?php _e("With caption", "gd-bbpress-attachments"); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["image_thumbnail_caption"] == 1) {
                            echo " checked";
                        } ?> name="image_thumbnail_caption"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="image_thumbnail_inline"><?php _e("In line", "gd-bbpress-attachments"); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["image_thumbnail_inline"] == 1) {
                            echo " checked";
                        } ?> name="image_thumbnail_inline"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="image_thumbnail_css"><?php _e("CSS class", "gd-bbpress-attachments"); ?></label>
                    </th>
                    <td>
                        <input type="text" class="widefat" value="<?php echo $options["image_thumbnail_css"]; ?>" id="image_thumbnail_css" name="image_thumbnail_css"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="image_thumbnail_rel"><?php _e("REL attribute", "gd-bbpress-attachments"); ?></label>
                    </th>
                    <td>
                        <input type="text" class="widefat" value="<?php echo $options["image_thumbnail_rel"]; ?>" id="image_thumbnail_rel" name="image_thumbnail_rel"/><br/>
                        <em><?php _e("You can use these tags", "gd-bbpress-attachments"); ?>: %ID%, %TOPIC%</em>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
        <p class="submit">
            <input type="submit" value="<?php _e("Save Changes", "gd-bbpress-attachments"); ?>" class="button-primary gdbb-tools-submit" id="gdbb-att-images-submit" name="gdbb-att-images-submit"/>
        </p>
    </div>
    <div class="d4p-clear"></div>
</form>
