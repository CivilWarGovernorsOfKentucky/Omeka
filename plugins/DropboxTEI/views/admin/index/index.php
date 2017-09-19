<?php
    queue_js_file('items');
    queue_js_file('tabs');
    queue_css_file('dropboxTEI');
    echo head(array('title' => __('Dropbox TEI'), 'bodyclass' => 'DropboxTEI'));
    $tagDelimiter = get_option('tag_delimiter');
  ?>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function () {
    Omeka.Items.tagDelimiter = <?php echo js_escape($tagDelimiter); ?>;
    Omeka.Items.tagChoices('#dropbox-tei-tags', <?php echo js_escape(url(array('controller' => 'tags', 'action' => 'autocomplete'), 'default', array(), true)); ?>);
});
//]]>
</script>
<?php echo flash(); ?>
<p>
<?php
echo __("To make files available to Dropbox TEI, upload them to the Dropbox TEI plugin's "
    . "files/ folder on the server.  Dropbox TEI files can be added in bulk to your site "
    . "from this page.");
?>
</p>
<form action="<?php echo html_escape(url(array('action'=>'add'))); ?>" method="post" accept-charset="utf-8">
    <section class="seven columns alpha">
        <h2><?php echo __('Batch Add Items'); ?></h2>
        <p>
        <?php
        echo __('For each file selected, a new item will be created. '
            . 'The properties set to the right will be applied to each new item.');
        ?>
        </p>
        <?php dropboxTEI_list(); ?>
    </section>
    <section class="three columns omega">
        <div id="save" class="panel">
            <input type="submit" class="submit big green button" name="submit" id="dropboxTEI-upload-files" value="<?php echo __('Upload Files as Items'); ?>" />
            <div id="public-featured">
                <div class="public">
                    <label for="dropbox-tei-public"><?php echo __('Public'); ?></label>
                    <?php echo $this->formCheckbox('dropbox-tei-public', null, array('checked' => true)); ?>
                </div>
                <div class="featured">
                    <label for="dropbox-tei-featured"><?php echo __('Featured'); ?></label>
                    <?php echo $this->formCheckbox('dropbox-tei-featured'); ?>
                </div>
            </div>
            <div id="collection-form" class="field">
                <label for="dropbox-tei-collection-id"><?php echo __('Collection'); ?></label>
                <div class="inputs">
                    <?php
                    echo $this->formSelect(
                        'dropbox-tei-collection-id',
                        null,
                        array(),
                        get_table_options('Collection')
                    );
                    ?>
                </div>
            </div>
            <div id="tags-form" class="field">
                <label for="dropbox-tei-tags"><?php echo __('Tags'); ?></label>
                <div class="inputs">
                    <?php echo $this->formText('dropbox-tei-tags'); ?>
                    <p class="explanation"><?php echo __('Separate tags with %s', option('tag_delimiter')); ?></p>
                </div>
            </div>
        </div>
    </section>
</form>
<script type="text/javascript">
jQuery('document').ready(function () {
    function toggleUploadButton() {
        jQuery('#dropboxTEI-upload-files').prop('disabled',
            !jQuery('input[name="dropboxTEI-files[]"]:checked').length);
    }

    toggleUploadButton();
    jQuery('input[name="dropboxTEI-files[]"]').change(toggleUploadButton);
    jQuery('#dropboxTEI-file-checkboxes').on('dropboxTEI-all-toggled', toggleUploadButton);
});
</script>
<?php echo foot();
