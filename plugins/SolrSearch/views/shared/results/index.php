<?php

/**
 * @package     omeka
 * @subpackage  solr-search
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

?>


<?php queue_css_file('results'); ?>
<?php echo head(array('title' => __('Search the Collections')));?>


<!--<h1><?php echo __('Search the Collection'); ?></h1>-->


<!-- Search form. -->
<div class="solr">
  <div id="search-header">
    <p><span>&#9733; </span>&nbsp;Search Collection&nbsp;<span> &#9733;</span></p>
  </div>
  <form id="solr-search-form">
    <!--<input type="submit" value="Search" />-->

    <span class="float-wrap">
      <input type="text" title="<?php echo __('Search keywords') ?>" name="q" value="<?php
        echo array_key_exists('q', $_GET) ? $_GET['q'] : '';
      ?>" />
    </span>
    <button type="submit" value="Search"></button>
  </form>
  <div id="advanced-search-link">
    <a href="/items/search" >Advanced Search</a>
  </div>
</div>


<!-- Applied facets. -->
<div id="solr-applied-facets">

  <ul>

    <!-- Get the applied facets. -->
    <?php foreach (SolrSearch_Helpers_Facet::parseFacets() as $f): ?>
      <li>

        <!-- Facet label. -->
        <?php $label = SolrSearch_Helpers_Facet::keyToLabel($f[0]); ?>
        <?php if($label == "ISO Date of Creation"){
          ?> <span class="applied-facet-label">Date of Creation:</span> <?php
        } else { ?>
        <span class="applied-facet-label"><?php echo $label; ?>:</span>
        <?php } ?>

        <?php if($f[1]== "Broadside of \\"){ ?>
        <span class="applied-facet-value"><?php echo 'Broadside of "Inaugural address of the Provisional Governor of Kentucky" 1862 Richard Hawes'; ?></span>
        <?php } else { ?>
          <span class="applied-facet-value"><?php echo $f[1]; ?></span>

        <?php } ?>
        <!-- Remove link. -->
        <?php $url = SolrSearch_Helpers_Facet::removeFacet($f[0], $f[1]); ?>
        <a class="remove-link" href="<?php echo $url; ?>">X</a>

      </li>
    <?php endforeach; ?>

  </ul>

</div>


<!-- Facets. -->
<div id="solr-facets">

  <?php foreach ($results->facet_counts->facet_fields as $name => $facets): ?>

    <!-- Does the facet have any hits? -->
    <?php if (count(get_object_vars($facets))): ?>

      <!-- Facet label. -->
      <?php $label = SolrSearch_Helpers_Facet::keyToLabel($name); ?>
      <?php if ($label == "ISO Date of Creation"){?>
        <h3>Date of Creation</h3>
      <?php } else { ?>
      <h3><?php echo $label; ?></h3>
      <?php } ?>
      <ul>
        <!-- Facets. -->
        <?php foreach ($facets as $value => $count): ?>
          <li class="<?php echo $value; ?>">

            <!-- Facet URL. -->
            <?php $url = SolrSearch_Helpers_Facet::addFacet($name, $value); ?>

            <!-- Facet link. -->
            <a href="<?php echo $url; ?>" class="facet-value">
              <?php echo $value; ?>
            </a>

            <!-- Facet count. -->
            (<span class="facet-count"><?php echo $count; ?></span>)

          </li>
        <?php endforeach; ?>
      </ul>

    <?php endif; ?>

  <?php endforeach; ?>
</div>


<!-- Results. -->
<div id="solr-results">

  <!-- Number found. -->
  <h2 id="num-found">
    <?php echo $results->response->numFound;
    if ($results->response->numFound == 1){
      echo " result ";
    } else {
      echo " results ";
    }
    if (!empty($_GET['q'])){
      echo "for ";
      $searchterms = array_key_exists('q', $_GET) ? $_GET['q'] : '';
      // make the solr search advanced search indexes more user-friendly by
      // replacing the id number with the name of the index
      $searchterms = preg_replace('/52_t/', 'Accession Number', $searchterms);
      $searchterms = preg_replace('/59_t/', 'Collection', $searchterms);
      $searchterms = preg_replace('/62_t/', 'Date of Creation', $searchterms);
      $searchterms = preg_replace('/64_t/', 'Dates Mentioned', $searchterms);
      $searchterms = preg_replace('/65_t/', 'Document Genre', $searchterms);
      $searchterms = preg_replace('/53_t/', 'Document Title', $searchterms);
      $searchterms = preg_replace('/54_t/', 'Editorial Note', $searchterms);
      $searchterms = preg_replace('/60_t/', 'Item Location', $searchterms);
      $searchterms = preg_replace('/63_t/', 'Place of Creation', $searchterms);
      $searchterms = preg_replace('/58_t/', 'Repository', $searchterms);
      $searchterms = preg_replace('/66_t/', 'Transcription', $searchterms);

      echo $searchterms;
    }?>
  </h2>
  <div class="browse-divider"><hr class="star" /></div>

  <?php foreach ($results->response->docs as $doc):

      // if the result is an item, loop through the item results
      if ($doc->resulttype == 'Item'):
        $item = get_db()->getTable($doc->model)->find($doc->modelid);
        set_current_record('item',$item);
  ?>

        <!-- Document. -->
        <div class="result">

          <!-- Thumbnail. -->
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

          <!-- Header. -->
          <div class="result-header">

            <!-- Record URL. -->
            <?php $url = SolrSearch_Helpers_View::getDocumentUrl($doc); ?>

            <!-- Title. -->
            <h2><a href="<?php echo $url; ?>" class="result-title"><?php
                    $title = is_array($doc->title) ? $doc->title[0] : $doc->title;
                    if (empty($title)) {
                        $title = '<i>' . __('Untitled') . '</i>';
                    }
                    echo $title;
                ?></a></h2>
                <!-- Date. -->
                <?php if ($date = metadata('item', array('CWG Documents', 'Date of Creation'))): ?>
                  <div class="item-description">
                    <?php echo $date; ?>
                  </div>
                <?php endif; ?>
                <!-- Genre. -->
                <?php if ($genre = metadata('item', array('CWG Documents', 'Document Genre'))): ?>
                  <div class="item-description">
                    <?php echo $genre; ?>
                  </div>
                <?php endif; ?>

            <!-- Result type. -->
            <!--<span class="result-type">(<?php //echo $doc->resulttype; ?>)</span>-->

          </div>

          <!-- Highlighting. -->
          <!--<?php if (get_option('solr_search_hl')): ?>
            <ul class="hl">
              <?php foreach($results->highlighting->{$doc->id} as $field): ?>
                <?php foreach($field as $hl): ?>
                  <li class="snippet"><?php echo strip_tags($hl, '<em>'); ?></li>
                <?php endforeach; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>-->

        </div>
      <?php else: ?>
        <!-- Document. -->
        <div class="result">
          <!-- Header. -->
          <div class="result-header">

            <!-- Record URL. -->
            <?php $url = SolrSearch_Helpers_View::getDocumentUrl($doc); ?>

            <!-- Title. -->
            <h2><a href="<?php echo $url; ?>" class="result-title"><?php
                    $title = is_array($doc->title) ? $doc->title[0] : $doc->title;
                    if (empty($title)) {
                        $title = '<i>' . __('Untitled') . '</i>';
                    }
                    echo $title;
                ?></a></h2>
          </div>
        </div>
      <?php endif; ?>

  <?php endforeach; ?>

</div>


<?php echo pagination_links(); ?>
<?php echo foot();
