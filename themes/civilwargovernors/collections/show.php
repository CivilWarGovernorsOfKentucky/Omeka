<?php
$collectionTitle = strip_formatting(metadata('collection', array('Dublin Core', 'Title')));
?>

<?php echo head(array('title'=> $collectionTitle, 'bodyclass' => 'collections show')); ?>

<h1><?php echo $collectionTitle; ?></h1>

<div class="browse-divider"><hr class="star" /></div>

<?php $collectionDescription = metadata($collection, array('Dublin Core', 'Description'), array('snippet' => 150));?>
    <?php if ($collectionDescription): ?>
        <p class="collection-description"><?php echo $collectionDescription; ?></p>
    <?php endif; ?>
    <?php if (metadata('collection', 'total_items') > 0):
            $cleanCollectionTitle = htmlspecialchars_decode($collectionTitle, ENT_QUOTES);
            $encodedCollectionTitle = urlencode($cleanCollectionTitle);
            $xCollectionTitle = str_replace('%22', '%5C%22', $encodedCollectionTitle);
            $collectionUrl = "/solr-search?q=&facet=59_s%3A%22" . $xCollectionTitle . "%22";
          ?>

    <p><a href="<?php echo $collectionUrl; ?>">Browse all documents in this collection</a></p>

  <?php endif; ?>
  <div class="browse-divider"><hr class="star" /></div>


<div id="collection-items">
<?php if (metadata('collection', 'total_items') > 0):?>
        <?php foreach (loop('items') as $item): ?>
        <?php $itemTitle = strip_formatting(metadata('item', array('Dublin Core', 'Title'))); ?>
        <div class="item hentry">

          <?php if (metadata('item', 'has files')):

            foreach($item->Files as $file){
              if($file['mime_type']=='application/pdf' && $file['has_derivative_image']==1){

                ?>
                <div class="item-img">
                  <?php  echo file_image('square_thumbnail', array(), $file); ?>
                </div>
            <?php  }
            }

          endif; ?>

            <h2><?php echo link_to_item($itemTitle, array('class'=>'permalink')); ?></h2>

            <?php if ($date = metadata('item', array('CWG Documents', 'Date of Creation'), array('snippet'=>250))): ?>
            <div class="item-description">
                <?php echo $date; ?>
            </div>
            <?php endif; ?>
            <?php if ($genre = metadata('item', array('CWG Documents', 'Document Genre'), array('snippet'=>250))): ?>
            <div class="item-description">
                <?php echo $genre; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

    <?php else: ?>
        <!--<p><?php echo __("There are currently no items within this collection."); ?></p>-->
    <?php endif; ?>
</div><!-- end collection-items -->

<?php fire_plugin_hook('public_collections_show', array('view' => $this, 'collection' => $collection)); ?>

<?php echo foot(); ?>
