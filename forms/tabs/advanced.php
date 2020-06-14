<?php if (isset($_GET["settings-updated"]) && $_GET["settings-updated"] == "true") { ?>
    <div class="updated settings-error" id="setting-error-settings_updated">
        <p><strong>Settings saved.</strong></p>
    </div>
<?php } ?>

<form action="" method="post">
    <?php wp_nonce_field("gd-bbpress-attachments"); ?>
    <div class="d4p-settings">
        <fieldset>
            <h3>Error logging</h3>
            <p>Each failed upload will be logged in postmeta table. Administrators and topic/reply authors can see the log.</p>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="log_upload_errors">Activated</label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["log_upload_errors"] == 1) {
                            echo " checked";
                        } ?> name="log_upload_errors"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="errors_visible_to_admins">Visible to administrators</label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["errors_visible_to_admins"] == 1) {
                            echo " checked";
                        } ?> name="errors_visible_to_admins"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="errors_visible_to_moderators">Visible to moderators</label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["errors_visible_to_moderators"] == 1) {
                            echo " checked";
                        } ?> name="errors_visible_to_moderators"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="errors_visible_to_author">Visible to author</label>
                    </th>
                    <td>
                        <input type="checkbox" <?php if ($options["errors_visible_to_author"] == 1) {
                            echo " checked";
                        } ?> name="errors_visible_to_author"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset>
            <h3>Deleting attachments</h3>
            <p>Once uploaded and attached, attachments can be deleted. Only administrators and authors can do this.</p>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row"><label>Administrators</label></th>
                    <td>
                        <select name="delete_visible_to_admins" class="widefat">
                            <option value="no"<?php if ($options["delete_visible_to_admins"] == "no") {
                                echo ' selected="selected"';
                            } ?>>Don't allow to delete</option>
                            <option value="delete"<?php if ($options["delete_visible_to_admins"] == "delete") {
                                echo ' selected="selected"';
                            } ?>>Delete from Media Library</option>
                            <option value="detach"<?php if ($options["delete_visible_to_admins"] == "detach") {
                                echo ' selected="selected"';
                            } ?>>Only detach from topic/reply</option>
                            <option value="both"<?php if ($options["delete_visible_to_admins"] == "both") {
                                echo ' selected="selected"';
                            } ?>>Allow both delete and detach</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Moderators</label></th>
                    <td>
                        <select name="delete_visible_to_moderators" class="widefat">
                            <option value="no"<?php if ($options["delete_visible_to_moderators"] == "no") {
                                echo ' selected="selected"';
                            } ?>>Don't allow to delete</option>
                            <option value="delete"<?php if ($options["delete_visible_to_moderators"] == "delete") {
                                echo ' selected="selected"';
                            } ?>>Delete from Media Library</option>
                            <option value="detach"<?php if ($options["delete_visible_to_moderators"] == "detach") {
                                echo ' selected="selected"';
                            } ?>>Only detach from topic/reply</option>
                            <option value="both"<?php if ($options["delete_visible_to_moderators"] == "both") {
                                echo ' selected="selected"';
                            } ?>>Allow both delete and detach</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Author</label></th>
                    <td>
                        <select name="delete_visible_to_author" class="widefat">
                            <option value="no"<?php if ($options["delete_visible_to_author"] == "no") {
                                echo ' selected="selected"';
                            } ?>>Don't allow to delete</option>
                            <option value="delete"<?php if ($options["delete_visible_to_author"] == "delete") {
                                echo ' selected="selected"';
                            } ?>>Delete from Media Library</option>
                            <option value="detach"<?php if ($options["delete_visible_to_author"] == "detach") {
                                echo ' selected="selected"';
                            } ?>>Only detach from topic/reply</option>
                            <option value="both"<?php if ($options["delete_visible_to_author"] == "both") {
                                echo ' selected="selected"';
                            } ?>>Allow both delete and detach</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>

        <p class="submit">
            <input type="submit" value="Save Changes" class="button-primary gdbb-tools-submit" id="gdbb-att-advanced-submit" name="gdbb-att-advanced-submit"/>
        </p>
    </div>
    <div class="d4p-clear"></div>
</form>
