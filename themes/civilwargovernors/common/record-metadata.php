<?php foreach ($elementsForDisplay as $setName => $setElements): ?>
    <?php if ($setName == "CWG Documents"){ ?>
        <div class="element-set">
            <?php if ($showElementSetHeadings): ?>
                <h2><?php echo html_escape(__($setName)); ?></h2>
            <?php endif; ?>

            <?php foreach ($setElements as $elementName => $elementInfo): ?>
                <?php $blacklist = array("Document Title", "Source Country", "Source State", "Source City", "ISO Date of Creation", "DocTracker Number", "Transcription"); ?>
                <?php if ( !in_array($elementName, $blacklist, true ) ) { ?>
                    <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element">
                        <h3><?php echo html_escape(__($elementName)); ?></h3>
                        <?php foreach ($elementInfo['texts'] as $text): ?>
                            <div class="element-text"><?php echo $text; ?></div>
                        <?php endforeach; ?>
                    </div><!-- end element -->
                <?php } ?>
            <?php endforeach; ?>
          </div><!-- end element-set -->
      <?php } ?>
<?php endforeach;
