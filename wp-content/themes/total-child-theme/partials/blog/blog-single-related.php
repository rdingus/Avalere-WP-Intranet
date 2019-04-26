<?php
/**
 * Single related posts
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.2
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
$post_id = get_the_ID();
if (has_category('podcasts', $post_id)) {
    if (is_singular('post')):

        $relatedPodcast = get_posts(
                array(
                    'category__in' => wp_get_post_categories($post_id),
                    'numberposts' => 4,
                    'post__not_in' => array($post_id),
                    'orderby' => 'date',
                    'order' => 'DESC'
                )
        );

        if (!empty($relatedPodcast)) {
            ?>
            <div class="related-podcast clr">
                <h2 class="theme-heading border-w-color related-posts-title related-podcast-title"><span class="text">Recent Podcasts</span>
                </h2>
                    <?php
                    foreach ($relatedPodcast as $key => $value) {

                        $url = get_the_permalink($value->ID);
                        ?>
                        <article class = "single-related-podcast clr nr-col post-<?php echo $value->ID; ?> post type-post status-publish format-standard has-post-thumbnail entry has-media">
                            <!--<div class="podcast-icon">
                                <img src="<?php //echo get_stylesheet_directory_uri(); ?>/images/podcast-icon.png"/>
                            </div>-->
                            <div class = "related-podcast-content clr">
                                <div class="post_meta">
                                    <ul>
                                        <li class="meta-date">
                                            <?php echo date("M d, Y", strtotime($value->post_date)); ?>
                                        </li>

                                        <?php
                                        /* $postcat = get_the_category($value->ID);
                                          if (!empty($postcat)) {
                                          ?>
                                          <li class="meta-category">
                                          <?php echo esc_html($postcat[0]->name); ?>
                                          </li>
                                          <?php
                                          } */

                                        $umbrellaCat = wp_get_object_terms($value->ID, 'content-categories');
                                        
                                        if (!empty($umbrellaCat)) {
                                            ?>
                                            <li class="meta-category">
                                                <?php
                                                foreach ($umbrellaCat as $term) {
                                                    if ($term->parent == 0) {
                                                    echo '<a href="' . get_term_link($term->slug, 'content-categories') . '">' . $term->name . '</a>';
                                                    }
                                                }
                                                $i = 1;
                                                foreach ($umbrellaCat as $term) {
                                                    if ($term->parent != 0 && $i == 1) {
                                                    echo ' > <a href="' . get_term_link($term->slug, 'content-categories') . '">' . $term->name . '</a>';
                                                    $i++;
                                                    }
                                                }
                                                ?>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <h4 class = "related-podcast-title entry-title">
                                    <a href = "<?php echo $url; ?>" rel = "bookmark"><?php echo "Podcast: " . $value->post_title; ?></a>
                                </h4>

                                <?php
                                $employeeIdArr = get_post_meta($value->ID, $key = 'post_authors');
                                $str = '';
                                if (isset($employeeIdArr[0]) && !empty($employeeIdArr[0])):

                                    $args = array(
                                        'include' => $employeeIdArr[0],
                                        'posts_per_page' => -1,
                                        'orderby' => 'post_title',
                                        'order' => 'ASC',
                                        'post_type' => 'emd_employee',
                                        'post_status' => 'publish',
                                        'suppress_filters' => true
                                    );
                                    $posts_array = get_posts($args);

                                    if (!empty($posts_array)) {
                                        ?>

                                        <ul class="post_authors">
                                            <?php
                                            foreach ($posts_array as $val) {
                                                $employee_id = $val->ID;
                                                $employee_url = get_the_permalink($employee_id);

                                                $sval = get_post_meta($employee_id, 'emd_employee_photo');
                                                if (isset($sval[0])) {
                                                    $thumb = wp_get_attachment_image_src($sval[0], 'small');
                                                    if (isset($thumb[0]) && $thumb[0] != "") {
                                                        $img_url = $thumb[0];
                                                    }
                                                } else {
                                                    $img_url = get_stylesheet_directory_uri() . '/images/default_avatar.png';
                                                }
                                                ?>
                                                <li class="authors-details">
                                                    <a href="<?php echo $employee_url; ?>">
                                                        <img class="emd-img thumb" src="<?php echo $img_url; ?>" alt="<?php echo $val->post_title; ?>"/>
                                                        <span class="emp_name"><?php echo $val->post_title; ?></span>
                                                    </a>
                                                </li>
                                            <?php }
                                            ?>
                                        </ul>
                                        <?php
                                    }
                                endif;
                                ?>
                            </div>
                        </article>
                        <?php
                    }
                    ?>
            </div>

            <?php
        }
    endif;
}
 /*?>$productIds = get_field("related_products", $post_id);
$products = null;
if ($productIds) {
    $products = getObjectsByCPT('product', -1, array(), $productIds);
}
	if ($products) { ?>
                <div id="products-wrapper" class="products-wrapper">
                    <div class="product-category-box odd">
                        <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;margin-bottom:30px;">
                            <div class="wpb_column vc_column_container vc_col-sm-4">
                                <h2 class="section-title">Related Products</h2>
                            </div>
                        </div>
                        <?php
                        $productsParts = array_chunk($products, 4);
                        foreach ($productsParts as $rowkey => $productsPart) {
                            ?>
                            <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">
                                <?php
                                foreach ($productsPart as $key => $product) {
                                    $rolloverImage = get_field('product_rollover_image', $product->ID);
                                    $productImage = get_field('product_image', $product->ID);
                                    //$productCategorySlug = wp_get_post_terms($product->ID, 'product-category')[0]->slug;
                                    //$fieldNameCategoryWise = str_replace('-', '_', $productCategorySlug);
                                   // $productIntro = get_field($fieldNameCategoryWise . '_intro', $product->ID);
								    $productIntro = get_field('intro', $product->ID);
                                    $index = $key + 1;
                                    $productTitle = $product->post_title;
                                    $placeholder = get_stylesheet_directory_uri() . '/images/team-placeholder.jpg';
									if($productImage){
										$productImageURL = $productImage['url'];										
									}else{
										$productImageURL = get_stylesheet_directory_uri() . '/images/team-placeholder.jpg';
									}
                                    $productURL = get_permalink($product->ID);
									
                                    ?>
                                    <div class="product-wrapper wpb_column vc_column_container vc_col-sm-4" >
                                        <div class="product-box"> <a href="<?php echo $productURL; ?>">
                                                <div class="imageWrapper"> <img src="<?php echo $placeholder; ?>" data-original="<?php echo $productImageURL; ?>" alt="<?php echo $productTitle; ?>"/> <i class="arrow-up"></i> </div>
                                                <div class="productSummary">
                                                    <h4><?php echo $productTitle; ?></h4>
                                                    <div class="intro"><?php echo $productIntro; ?></div>
                                                </div>
                                            </a> </div>

                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <script type='text/javascript'>


                    (function ($) {
                        $(window).bind('load', function () {
                            var images = $('.product-wrapper .imageWrapper img');
                            images.each(function (index, element) {

                                var original = $(this).attr('data-original');
                                $(this).attr('src', original);
                            });

                        });
                    })(jQuery);
                </script>
            <?php }<?php */

// Return if disabled
if (!wpex_get_mod('blog_related', true)) {
    return;
}

// Number of columns for entries
$wpex_columns = apply_filters('wpex_related_blog_posts_columns', wpex_get_mod('blog_related_columns', '3'));

// Query args
$args = array(
    'posts_per_page' => wpex_get_mod('blog_related_count', '3'),
    'orderby' => 'rand',
    'post__not_in' => array(get_the_ID()),
    'no_found_rows' => true,
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => array('post-format-quote', 'post-format-link'),
            'operator' => 'NOT IN',
        ),
    ),
);

// Query items fom same category
if (apply_filters('wpex_related_in_same_cat', true)) {
    $cats = wp_get_post_terms(get_the_ID(), 'category', array(
        'fields' => 'ids',
    ));
    if ($cats) {
        $args['category__in'] = $cats;
    }
}

// If content is disabled make sure items have featured images
if (!wpex_get_mod('blog_related_excerpt', true)) {
    $args['meta_key'] = '_thumbnail_id';
}

// Apply filters to arguments for child theme editing.
$args = apply_filters('wpex_blog_post_related_query_args', $args);

// Related query arguments
$wpex_related_query = new wp_query($args);

// If the custom query returns post display related posts section
if ($wpex_related_query->have_posts()) :

    // Wrapper classes
    $classes = 'related-posts clr';
    if ('full-screen' == wpex_content_area_layout()) {
        $classes .= ' container';
    }

    $category_slug = isset(get_the_category($id)[0]->slug) ? get_the_category($id)[0]->slug : "";

    $grey = ['webinars', 'press-releases', 'insights', 'videos'];
    if (in_array($category_slug, $grey)) {
        $classes .= ' grey-bg-content';
    }
    $classes .= ' ' . $category_slug;
    ?>

    <div class="<?php echo esc_attr($classes); ?> ">

        <?php get_template_part('partials/blog/blog-single-related', 'heading'); ?>

        <!--<div class="wpex-row clr">-->
            <?php
            
			$umbrellaCat = wp_get_object_terms($post_id, 'content-categories');	
	        $umbrellaCatId = wp_list_pluck($umbrellaCat, 'term_id');
            $relatedUmbrellaPosts = $relatedPosts = $argsrelatedcontent = [];
			$selectedrelatedcontent = get_field('related_content',$post_id);
			if($selectedrelatedcontent){
				
				if(count($selectedrelatedcontent) < 3){
					switch(count($selectedrelatedcontent)){
							case 2:
							$per2 = 1;
							break;
							case 1:
							$per2 = 2;
							break;
							
					}
					$exludeids = array_merge($selectedrelatedcontent,array($post_id));
					$thirdrelatedcontent = getObjectsByCPTWithCategory('post',array(
                        array(
                            'taxonomy' => 'content-categories',
                            'field' => 'id',
                            'terms' => $umbrellaCatId,                            
                        )),$per2,array(),array(),$exludeids,'date','DESC');
						foreach($thirdrelatedcontent as $e){
					array_push($selectedrelatedcontent, $e->ID);
						}
				}

				
				$argsrelatedcontent = array(
                    'post_type' => 'post',
					'post__in'=>$selectedrelatedcontent,					                    
					'orderby' => 'post__in',
                    'order' => 'DESC',
                );
				
			}else{
				
				if (!empty($umbrellaCatId)) {
				
				
					$argsrelatedcontent = array(
                    //'post_type' => 'products',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'content-categories',
                            'field' => 'id',
                            'terms' => $umbrellaCatId,                            
                        )),
                    'posts_per_page' => 3,
                    //'ignore_sticky_posts' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post__not_in' => array($post_id)
                );
				
				
				
                
            }
			}
            


			
            
			$relatedPostsObject = new WP_Query($argsrelatedcontent);
            $relatedPosts = $relatedPostsObject->posts;

            if (!empty($relatedPosts)) { // append parent category post if umbrella cat post not found 4 posts
                
            

            /* foreach ($wpex_related_query->posts as $post) : setup_postdata($post);
              ?>
              <?php $wpex_count++; ?>
              <?php include( locate_template('partials/blog/blog-single-related-entry.php') ); ?>
              <?php if ($wpex_columns == $wpex_count) $wpex_count = 0; ?>
              <?php
              endforeach;

              echo '<h2 class="theme-heading border-w-color related-posts-title"><span class="text"> NEW Related Content</span></h2>'; */

            $wpex_count = 0;
            foreach ($relatedPosts as $post) : setup_postdata($post);
                ?>
                <?php $wpex_count++; ?>
                <?php include( locate_template('partials/blog/blog-single-related-entry.php') ); ?>
                <?php if ($wpex_columns == $wpex_count) $wpex_count = 0; ?>
            <?php endforeach; }?>
        <!--</div>--><!-- .wpex-row -->

    </div><!-- .related-posts -->

<?php endif; ?>

<?php wp_reset_postdata(); ?>
<?php
$totalIdArr = [];
$post_id = get_the_ID();
//$productsArr = get_post_meta($post_id, $key = 'related_products');

$servicesArr = get_post_meta($post_id, $key = 'related_services');
/* if ((is_singular('post')) && (isset($productsArr[0]) && !empty($productsArr[0]))):
  foreach ($productsArr[0] as $product) {
  array_push($totalIdArr, $product);
  }
  endif; */

if ((is_singular('post')) && (isset($servicesArr[0]) && !empty($servicesArr[0]))):
$totalIdArr = $servicesArr[0];
if(count($totalIdArr) < 3){
	switch(count($totalIdArr)){
		case 2:
		$per = 1;
		break;
		case 1:
		$per = 2;
		break;
		
	}
	if($selpro = get_field('related_products',$post_id)){
	$thirdservice = getObjectsByCPT('product',$per,array(),$selpro,array(),'date','DESC');
	foreach($thirdservice as $s){
		array_push($totalIdArr, $s->ID);
	}
	}
	
}

    
endif;
if (!empty($totalIdArr)):
    ?>
    <div class="products-services <?php if (in_category( 'podcasts' )) : ?>bg-grey<?php endif;?> clr">

        <h2 class="theme-heading border-w-color related-posts-title product-service-title"><span class="text">Services</span>
        </h2>
        <div class="quotes">From beginning to end, our team synergy <br /> produces measurable results. Let's work together.</div>
        <div class="wpex-row clr">
            <?php
            $i = 1;
            foreach ($totalIdArr as $service_id) {
                $serviceData = get_post($service_id);
                $serviceLink = get_permalink($service_id);
				if($serviceData->post_type  == 'service'){
                	$serviceImage = get_field("service_image", $service_id);
				}else{
					$serviceImage = get_field("product_image", $service_id);
				}
                $subheading = get_field("subheading", $service_id);
                if ($i > 3)
                    continue;
                ?>
                <article class = "related-products clr nr-col span_1_of_3 col-<?php echo $i; ?> post-<?php echo $service_id; ?>  post type-post status-publish format-standard has-post-thumbnail entry has-media">
                    <figure class = "related-post-figure clr " style="display:block;">
                        <a href = "<?php echo $serviceLink; ?>" title = "<?php echo $serviceData->post_title; ?>" rel = "bookmark" class = "related-post-thumb">
                            <img src = "<?php echo (isset($serviceImage['url']) && $serviceImage['url'] != "") ? $serviceImage['url'] : ''; ?>"> </a>
                    </figure>
                    <div class = "related-products-content clr" style = "height: 84px;">
                        <!--                        <div class="types">
                        <?php
                        //if ($value->post_type == 'service') {
                        //echo 'Services';
                        /* } else if ($value->post_type == 'product') {
                          echo 'Products';
                          } */
                        ?>
                                                </div> -->
                        <h4 class = "related-products-title entry-title">
                            <a href = "<?php echo $serviceLink; ?>" rel = "bookmark"><?php echo $serviceData->post_title; ?></a>
                        </h4>
                        <div class="product_subheading"><?php echo $subheading; ?></div>
                    </div>
                </article>
                <?php
                $i++;
            }
            ?>
        </div>
    </div>
    <?php endif;
	?>