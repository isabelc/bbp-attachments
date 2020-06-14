<?php if (isset($_GET["settings-updated"]) && $_GET["settings-updated"] == "true") { ?>
    <div class="updated settings-error" id="setting-error-settings_updated">
        <p><strong>Settings saved.</strong></p>
    </div>
<?php } ?>

<form action="" method="post">
    <?php wp_nonce_field("gd-bbpress-attachments"); ?>
    <div class="d4p-settings">
        <fieldset>
            <h3>Global Attachments Settings</h3>
            <p>These settings can be overridden for individual forums.</p>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="max_file_size">Maximum file size</label>
                    </th>
                    <td>
                        <input step="1" min="1" type="number" class="widefat small-text" value="<?php echo $options["max_file_size"]; ?>" id="max_file_size" name="max_file_size"/>
                        <span class="description">KB</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="max_to_upload">Maximum files to upload</label>
                    </th>
                    <td>
                        <input step="1" min="1" type="number" class="widefat small-text" value="<?php echo $options["max_to_upload"]; ?>" id="max_to_upload" name="max_to_upload"/>
                        <span class="description">at once</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="hide_from_visitors">Hide attachments</label>
                    </th>
                    <td>
                        <input class="widefat" type="checkbox" <?php if ($options["hide_from_visitors"] == 1) {
                            echo " checked";
                        } ?> name="hide_from_visitors"/>
                        From visitors
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset>
            <h3>Users Upload Restrictions</h3>
            <p>Only users having one of the selected roles will be able to attach files.</p>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row">Allow upload to</th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span>Allow upload to</span></legend>
                            <?php foreach ($_user_roles as $role => $title) { ?>
                                <label for="roles_to_upload_<?php echo $role; ?>">
                                    <input type="checkbox" <?php if (!isset($options["roles_to_upload"]) || is_null($options["roles_to_upload"]) || in_array($role, $options["roles_to_upload"])) {
                                        echo " checked";
                                    } ?> value="<?php echo $role; ?>" id="roles_to_upload_<?php echo $role; ?>" name="roles_to_upload[]"/>
                                    <?php echo $title; ?>
                                </label><br/>
                            <?php } ?>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset>
            <h3>Topic and Reply Deleting</h3>
            <p>Select what to do with attachments when topic or reply with attachments is deleted.</p>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row"><label>Attachments Action</label></th>
                    <td>
                        <select name="delete_attachments" class="widefat">
                            <option value="detach"<?php if ($options["delete_attachments"] == "detach") {
                                echo ' selected="selected"';
                            } ?>>Leave in media library</option>
                            <option value="delete"<?php if ($options["delete_attachments"] == "delete") {
                                echo ' selected="selected"';
                            } ?>>Delete</option>
                            <option value="nohing"<?php if ($options["delete_attachments"] == "nohing") {
                                echo ' selected="selected"';
                            } ?>>Do nothing</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset>
            <h3>JavaScript and CSS Settings</h3>
            <p>If you use shortcodes to embed forums, and you rely on plugin to add JS and CSS, you also need to enable this option to skip checking for bbPress specific pages.</p>
            <p>Plugin will attempt to load files automatically when needed. If that fails, try using this option.</p>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="include_always">Always Include</label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["include_always"] == 1) {
                            echo " checked";
                        } ?> name="include_always"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
        <p class="submit">
            <input type="submit" value="Save Changes" class="button-primary gdbb-tools-submit" id="gdbb-attach-submit" name="gdbb-attach-submit"/>
        </p>
    </div>
    <div class="d4p-clear"></div>
</form>
