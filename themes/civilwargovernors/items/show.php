<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'items show')); ?>

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
  <?php elseif (metadata('item', 'item_type_name') == 'CWGK Person'): ?>
    <?php include 'network.php' ?>
  <?php endif; ?>

  <!-- tabs -->
  <div id="tabs">
      <ul>
        <li><a href="#tabs-meta">Metadata</a></li>
        <li><a href="#tabs-cite">Citation</a></li>
        <li><a href="#tabs-down">Download</a></li>
      </ul>
      <div id="tabs-meta">
        <!-- metadata -->
        <?php echo all_element_texts('item'); ?>
      </div>
      <div id="tabs-cite">
        <!-- The following prints a citation for this item. -->
        <div id="item-citation" class="element">
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
        </div>
      </div>
      <div id="tabs-down" class="element-text">
        <?php $identifier = metadata('item', array('Dublin Core', 'Identifier')); ?>
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
               <p><em>Early Access</em> is a work in progress. Help us improve our edition by suggesting a correction for this document. <div class="correction button"><a href='<?php echo url('contact'); ?>?document_id=<?php echo $identifier; ?>&document_title=<?php echo urlencode(metadata('item', array('Dublin Core', 'Title'))); ?>'>Suggest a correction</a></div></p>

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
