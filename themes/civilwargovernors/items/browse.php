<?php
$pageTitle = __('Browse Items');
echo head(array('title'=>$pageTitle,'bodyclass' => 'items browse'));
?>


<h2 class="browsetitle"><?php echo $total_results; ?> Results for: <?php echo item_search_filters(); ?></h2>

<!--<nav class="items-nav navigation secondary-nav">
    <?php echo public_nav_items(); ?>
</nav>-->

<div class="browse-divider"><hr class="star" /></div>

<?php //echo pagination_links(); ?>

<?php if ($total_results > 0): ?>

<?php
$sortLinks[__('Title')] = 'Dublin Core,Title';
$sortLinks[__('Date')] = 'CWG Documents,ISO Date of Creation';
$sortLinks[__('Accession Number')] = 'CWG Documents,Accession Number';
?>
<div id="sort-links">
    <span class="sort-label"><?php echo __('SORT'); ?></span><?php echo browse_sort_links($sortLinks); ?>
</div>

<?php endif; ?>

<div class="browse-divider"><hr class="star" /></div>

<?php foreach (loop('items') as $item): ?>
<div class="item hentry">

    <div class="item-meta">
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
<!--    replacing this code to modify the behavior of the 'next' and 'previous' buttons per http://omeka.readthedocs.io/en/latest/Tutorials/recipes/retainingSearchSortOrderWhenPaging.html#problem
    <h2><?php echo link_to_item(metadata('item', array('Dublin Core', 'Title')), array('class'=>'permalink')); ?></h2>
-->
<!-- replacement code -->
<?php
if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
{

    $searchlink = record_url('item').'?' . $_SERVER['QUERY_STRING'];

    echo '<h2><a href="'.$searchlink.'">'. metadata('item', array('Dublin Core','Title')).'</a></h2>';
}

else
{
    echo '<h2>'.link_to_item(metadata('item', array('Dublin Core','Title')), array('class'=>'permalink')).'</h2>';
}
?>
<!-- end replacement code -->

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


    <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>

    </div><!-- end class="item-meta" -->
</div><!-- end class="item hentry" -->
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<!-- <div id="outputs">
    <span class="outputs-label"><?php echo __('Output Formats'); ?></span>
    <?php echo output_format_list(false); ?>
</div> -->

<?php fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this)); ?>

<?php echo foot(); ?>
