<div id="bbp-attachment-toggle-wrap"><span id="bbp-attachment-toggle"><span>&#128206;  Upload Image</span></span></div>
<fieldset id="bbp-attachment-upload" class="bbp-form palebg">
	<p class="bbp-attachments-form">
		<label for="bbp_topic_tags">Attachments:</label><br/>
		<input type="file" size="40" name="d4p_attachment[]" class="bbp-attachment-field-input"> <a href="#" class="d4p-attachment-remove">Remove</a><br>
		<a class="d4p-attachment-addfile" href="#">Add another file</a>
	</p>
	<div class="bbp-template-notice">
		<p><?php
			$size = $file_size < 1024 ? $file_size." KB" : floor($file_size / 1024)." MB";
			printf("Maximum file size allowed is %s.", $size);
			?></p>
	</div>	
</fieldset>