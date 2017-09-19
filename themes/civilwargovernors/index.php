<?php echo head(array('bodyid'=>'home')); ?>

<div id="search-container" role="search">
  <div id="search-header">
    <p><span>&#9733; </span>&nbsp;Search Collection&nbsp;<span> &#9733;</span></p>
  </div>
  <?php echo search_form(); ?>
  <div id="advanced-search-link">
    <a href="/items/search" >Advanced Search</a>
  </div>
</div>

<div id="homepage-text">
  <?php if (get_theme_option('Homepage Text')): ?>
    <p><?php echo get_theme_option('Homepage Text'); ?></p>
  <?php endif; ?>
</div>

<div id="genre-browse">
  <h2>Explore</h2>
  <ul>
    <li><a href="/solr-search?q=&facet=65_s%3A%22Commission%2FAppointment%22">commission/appointment</a></li>
    <li><a href="/solr-search?q=&facet=65_s%3A%22Correspondence%22">correspondence</a></li>
    <li><a href="/solr-search?q=&facet=65_s%3A%22Legal%2FFinancial%22">legal/financial</a></li>
    <li><a href="/solr-search?q=&facet=65_s%3A%22Petition%22">petition</a></li>
    <li><a href="/solr-search">more...</a></li>
  </ul>

  <!--<li><a href="/solr-search?q=&facet=65_s%3A%22Broadside%22">broadside</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Diagram%22">diagram</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Endorsement%22">endorsement</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Envelope%22">envelope</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Newspaper%22">newspaper</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Newspaper+Article%22">newspaper article</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Note%22">note</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Order%22">order</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Proclamation%2FLegislation%22">proclamation/legislation</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Report%22">report</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Speech%22">speech</a></li>
  <li><a href="/solr-search?q=&facet=65_s%3A%22Telegram%22">telegram</a></li>-->

</div>

<div class="divider"><hr class="kentucky-star"/></div>
<div id="featured" >
  <?php  if (get_theme_option('Display Featured Collection') !== '0'): ?>
  <!-- Featured Collection -->
  <div id="featured-collection">
      <h2><?php echo __('Featured Collection'); ?></h2>
      <?php echo random_featured_collection(); ?>
  </div><!-- end featured collection -->
  <?php endif; ?>

  <?php if ((get_theme_option('Display Featured Exhibit') !== '0')
          && plugin_is_active('ExhibitBuilder')
          && function_exists('exhibit_builder_display_random_featured_exhibit')):  ?>
          <?php echo exhibit_builder_display_random_featured_exhibit(); ?>
  <?php endif; ?>

  <div id="home-news">
    <h2>News</h2>
    <div class="news record">
      <a class="image" href="http://civilwargovernors.org/category/featured-article/" target="blank"><img src="files/theme_uploads/homenews.jpg" /></a>
    </div>
  </div>
</div>

<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>

<?php fire_plugin_hook('public_home', array('view' => $this)); ?>

<?php echo foot(); ?>
