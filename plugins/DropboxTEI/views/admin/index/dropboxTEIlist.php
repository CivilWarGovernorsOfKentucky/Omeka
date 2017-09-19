<?php if (!dropboxTEI_can_access_files_dir()): ?>
    <p class="dropboxTEI-alert error"><?php echo __('The DropboxTEI files directory must be both readable and writable.'); ?></p>
<?php else: ?>
    <?php $fileNames = dropboxTEI_dir_list(dropboxTEI_get_files_dir_path()); ?>
    <?php if (!$fileNames): ?>
        <p><strong><?php echo __('No files have been uploaded to the dropboxTEI.'); ?></strong></p>
    <?php else: ?>
        <script type="text/javascript">
            function dropboxTEISelectAllCheckboxes(checked) {
                jQuery('#dropboxTEI-file-checkboxes tr:visible input').each(function() {
                    this.checked = checked;
                });
                jQuery('#dropboxTEI-file-checkboxes').trigger('dropboxTEI-all-toggled');
            }

            function dropboxTEIFilterFiles() {
                var filter = jQuery.trim(jQuery('#dropboxTEI-file-filter').val().toLowerCase());
                var someHidden = false;
                jQuery('#dropboxTEI-file-checkboxes input').each(function() {
                    var v = jQuery(this);
                    if (filter != '') {
                        if (v.val().toLowerCase().indexOf(filter) != -1) {
                            v.parent().parent().show();
                        } else {
                            v.parent().parent().hide();
                            someHidden = true;
                        }
                    } else {
                        v.parent().parent().show();
                    }
                });
                jQuery('#dropboxTEI-show-all').toggle(someHidden);
            }

            function dropboxTEINoEnter(e) {
                var e  = (e) ? e : ((event) ? event : null);
                var node = (e.target) ? e.target : ((e.srcElement) ? e.srcElement : null);
                if ((e.keyCode == 13) && (node.type=="text")) {return false;}
            }

            jQuery(document).ready(function () {
                jQuery('#dropboxTEI-select-all').click(function () {
                    dropboxTEISelectAllCheckboxes(this.checked);
                });

                jQuery('#dropboxTEI-show-all').click(function (event) {
                    event.preventDefault();
                    jQuery('#dropboxTEI-file-filter').val('');
                    dropboxTEIFilterFiles();
                });

                jQuery('#dropboxTEI-file-filter').keyup(function () {
                    dropboxTEIFilterFiles();
                }).keypress(dropboxTEINoEnter);

                jQuery('.dropboxTEI-js').show();
                jQuery('#dropboxTEI-show-all').hide();
            });
        </script>

        <p class="dropboxTEI-js" style="display:none;">
            <?php echo __('Filter files by name:'); ?>
            <input type="text" id="dropboxTEI-file-filter">
            <button type="button" id="dropboxTEI-show-all" class="blue"><?php echo __('Show All'); ?></button>
        </p>
        <table>
            <colgroup>
                <col style="width: 2em">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th><input type="checkbox" id="dropboxTEI-select-all" class="dropboxTEI-js" style="display:none"></th>
                    <th><?php echo __('File Name'); ?></th>
                </tr>
            </thead>
            <tbody id="dropboxTEI-file-checkboxes">
            <?php foreach ($fileNames as $fileName): ?>
                <tr><td><input type="checkbox" name="dropboxTEI-files[]" value="<?php echo html_escape($fileName); ?>"/></td><td><?php echo html_escape($fileName); ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif ?>
