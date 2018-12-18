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

//global $wp_query; 
//$results = $wp_query->posts;
//var_dump($results);

$searchQuery =  get_search_query();
$searchResultIds = $services = $servicesSearch = $servicesACF = $posts = $employees = array();
			
			// get services search result
			$servicesArgs = array(
				'post_type' => 'service',            
				'posts_per_page' => -1,
				'post_status' => 'publish',			
				'orderby'=>'date',					
				's'=>$searchQuery,
				'order'=>'DESC'
			);
			$servicesObject = new WP_Query($servicesArgs);
			
			if($servicesObject){
				foreach($servicesObject->get_posts() as $post){
					$servicesSearch[] = $post->ID;
				}
			}
			
			$servicesACFArgs = array(
				'post_type' => 'service',            
				'posts_per_page' => -1,
				'post_status' => 'publish',			
				'orderby'=>'date',
				'meta_query'=>array(
					'relation'=>'OR',
					array(
						'key' => 'subheading',
						'value' => $searchQuery,
						'compare' => 'LIKE',
				      ),
					  array(
						'key' => 'description',
						'value' => $searchQuery,
						'compare' => 'LIKE',
				      ),
				),	
				'order'=>'DESC'
			);
			$servicesACFObject = new WP_Query($servicesACFArgs);
			
			if($servicesACFObject){
				foreach($servicesACFObject->get_posts() as $post){
					$servicesACF[] = $post->ID;
				}
			}	
			$services = array_unique(array_merge($servicesSearch,$servicesACF));
			
			// get post search result
			$postArgs = array(
				'post_type' => 'post',            
				'posts_per_page' => -1,
				'post_status' => 'publish',			
				'orderby'=>'date',
				's'=>$searchQuery,
				'order'=>'DESC'
			);
			$postObject = new WP_Query($postArgs);
			if($postObject){
				foreach($postObject->get_posts() as $post){
					$posts[] = $post->ID;
				}
			}
			/*
			// get employees search result
			$employeesArgs = array(
				'post_type' => 'emd_employee',            
				'posts_per_page' => -1,
				'post_status' => 'publish',			
				'orderby'=>'date',
				's'=>$searchQuery,
				'order'=>'DESC'
			);
			$employeesObject = new WP_Query($employeesArgs);
			if($employeesObject){
				foreach($employeesObject->get_posts() as $post){
					$employees[] = $post->ID;
				}
			}*/	
					
			
			//get all merge
			$searchResultIds = array_merge($services,$posts);
			$searchResultArgs = array(  
				'post_type'=>'any',                      
				'post__in'=>$searchResultIds,
				'post_status'=>'publish',
				'orderby'=>'post__in',
				'posts_per_page'=>get_option('posts_per_page'),
				'paged'=>get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1
    	    );
			$searchResult = new WP_Query($searchResultArgs);
// Get site header

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
      <?php /*?>            <?php echo '<div id="category-archive-searchform">' . do_shortcode('[searchandfilter id="8897"]') . '</div>'; ?><?php */?>
      <?php
			
			
			//echo '<pre>';print_r($searchResult);
            // Check if there are search results
            if ($searchResult->have_posts()) :
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
                            while ($searchResult->have_posts()) : $searchResult->the_post();

                                // Add to counter
                                $wpex_count++;

                                // Get blog entry layout
                                wpex_get_template_part('blog_entry');

                                // Reset counter to clear floats
                                if ($columns == $wpex_count) {
                                    $wpex_count = 0;
                                }

                            // End loop
                            endwhile;
							
                            ?>
        </div>
        <!-- #blog-entries -->
        
        <?php
                        // Display post pagination
                        
                        wpex_pagination( $searchResult, true )
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
