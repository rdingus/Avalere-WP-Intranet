<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: wpex
 * @link http://codex.wordpress.org/Plugin_API
 *
 */
/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */

// Add custom font to font settings
add_filter('wp_kses_allowed_html', function( $allowed, $context ) {

    if ( is_array( $context ) ) {
        return $allowed;
    }

    if ( $context === 'page' ) {
        $allowed['a']['data-type'] = true;
        $allowed['a']['data-options'] = true;
    }

    return $allowed;

}, 10, 2);

function wpex_add_custom_fonts() {
	return array( 'Helvetica Neue' ); // You can add more then 1 font to the array!
}

 function wpex_list_post_terms_custom( $taxonomy = 'category', $show_links = true, $echo = true ,$postId = false ) {

	// Make sure taxonomy exists
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}

	if(!$postId){
		$postId = get_the_ID();
	}

	// Get terms
	$list_terms = array();
	$terms      = wp_get_post_terms( $postId, $taxonomy );

	// Return if no terms are found
	if ( ! $terms ) {
		return;
	}
	$list_terms_parent = $list_terms_child = array();
	// Loop through terms
	foreach ( $terms as $term ) {

		if ( $show_links ) {

			$attrs = array(
				'href'  => esc_url( get_term_link( $term->term_id, $taxonomy ) ),
				'title' => esc_attr( $term->name ),
				'class' => 'term-' . $term->term_id,
			);
			if($term->parent == 0){
				$list_terms_parent[] = wpex_parse_html( 'a', $attrs, esc_html( $term->name ) );	
			}else{
				$list_terms_child[] = wpex_parse_html( 'a', $attrs, esc_html( $term->name ) );
			}
		} else {

			$attrs = array(
				'class' => 'term-' . $term->term_id,
			);

			$list_terms[] = wpex_parse_html( 'span', $attrs, esc_html( $term->name ) );

		}
	}
	$list_terms = array_merge($list_terms_parent,$list_terms_child);

	// Turn into comma seperated string
	if ( $list_terms && is_array( $list_terms ) ) {
		$list_terms = implode( ' > ', $list_terms );
	} else {
		return;
	}

	// Apply filters (can be used to change the comas to something else)
	$list_terms = apply_filters( 'wpex_list_post_terms', $list_terms, $taxonomy );

	// Echo terms
	if ( $echo ) {
		echo $list_terms;
	} else {
		return $list_terms;
	}

}
add_filter('wpex_page_header_style', function( $style ) {
	$product_type = get_post_type();
    if ( $product_type == 'product' || is_tax() || is_404() || is_search() || is_category() || is_single() || is_page() && !is_front_page()) {
        $style = 'background-image';
    }
    return $style;
}, 20);
// Conditionally enable Overlay Header for category
add_filter('wpex_has_overlay_header', function( $return ) {
	$product_type = get_post_type();
    if ( $product_type == 'product' || is_tax() || is_404() || is_search() || is_category() || is_single() || is_page() && !is_front_page()) {
        $return = true;
    }
    return $return;
});
// Post page and category page header image
// Change the page header background image

// echo var_dump( is_search() );
// echo get_page_template();
add_filter('wpex_page_header_background_image', function( $image ) {
    if ( is_singular('post') && !is_search() ) {
        $cat = get_query_var('cat');
        $args = array(
            'child_of' => $cat,
            'orderby' => 'name',
            'order' => 'ASC'
        );
        $category_id = wp_get_post_terms(get_the_ID(), 'category');
        if (isset($category_id[0]->term_id)) {
            $image = get_field('post_header_image', 'category_' . $category_id[0]->term_id);
        }
    } elseif ( is_category() || is_tax() && !is_search() ) {
        $term_id = get_queried_object()->term_id;
        $postIdArr = get_term_meta($term_id, 'category_header_image');
        if (isset($postIdArr[0]) && $postIdArr[0] != "") {
            $image = get_the_guid($postIdArr[0]);
        }
    } elseif ( is_page() && !is_front_page() && !is_search() ) {
        $image = get_field('page_header');
    } elseif ('product' == get_post_type() || 'service' == get_post_type() && !is_search() ){
    	$image = get_field('page_header');
    } elseif ( is_singular('emd_employee') && !is_search() ) {
        $image = get_field('page_header', 10048);
    } elseif ( is_404() || is_search() ) {
        $postIdArr = get_term_meta(3, 'category_header_image');
        if (isset($postIdArr[0]) && $postIdArr[0] != "") {
            $image = get_the_guid($postIdArr[0]);
        }
    }
    /*elseif ( is_search() ) {
        // $image = get_field('page_header', 10436);
        echo "Hello Hi Search functions ";
        $postIdArr = get_term_meta(3, 'category_header_image');
        if (isset($postIdArr[0]) && $postIdArr[0] != "") {
            $image = get_the_guid($postIdArr[0]);
        }
    } */    
    return $image;
});

add_image_size('640-480-size', 640, 480, true);
require_once get_stylesheet_directory() . '/custom-shortcodes.php';
add_filter('template_include', 'portfolio_page_template', 99);

function portfolio_page_template($template) {
    if (is_singular('emd_employee')) {
        $new_template = locate_template(array('single-emd_employee.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    return $template;
}

add_filter('wp_link_pages_args', 'remove_wp_link_pages');

function remove_wp_link_pages($args = '') {
    if (is_singular('emd_employee')) {
        $args['echo'] = 0;
    }
    return $args;
}

function total_child_enqueue_parent_theme_style() {
// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
    $theme = wp_get_theme('Total');
    $version = $theme->get('Version');

    $style_ver = filemtime( get_stylesheet_directory() . '/style.css' );
    $custom_ver = filemtime( get_stylesheet_directory() . '/custom.css' );
// Load the stylesheet 
    wp_enqueue_style('parent-theme', get_template_directory_uri() . '/style.css', array(), $style_ver);
    wp_register_style('child-theme', get_stylesheet_directory_uri() . '/custom.css', array('parent-theme'), $custom_ver);
    wp_enqueue_style('child-theme');
    wp_register_script('child-matchheight', get_stylesheet_directory_uri() . '/js/jquery.matchHeight-min.js', array(), '', true);
    wp_enqueue_script('child-matchheight');
}

add_action('wp_enqueue_scripts', 'total_child_enqueue_parent_theme_style');

function r($array = []) {
    echo "<pre>";
    print_r($array);
}

//Remove Theme Meta Generator
add_action('init', function() {
    remove_action('wp_head', 'wpex_theme_meta_generator', 1);
}, 10);

function re($array = []) {
    echo "<pre>";
    print_r($array);
    exit;
}

//Post category name changes to Media Type
function revcon_change_cat_label() {
    global $submenu;
    $submenu['edit.php'][15][0] = 'Media Types'; // Rename categories to Media Types
}

add_action('admin_menu', 'revcon_change_cat_label');

function revcon_change_cat_object() {
    global $wp_taxonomies;
    $labels = &$wp_taxonomies['category']->labels;
    $labels->name = 'Media Types';
    $labels->singular_name = 'Media Types';
    $labels->add_new = 'Add Media Types';
    $labels->add_new_item = 'Add Media Types';
    $labels->edit_item = 'Edit Media Types';
    $labels->new_item = 'Media Types';
    $labels->view_item = 'View Media Types';
    $labels->search_items = 'Search Media Types';
    $labels->not_found = 'No Media Types found';
    $labels->not_found_in_trash = 'No Media Types found in Trash';
    $labels->all_items = 'All Media Types';
    $labels->menu_name = 'Media Types';
    $labels->name_admin_bar = 'Media Types';
}

add_action('init', 'revcon_change_cat_object');

// Add sorting to ACF relation
function my_relationship_query($args, $field, $post_id) {
    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
    return $args;
}

// filter for every field
add_filter('acf/fields/relationship/query', 'my_relationship_query', 10, 3);

// Relation ship data load date wise
function insight_relationship_query($args, $field, $post_id) {
// order by newest posts first
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';
    return $args;
}

// filter for every field
add_filter('acf/fields/relationship/query/key=field_5b49af7af1481', 'insight_relationship_query', 10, 3);
add_filter('acf/fields/relationship/query/key=field_5b49a14175342', 'insight_relationship_query', 10, 3);
add_filter('acf/fields/relationship/query/key=field_5bfd41ced01b9', 'insight_relationship_query', 10, 3);

//add_filter( 'generate_rewrite_rules', 'ex_rewrite' );
// Use to call Products Archive template file
function myprefix_custom_template_parts($parts) {
// Override the output for your 'products' post type
// Now you can simply create a products-entry.php file in your child theme
// and whatever you place there will display for the entry
    if ('product' == get_post_type()) {
        $parts['cpt_entry'] = 'products-entry';
    } else if ('product' == get_post_type()) {
        $parts['cpt_entry'] = 'services-entry';
    }
    return $parts;
}

add_filter('wpex_template_parts', 'myprefix_custom_template_parts');
/* change header search style */

function my_search_placeholder() {
    return __('', 'Total');
}

add_filter('wpex_search_placeholder_text', 'my_search_placeholder');
// for adding js/jq code into footer
/*add_action('wp_footer', function() {
    ?>
    <script>
        (function ($) {
            'use strict';
            jQuery(document).ready(function () {
                //append search to header
                jQuery('.search-toggle-li').empty();
                var search_form = '<div id="top-search-bar"><form method="get" class="searchform" action="<?php echo site_url(); ?>"><div>';
                search_form += '<button type="submit" class="searchform-submit">';
                search_form += '<span class="fa fa-search" aria-hidden="true"></span><span class="screen-reader-text">Submit</span></button>';
                search_form += '<input class="field" name="s" placeholder="" type="search"></div></form></div>';
                jQuery('#site-navigation-wrap').addClass('mycustom_search');
                jQuery('#site-navigation').before(search_form);
                jQuery('#searchform-dropdown').hide();
            });
        })(jQuery);
    </script>
    <?php
});*/

// Display Panelists @author S@if
function displayPanelists() {
    $postId = get_the_ID();
    $totalEmployeeIdArr = [];
    $moderatorIdArr = get_post_meta($postId, $key = 'post_moderator');
    $speakersArr = get_post_meta($postId, $key = 'post_speakers');
	$guestSpeakers = get_field('guest_speakers',$postId);
    $post_moderator = 0;
    if ((is_singular('post')) && (isset($moderatorIdArr[0][0]) && $moderatorIdArr[0][0] != "")):
        array_push($totalEmployeeIdArr, $moderatorIdArr[0][0]);
        $post_moderator = $moderatorIdArr[0][0];
    endif;
    if ((is_singular('post')) && (isset($speakersArr[0]) && !empty($speakersArr[0]))):
        foreach ($speakersArr[0] as $speaker) {
            array_push($totalEmployeeIdArr, $speaker);
        }
    endif;
    if (!empty($totalEmployeeIdArr) || !empty($guestSpeakers)):
        ?>
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner ">
                    <div class="wpb_wrapper">
                        <div class="panelists_section">
                            <div class="post-container">
                                <h2>Panelists</h2>
                                <div class="panelists">
                                    <?php
									if(!empty($totalEmployeeIdArr)){
                                    foreach ($totalEmployeeIdArr as $employee_id) {
                                        /* $args = array(
                                          'include' => $employee_id,
                                          'posts_per_page' => - 1,
                                          'post_type' => 'emd_employee',
                                          'post_status' => 'publish',
                                          'suppress_filters' => true
                                          );
                                          $posts_array = get_posts($args); */
                                        $employeeData = get_post($employee_id, ARRAY_A);
                                        if (!empty($employeeData)) {
                                            //foreach ($posts_array as $key => $value) {
                                            $employee_url = get_the_permalink($employee_id);
                                            $designation = get_the_terms($employee_id, 'jobtitles');
                                            $email = get_post_meta($employee_id, $key = 'emd_employee_email');
                                            $department = get_the_terms($employee_id, "departments", TRUE);
                                            $twitter = get_post_meta($employee_id, $key = 'emd_employee_twitter');
                                            $sval = get_post_meta($employee_id, 'emd_employee_photo');
                                            if (isset($sval[0])) {
                                                $thumb = wp_get_attachment_image_src($sval[0], 'small');
                                                if (isset($thumb[0]) && $thumb[0] != "") {
                                                    $img_url = $thumb[0];
                                                }
                                            } else {
                                                $upload_dir_path = wp_upload_dir();
                                                $img_url = $upload_dir_path['baseurl'] . '/2017/05/default_avatar.png';
                                            }
                                            ?>
                                            <div class="authors-details">
                                                <div class="calendar-container"> <a class="panelist-profile" href="<?php echo $employee_url; ?>"> <img src="<?php echo $img_url; ?>" title="<?php echo $employeeData['post_title']; ?>" /> </a>
                                                    <div class="panelists-content">
                                                        <div class="author_type"><?php echo ($employee_id == $post_moderator) ? "Moderator" : "Speaker"; ?></div>
                                                        <div class="author_name"> <span class="employee_name"><a href="<?php echo $employee_url; ?>"><?php echo trim($employeeData['post_title']); ?></a></span> <span class="staff_post_department">
                                                                <?php
                                                                echo isset($designation[0]->name) ? ', ' . $designation[0]->name : "";
                                                                echo isset($department[0]->name) ? ', ' . $department[0]->name : "";
                                                                ?>
                                                            </span> </div>
                                                        
                                                        <?php
                                                        $matches = null;
                                                        preg_match('/[a-z]\./', $employeeData['post_content'], $matches, PREG_OFFSET_CAPTURE);
                                                        if (count($matches) > 0) {
                                                            $firstMatch = reset($matches);
                                                            echo('<div class="author_desc">'.substr($employeeData['post_content'], 0, $firstMatch[1] + strlen($firstMatch[0])).'</div>');
                                                        }
                                                        ?>

                                                        <div class="author_contact">
                                                            <ul>
                                                                <?php if (isset($email[0]) && $email[0] != "") { ?>
                                                                    <li><a href="mailto:<?php echo $email[0]; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/mail-icon.png" /></a></li>
                                                                <?php } ?>
                                                                <?php if (isset($twitter[0]) && $twitter[0] != "") { ?>
                                                                    <li><a href="<?php echo $twitter[0]; ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter-icon.png" /></a></li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            //}
                                        }
                                    }
									}
									if(!empty($guestSpeakers)){
                                    foreach ($guestSpeakers as $guestSpeaker) {                                                                               
                                        
                                        
                                            $employee_url = get_the_permalink($employee_id);
                                            $designation = get_the_terms($employee_id, 'jobtitles');
                                            $email = get_post_meta($employee_id, $key = 'emd_employee_email');
                                            $department = get_the_terms($employee_id, "departments", TRUE);
                                            $twitter = get_post_meta($employee_id, $key = 'emd_employee_twitter');
                                            $sval = get_post_meta($employee_id, 'emd_employee_photo');
                                            if (isset($guestSpeaker['photo'])) {
                                                
                                                $img_url = $guestSpeaker['photo']['url'];
                                                
                                            } else {
                                                $upload_dir_path = wp_upload_dir();
                                                $img_url = $upload_dir_path['baseurl'] . '/2017/05/default_avatar.png';
                                            }
                                            ?>
                                            <div class="authors-details">
                                                <div class="calendar-container"> <a class="panelist-profile" href="javascript:void(0);"> <img src="<?php echo $img_url; ?>" title="<?php echo $guestSpeaker['name']; ?>" /> </a>
                                                    <div class="panelists-content">
                                                        <div class="author_type">Guest Speaker</div>
                                                        <div class="author_name"> <span class="employee_name" style="color:#1296f3;font-weight:600;"><?php echo trim($guestSpeaker['name']); ?></span> <span class="staff_post_department">
                                                                <?php
                                                                echo isset($guestSpeaker['title']) ? ', ' . $guestSpeaker['title'] : "";
                                                                echo isset($guestSpeaker['company_name']) ? ', ' . $guestSpeaker['company_name'] : "";
                                                                ?>
                                                            </span> </div>
                                                        <div class="author_desc"><?php $gp = explode(".",$guestSpeaker['bio_excerpt_text']); echo $gp[0].'.'; ?></div>
                                                        <div class="author_contact">
                                                            <ul>
                                                                <?php if (isset($guestSpeaker['email_address']) && $guestSpeaker['email_address'] != "") { ?>
                                                                    <li><a href="mailto:<?php echo $guestSpeaker['email_address']; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/mail-icon.png" /></a></li>
                                                                <?php } ?>
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            
                                        
                                    }
									}
									
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endif;
}

add_shortcode('display-panelists', 'displayPanelists');
/*
 * @author S@if
 * alter content-categories tax layout
 */
add_filter('wpex_is_blog_query', function( $bool ) {
    if (is_tax('content-categories')) {
        $bool = true;
    }
    return $bool;
});

function append_to_category() {
    if (is_tax('content-categories') || is_category()) {
        $categoryData = get_queried_object();
        $post_id = get_the_ID();
        ?>
        <div id="category_landing_title">
            <h1 class="vcex-module vcex-heading vcex-heading-plain"><?php echo isset($categoryData->name) ? $categoryData->name : ""; ?></h1>
            <div class="category_desc"><?php echo isset($categoryData->description) ? $categoryData->description : ""; ?></div>
        </div>
        <?php
        $featureContent = get_posts(
                array(
                    'category__in' => $categoryData->term_id,
                    'numberposts' => 4,
                    'post__not_in' => array($post_id),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => array(
                        array(
                            'key' => 'featured_post',
                            'value' => 'Yes',
                        )
                    )
                )
        );
        if (!empty($featureContent)) {
            ?>
            <div class = "vc_row wpb_row vc_row-fluid category-featured-content">
                <div class="wpex-row clr">
                    <?php
                    wpex_heading(array(
                        'content' => 'Featured Content',
                        'classes' => array('related-posts-title'),
                        'apply_filters' => 'blog_related',
                    ));
                    $wpex_count = 0;
                    $wpex_columns = apply_filters('wpex_related_blog_posts_columns', wpex_get_mod('blog_related_columns', '4'));
                    foreach ($featureContent as $post) : setup_postdata($post);
                        $wpex_count++;
                        $classes = array('related-post', 'clr', 'nr-col');
                        $classes[] = wpex_grid_class($wpex_columns);
                        $classes[] = 'col-' . $wpex_count;
                        ?>
                        <article <?php post_class($classes); ?>>
                            <?php
                            $featured_post_id = $post->ID;
                            $post_url = get_permalink($featured_post_id);
                            ?>
                            <div class="related-post-content clr">
                                <ul class="meta">
                                    <?php
                                    /* $umbrellaCat = wp_get_object_terms($featured_post_id, 'content-categories');
                                      if (!empty($umbrellaCat)) {
                                      ?>
                                      <li class="meta-category">
                                      <?php
                                      foreach ($umbrellaCat as $term) {
                                      if ($term->parent == 0)
                                      continue; // display only child terms
                                      echo '<a href="' . get_term_link($term->slug, 'content-categories') . '">' . $term->name . '</a> ';
                                      }
                                      ?>
                                      </li>
                                      <?php
                                      } */

                                    ?>
                                     <?php if(wp_get_post_terms($post_id,'category')){?>
        <li class="umbrella-category">
          <?php wpex_list_post_terms_custom('category'); ?>
        </li>
        <?php }?>
                                    <?php if(wp_get_post_terms($post_id,'content-categories')){?>
                                    <li class="umbrella-category">
                                      <?php wpex_list_post_terms_custom('content-categories'); ?>
                                    </li>
                                    <?php }?>
                                    
                                </ul>
                                <h4 class="related-post-title entry-title"> <a href="<?php echo $post_url; ?>" rel="bookmark"><?php echo $post->post_title; ?></a> </h4>
                                <div class="related-post-excerpt clr"> <?php echo substr(strip_tags($post->post_content), '0', '200'); ?> </div>
                                <?php
                                $employeeIdArr = get_post_meta($featured_post_id, $key = 'post_authors');
                                if (isset($employeeIdArr[0][0]) && $employeeIdArr[0][0] != ""):
                                    $args = array(
                                        'include' => $employeeIdArr[0][0],
                                        'posts_per_page' => -1,
                                        'orderby' => 'post_title',
                                        'order' => 'ASC',
                                        'post_type' => 'emd_employee',
                                        'post_status' => 'publish',
                                        'suppress_filters' => true
                                    );
                                    $posts_array = get_posts($args);
                                    if (isset($posts_array[0]) && !empty($posts_array[0])) {
                                        ?>
                                        <ul class="post_authors">
                                            <?php
                                            //foreach ($posts_array as $key => $value) {
                                            $value = $posts_array[0];
                                            $employee_id = $value->ID;
                                            $employee_url = get_permalink($employee_id);
                                            $sval = get_post_meta($employee_id, 'emd_employee_photo');
                                            $thumb = wp_get_attachment_image_src($sval[0], 'small');
                                            if (isset($sval[0])) {
                                                $thumb = wp_get_attachment_image_src($sval[0], 'small');
                                                if (isset($thumb[0]) && $thumb[0] != "") {
                                                    $img_url = $thumb[0];
                                                }
                                            } else {
                                                $upload_dir_path = wp_upload_dir();
                                                $img_url = $upload_dir_path['baseurl'] . '/2017/05/default_avatar.png';
                                            }
                                            ?>
                                            <li class="authors-details"> <a href="<?php echo $employee_url; ?>"> <img class="emd-img thumb" src="<?php echo $img_url; ?>" alt="<?php echo $value->post_title; ?>"/> <span class="emp_name"> <?php echo $value->post_title; ?> </span> </a> </li>
                                        </ul>
                                        <?php
                                    }
                                endif;
                                ?>
                            </div>
                        </article>
                        <?php if ($wpex_columns == $wpex_count) $wpex_count = 0; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        }
//if (in_array("category", $url_explode)) { // to category page       
        ?>
        <div class = "vc_row wpb_row vc_row-fluid category-searchform">
            <div class = "wpb_column vc_column_container vc_col-sm-12">
                <div class = "vc_column-inner vc_custom_1497499115628">
                    <div class = "wpb_wrapper">
                        <div id="category-archive-searchform"><?php echo do_shortcode('[searchandfilter slug="media-types"]'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
//}
    }
}

add_action('wpex_hook_content_top', 'append_to_category');

function vc_before_init_actions() {
//.. Code from other Tutorials ..//
// Require new custom Element
    require_once( get_stylesheet_directory() . '/vc-elements/team-quote.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/service-insights.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/service-insights-dynamic.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/about-sections.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/custom-section-heading.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/employees-list.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/product-items.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/jobs-list.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/address-map.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/connect.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/single_post.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/single_category.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/single_quote.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/single_statistic.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/team.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/services_links.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/single_connect.php' );
    //require_once( get_stylesheet_directory() . '/vc-elements/category_posts.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/featured_posts_services.php' );
    require_once( get_stylesheet_directory() . '/vc-elements/audience-pages-posts.php' );

}


add_action('vc_before_init', 'vc_before_init_actions');
if (!function_exists('widgets_init')) {

    function widgets_init() {
        register_sidebar(array(
            'name' => __('Job Disclaimer', 'mmpl'),
            'id' => 'job-disclaimer',
            'description' => __('Display job Disclaimer.', 'mmpl'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '
<h4 class="widget-title">',
            'after_title' => '</h4>
',
        ));
    }

    add_action('widgets_init', 'widgets_init');
}
add_filter('wpex_has_social_share', function( $bool ) {
    if (is_singular('career')) {
        $bool = true;
    }
    return $bool;
}, 40);
add_filter('body_class', function (array $classes) {
    global $post;
    if (isset($post->ID) && 11998 == $post->ID) {
        if (in_array('single-career', $classes)) {
            unset($classes[array_search('single-career', $classes)]);
        }
    }
    return $classes;
});

add_action('init', function () {
    add_rewrite_rule('team/?$', 'index.php?pagename=team', 'top');
}, 1000);

require_once( get_stylesheet_directory() . '/new-functions.php' );


/* Team page title and meta */
add_filter('wpseo_metadesc','custom_meta',999);
function custom_meta( $desc ){

    if (is_singular('emd_employee')) {
		global $post;
		$memberTitle       = $post->post_title;
		
		$memberDesignation = wp_get_post_terms($post->ID, 'jobtitles');
		$memberDesignationList = "";
		if (!is_wp_error($memberDesignation)) {
			$memberDesignationList = implode(",", array_map(function($a)
			{
			return $a->name;
			}, $memberDesignation));
		}
        $desc = "Meet ".$memberTitle.", Avalere's ".$memberDesignationList.". Read a short bio and find contact information here.";
    } 

    return $desc;
}
add_filter('wpseo_title','custom_title',999);
function custom_title( $title ){


    if (is_singular('emd_employee')) {
		global $post;
		$memberTitle       = $post->post_title;
		
		$memberDesignation = wp_get_post_terms($post->ID, 'jobtitles');
		$memberDesignationList = "";
		if (!is_wp_error($memberDesignation)) {
			$memberDesignationList = implode(",", array_map(function($a)
			{
			return $a->name;
			}, $memberDesignation));
		}
        $title = $memberTitle.", ".$memberDesignationList." | Avalere";
    }   
    return $title;
}