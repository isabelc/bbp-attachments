<?php if (isset($_GET["settings-updated"]) && $_GET["settings-updated"] == "true") { ?>
    <div class="updated settings-error" id="setting-error-settings_updated">
        <p><strong>Settings saved.</strong></p>
    </div>
<?php } ?>

<form action="" method="post">
    <?php wp_nonce_field("gd-bbpress-attachments"); ?>
    <div class="d4p-settings">
        <fieldset>
            <h3>Display of image attachments</h3>
            <p>Attached images can be displayed as thumbnails, and from here you can control this.</p>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="image_thumbnail_active">Activated</label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["image_thumbnail_active"] == 1) {
                            echo " checked";
                        } ?> name="image_thumbnail_active"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="image_thumbnail_caption">With caption</label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["image_thumbnail_caption"] == 1) {
                            echo " checked";
                        } ?> name="image_thumbnail_caption"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
        <p class="submit">
            <input type="submit" value="Save Changes" class="button-primary gdbb-tools-submit" id="gdbb-att-images-submit" name="gdbb-att-images-submit"/>
        </p>
    </div>
    <div class="d4p-clear"></div>
</form>
