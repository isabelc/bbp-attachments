<input type="hidden" name="gdbbatt_forum_meta" value="edit"/>
<p>
    <strong class="label" style="width: 160px;">Disable Attachments:</strong>
    <label for="gdbbatt_disable" class="screen-reader-text">Disable Attachments:</label>
    <input type="checkbox" <?php if ($meta["disable"] == 1) {
        echo " checked";
    } ?> name="gdbbatt[disable]" id="gdbbatt_disable"/>
</p>
<p>
    <strong class="label" style="width: 160px;">Override Defaults:</strong>
    <label for="gdbbatt_to_override" class="screen-reader-text">Override Defaults:</label>
    <input type="checkbox" <?php if ($meta["to_override"] == 1) {
        echo " checked";
    } ?> name="gdbbatt[to_override]" id="gdbbatt_to_override"/>
</p>
<h4 style="font-size: 14px; margin: 3px 0 5px;">Settings to override:</h4>
<p>
    <strong class="label" style="width: 160px;">Maximum file size:</strong>
    <label for="gdbbatt_max_file_size" class="screen-reader-text">Maximum file size:</label>
    <br/><input step="1" min="1" type="number" class="widefat small-text" value="<?php echo $meta["max_file_size"]; ?>" name="gdbbatt[max_file_size]" id="gdbbatt_max_file_size"/>
    <span class="description">KB</span>
</p>
<p>
    <strong class="label" style="width: 160px;">Maximum files to upload:</strong>
    <label for="gdbbatt_max_to_upload" class="screen-reader-text">Maximum files to upload:</label>
    <br/><input step="1" min="1" type="number" class="widefat small-text" value="<?php echo $meta["max_to_upload"]; ?>" name="gdbbatt[max_to_upload]" id="gdbbatt_max_to_upload"/>
    <span class="description">at once</span>
</p>
<p>
    <strong class="label" style="width: 160px;">Hide list of attachments:</strong>
    <label for="gdbbatt_hide_from_visitors" class="screen-reader-text">Hide From Visitors:</label>
    <br/><input style="vertical-align: top; margin-top: 3px;" type="checkbox" <?php if ($meta["hide_from_visitors"] == 1) {
        echo " checked";
    } ?> name="gdbbatt[hide_from_visitors]" id="gdbbatt_hide_from_visitors"/>
    From visitors
</p>
