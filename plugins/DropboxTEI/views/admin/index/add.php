<?php
queue_js_file('items');
queue_js_file('tabs');
queue_css_file('dropboxTEI');

echo head(array('title' => 'Dropbox TEI', 'bodyclass' => 'dropboxTEI'));

?>

<div id="primary">
    <?php if ($fileErrors): ?>
        <div id="dropboxTEI_not_uploaded_filenames">
            <p>The following file(s) could NOT be uploaded:</p>
            <ul>
            <?php foreach ($fileErrors as $fileName=>$errorMessage): ?>
                <li><?php echo html_escape($fileName); ?><br/><?php echo html_escape($errorMessage);?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if ($uploadedFileNames): ?>
        <div id="dropboxTEI_not_uploaded_filenames">
            <p>The following file(s) were successfully uploaded:</p>
            <ul>
            <?php foreach ($uploadedFileNames as $fileName): ?>
                <li><?php echo html_escape($fileName); ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php foot();
