<!DOCTYPE html>
<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>">
    <?php endif; ?>

    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo str_replace(': Early Access', ' Digital Documentary Edition', implode(' &middot; ', $titleParts)); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_url('https://fonts.googleapis.com/css?family=Cinzel');
    queue_css_url('https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
    queue_css_file(array('iconfonts', 'normalize', 'webfonts/texgyreheros_regular_macroman/stylesheet', 'webfonts/texgyreheros_bold_macroman/stylesheet', 'style'), 'screen');
    queue_css_file('print', 'print');
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php
    queue_js_file(array(
        'vendor/selectivizr',
        'vendor/jquery-accessibleMegaMenu',
        'vendor/respond',
        'jquery-extra-selectors',
        'seasons',
        'globals'
    ));
    ?>
    <?php echo head_js(); ?>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
      jQuery(function() {
        jQuery( "#tabs" ).tabs();
      });
      jQuery(document).ready(function(){
        jQuery(".simple-page .repo h3").each(function(){
          var height = jQuery(this).height();
          var cornerwidth = height/2;
          var cornerright = cornerwidth*-1;
          jQuery(this).find(".bottom_corner").css({"width":cornerwidth,"right":cornerright});
          jQuery(this).find(".top_corner").css({"width":cornerwidth,"right":cornerright});
        });
      })
      function showPopup() {
          jQuery("#pdfviewer").dialog({
            height: 'auto',
            width: '90%',
            resizable: true
          });
      }
    </script>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
    <div id="wrap">

      <header role="banner">
        <div class="header-top-bar"></div>
          <div id="header-content">
            <div class="header-blue-bar">
              <div id="khslogo">
                <span>&#9733;</span>
                <a href="http://history.ky.gov/"><img src="/files/theme_uploads/kentuckyhistoricalsocietylogo.png" /></a>
                <span>&#9733;</span>
              </div>
            </div>
            <div id="site-title">
                <?php //echo link_to_home_page(theme_logo()); ?>
                <a href="http://discovery.civilwargovernors.org"><img src="/files/theme_uploads/civilwargovernorslogo.png" /></a>

            </div>
            <nav id="top-nav" class="top" role="navigation">
                <?php echo public_nav_main(); ?>
            </nav>
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>

          </div>


        </header>
        <?php if(! is_current_url(WEB_ROOT) && !isset($_GET['q']) && !is_current_url('/items/search') && !is_current_url('/solr-search')) {?>
          <div id="search-container" role="search">
            <div id="search-header">
              <p><span>&#9733; </span>&nbsp;Search Collection&nbsp;<span> &#9733;</span></p>
            </div>
            <?php echo search_form(); ?>
            <div id="advanced-search-link">
              <a href="/items/search" >Advanced Search</a>
            </div>
          </div>
        <?php } ?>

        <div id="content" role="main" tabindex="-1">
            <?php
                if(! is_current_url(WEB_ROOT)) {
                  fire_plugin_hook('public_content_top', array('view'=>$this));
                }
            ?>
