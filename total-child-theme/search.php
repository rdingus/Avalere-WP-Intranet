<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 3.3.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Get Ajax Search Pro plugin results
global $wp_query;
$results = $wp_query->posts;
//echo "......." . count($results). ".......";
//var_dump($results);

?>

<div id="content-wrap" class="container clr">
  <?php wpex_hook_primary_before(); ?>
  <div id="primary" class="content-area clr">
    <?php wpex_hook_content_before(); ?>
    <div id="content" class="site-content">
      <?php wpex_hook_content_top(); ?>
      <div style="background-color:#fff;margin-left:0px;margin-right:0px;" class="vc_row wpb_row vc_row-fluid wpex-vc_row-has-fill">
        <div class="wpb_column vc_column_container vc_col-sm-12">
          <div class="vc_column-inner ">
            <div class="wpb_wrapper">
              <div id="header-wrapper" class="header-wrapper vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">
                <div class="vc_column_container vc_col-sm-12">
                  <h1 class="jump-header">Search Results: <!--<span style="color:#d8e0e8;"><?php //echo $searchResult->found_posts;?></span>--></h1>
                </div>
                <div class="vc_column_container vc_col-sm-12">
                  <h1 class="jump-header" style="color:#27405d;" ><?php echo get_search_query();?></h1>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <?php	
      // Check if there are search results
      if (have_posts()) :
      ?>
      <div id="search-entries" class="clr">
        <?php
        // Display blog style search results
        if ('blog' == wpex_search_results_style()) :
           $columns = wpex_blog_entry_columns()
        ?>
        <div id="blog-entries" class="<?php wpex_blog_wrap_classes(); ?>">
          <?php
            // Define counter for clearing floats
            $wpex_count = 0;

            // Start div loop
            //while ($searchResult->have_posts()) : $searchResult->the_post();
            while ($wp_query->have_posts()) : 

            	// set the post object
            	$wp_query->the_post();
           
                // Add to counter
                $wpex_count++;

                // Get blog entry layout
                wpex_get_template_part('blog_entry');

                // Reset counter to clear floats
                if ($columns == $wpex_count) {
                    $wpex_count = 0;
                }

            endwhile;
			
          ?>
        </div>
        
        <?php
            // Display post pagination
            wpex_pagination( $wp_query, true )
        ?>
        <?php
            // Display custom style for search entries
            else :
        ?>
        <?php while (have_posts()) : the_post(); ?>
        <?php wpex_get_template_part('search_entry'); ?>
        <?php endwhile; ?>
        <?php wpex_pagination(); ?>
        <?php endif; ?>
      </div>
      <!-- #search-entries -->
      
      <?php
            // No search results found
            else :
      ?>
      <?php get_template_part('partials/search/search-no-results'); ?>
      <?php endif; ?>
      <?php wpex_hook_content_bottom(); ?>
    </div>
    <!-- #content -->
    
    <?php wpex_hook_content_after(); ?>
  </div>
  <!-- #primary -->
  
  <?php wpex_hook_primary_after(); ?>
</div>
<!-- #content-wrap -->

<?php
// Get site footer
get_footer();
?>
