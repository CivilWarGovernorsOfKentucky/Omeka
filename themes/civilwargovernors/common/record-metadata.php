<?php
$blacklists = array();
$blacklists['CWG Documents'] = array("Document Title", "Source Country", "Source State", "Source City", "ISO Date of Creation", "DocTracker Number", "Transcription");
$blacklists['CWGK Person Item Type Metadata'] = array("Name", "Bibliography", "Biographical Text");
?>
<?php foreach ($elementsForDisplay as $setName => $setElements): ?>
  <div class="element-set">
    <?php if (array_key_exists($setName, $blacklists)): ?>
      <?php if ($showElementSetHeadings): ?>
          <h2><?php echo html_escape(__($setName)); ?></h2>
      <?php endif; ?>
      <?php foreach ($setElements as $elementName => $elementInfo): ?>
          <?php if ( !in_array($elementName, $blacklists[$setName], true ) ) { ?>
              <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element">
                  <h3><?php echo html_escape(__($elementName)); ?></h3>
                  <?php foreach ($elementInfo['texts'] as $text): ?>
                      <div class="element-text"><?php echo $text; ?></div>
                  <?php endforeach; ?>
              </div><!-- end element -->
          <?php } ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div><!-- end element-set -->
<?php endforeach;
