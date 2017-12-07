<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'items show')); ?>
<?php $entity_item_types = array("CWGK Person", "CWGK Organization", "CWGK Place", "CWGK Geographical Feature"); ?>
<?php $identifier = metadata('item', array('Dublin Core', 'Identifier')); ?>
<h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>
<div class="browse-divider"><hr class="star" /></div>
<div id="primary">

  <!-- pdf viewer -->
  <!-- The following returns all of the files associated with an item. -->
  <!-- xml file link is hidden in the stylesheet -->

  <?php if (metadata('item', 'item_type_name') == 'CWGK Early Access Document'): ?>
    <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">
        <div class="element-text"><?php echo files_for_item(); ?></div>
    </div>
    <?php endif; ?>
  <?php endif; ?>
  <?php if (metadata('item', 'item_type_name') == 'CWGK Person' || metadata('item', 'item_type_name') == 'CWGK Organization') { ?>
    <?php include 'network.php' ?>
  <?php } elseif (metadata('item', 'item_type_name') == 'CWGK Place') { ?>
        <div id="itemfiles" class="element">
          <div class="element-text"><img src="<?php echo("http://$_SERVER[HTTP_HOST]/themes/civilwargovernors/images/P_icon_KY.png") ?>" width="100%" height="auto"/></div>
        </div>
  <?php } elseif (metadata('item', 'item_type_name') == 'CWGK Geographical Feature') { ?>
        <div id="itemfiles" class="element">
          <div class="element-text"><img src="<?php echo("http://$_SERVER[HTTP_HOST]/themes/civilwargovernors/images/G_icon_KY.png") ?>" width="100%" height="auto"/></div>
        </div>
  <?php } ?>

  <!-- tabs -->
  <div id="tabs">
      <ul>
        <li><a href="#tabs-meta">Metadata</a></li>
        <li><a href="#tabs-cite">Citation</a></li>
        <?php if (in_array(metadata('item', 'item_type_name'), $entity_item_types)): ?>
        <li><a href="#tabs-documents">Documents</a></li>
        <?php endif ?>
        <li><a href="#tabs-down">Download</a></li>
      </ul>
      <div id="tabs-meta">
        <!-- metadata -->
        <?php echo all_element_texts('item'); ?>
      </div>
      <div id="tabs-cite">
        <!-- The following prints a citation for this item. -->
        <div id="item-citation" class="element">
          <?php if (metadata('item', 'item_type_name') == 'CWGK Early Access Document') { ?>
            <!--<div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>-->
            <div class="element-text">
              <?php if (metadata('item', 'item_type_name') == 'CWGK Early Access Document'): ?>
                <?php echo metadata('item', array('CWG Documents', 'Document Title')); ?>,&nbsp;
                <?php echo metadata('item', array('CWG Documents', 'Date of Creation')); ?>,&nbsp;
                <?php echo metadata('item', array('CWG Documents', 'Collection')); ?>,&nbsp;
                <?php echo metadata('item', array('CWG Documents', 'Item Location')); ?>,&nbsp;
                <?php echo metadata('item', array('CWG Documents', 'Repository')); ?>,&nbsp;
                <?php echo metadata('item', array('CWG Documents', 'Source City')); ?>,&nbsp;
                <?php echo metadata('item', array('CWG Documents', 'Source State')); ?>.&nbsp;
                Accessed via the <em>Civil War Governors of Kentucky Digital Documentary Edition: Early Access</em>,
                discovery.civilwargovernors.org/document/<?php echo metadata('item', array('CWG Documents', 'Accession Number')); ?>,
                (accessed <?php echo date('F j, Y'); ?>).
              <?php endif ?>
            </div>
          <?php } else { ?>
            <div class="element-text">
              "<?php echo metadata('item', array('Dublin Core', 'Title')); ?>" in the <em>Civil War Governors of Kentucky Digital Documentary Edition</em>,
              discovery.civilwargovernors.org/document/<?php echo $identifier; ?>, (accessed <?php echo date('F j, Y'); ?>). 
            </div>
          <?php } ?>
        </div>
      </div>
      <?php if (in_array(metadata('item', 'item_type_name'), $entity_item_types)): ?>
      <div id="tabs-documents">
        <ul id='entity-document-list'></ul>
        <script>
          jQuery(document).ready(function() {
            jQuery.ajax({
              url: "http://test.mashbill.discovery.civilwargovernors.org/entities/show_documents/<?php echo $identifier;?>",
              crossDomain: true
            }).done(function(data) {
              data.forEach (function(cwgk_doc) {
                jQuery('#entity-document-list').append("<li><a href='<?php echo "http://$_SERVER[HTTP_HOST]/document/"?>" + cwgk_doc.cwgk_id + "'>" + cwgk_doc.cwgk_id + "</a></li>")
              });
            });
          });
        </script>
      </div>
      <?php endif ?>
      <div id="tabs-down" class="element-text">
      <?php if (metadata('item', 'item_type_name') == 'CWGK Early Access Document'): ?>
        <script>
          // ajax call to create branded pdf for download
          jQuery(document).ready(function(){
              jQuery('#pdfform').submit(function(event){

                var identifier = jQuery("#identifier").val();
                var parameters = { identifier : identifier};

              jQuery.ajax({
                  url: 'http://tcpdf.discovery.civilwargovernors.org/createpdf.php',
                  data: parameters,

                  dataType: 'text'
                })

                .done(function(data) {
                    window.open(
                      data,
                    '_blank' );

                });

                // don't reload the page
                event.preventDefault();
              });
            });
          </script>

          <form id="pdfform" action="http://tcpdf.discovery.civilwargovernors.org/createpdf.php" method="get">
            <input id="identifier" type="hidden" value="<?php echo $identifier; ?>" name="identifier" />
            <input id="brandPDF" type="submit"  class="btn-success" value="Download PDF" onClick="ga('send', 'event', 'Download PDF', '<?php echo $identifier; ?>')"/>
          </form>
        <?php endif ?>  
        <div id="downloadXML"><a onClick="ga('send', 'event', 'Download XML', '<?php echo $identifier; ?>')" href="/files/xml/<?php echo $identifier;?>.xml" target="_blank" >Download XML</a></div>
      </div>
    </div>
    <div class="browse-divider"><hr class="star" /></div>

</div><!-- end primary -->

<aside id="sidebar">
  <?php $files = $item->Files;
        if($files) {
          foreach($files as $file) {
            $filename = $file['filename'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext == "xml") {
               echo get_specific_plugin_hook_output("DropboxTEI", "public_items_show", $filename); ?>
               <br />
               <div class="browse-divider"><hr class="star" /></div>
               <br />
               <p><em>Civil War Governors of Kentucky</em> is always evolving. Help us improve the edition by suggesting a correction or addition to this record. <div class="correction button"><a href='<?php echo url('contact'); ?>?document_id=<?php echo $identifier; ?>&document_title=<?php echo urlencode(metadata('item', array('Dublin Core', 'Title'))); ?>'>Suggest a correction</a></div></p>

     <?php  }

          }
        }
  ?>

    <!-- If the item belongs to a collection, the following creates a link to that collection. -->
    <!--<?php if (metadata('item', 'Collection Name')): ?>
    <div id="collection" class="element">
        <h2><?php echo __('Collection'); ?></h2>
        <div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>
    </div>
  <?php endif; ?>-->

    <!-- The following prints a list of all tags associated with the item -->
    <!--<?php if (metadata('item', 'has tags')): ?>
    <div id="item-tags" class="element">
        <h2><?php echo __('Tags'); ?></h2>
        <div class="element-text"><?php echo tag_string('item'); ?></div>
    </div>
  <?php endif;?>-->



</aside>

<ul class="item-pagination navigation">
<!--    replacing this code to modify the behavior of the 'next' and 'previous' buttons per http://omeka.readthedocs.io/en/latest/Tutorials/recipes/retainingSearchSortOrderWhenPaging.html#problem
    <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
    <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
-->
<!-- replacement code -->
<!--just getting rid of the next/previous links for now
<?php custom_paging(); ?>
 -->
<!-- end replacement code -->


</ul>

<div class="divider"><hr class="kentucky-star"/></div>

<?php echo foot(); ?>
