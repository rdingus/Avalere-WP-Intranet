<?php
/**
 * Blog single post related entry
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.5.4.2
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Disable embeds
$show_embeds = apply_filters('wpex_related_blog_posts_embeds', false);

// Check if experts are enabled
$has_excerpt = wpex_get_mod('blog_related_excerpt', true);

// Get post format
$format = get_post_format();

// Get featured image
$thumbnail = wpex_get_post_thumbnail(array(
    'size' => 'blog_related',
        ));

// Add classes
$classes = array('related-post', 'clr', 'nr-col');
$classes[] = wpex_grid_class($wpex_columns);
$classes[] = 'col-' . $wpex_count;
?>

<article <?php post_class($classes); ?>>
  <?php
    $post_id = get_the_ID();
    ///$post_id = 8286;
    // Display post video
    if ($show_embeds && 'video' == $format && $video = wpex_get_post_video_html()) :
        ?>
  <div class="related-post-video"><?php echo $video; ?></div>
  <?php
    // Display post audio
    elseif ($show_embeds && 'audio' == $format && $audio = wpex_get_post_audio_html()) :
        ?>
  <div class="related-post-video"><?php echo $audio; ?></div>
  <?php
    // Display post thumbnail
    elseif ($thumbnail) :

        // Overlay style
        $overlay = wpex_get_mod('blog_related_overlay');
        $overlay = $overlay ? $overlay : 'none';
        ?>
  <figure class="related-post-figure clr <?php echo wpex_overlay_classes($overlay); ?>"> <a href="<?php the_permalink(); ?>" title="<?php wpex_esc_title(); ?>" rel="bookmark" class="related-post-thumb<?php wpex_entry_image_animation_classes(); ?>"> <?php echo $thumbnail; ?>
    <?php wpex_entry_media_after('blog_related'); ?>
    <?php wpex_overlay('inside_link', $overlay); ?>
    </a>
    <?php wpex_overlay('outside_link', $overlay); ?>
  </figure>
  <?php endif; ?>
  <?php
	// Display post excerpt
    if ($has_excerpt) : ?>
    	<?php //$cls = is_page( [12152,12154,12156] ) ? "light-border" : "dark-border"; ?>
    	<!-- post excerpt -->
  <div class="related-post-content dark-border <?php //echo esc_attr( $cls ) ?> clr">
    <ul> 
    <?php if(is_singular('emd_employee') && wp_get_post_terms(get_the_ID(),'category')){?>
      <li class="meta-category">
        <?php wpex_list_post_terms_custom('category',true,true,get_the_ID()); ?>
      </li>
      <?php }?>     
      <?php if(wp_get_post_terms(get_the_ID(),'content-categories')){?>
      <li class="meta-category">
        <?php wpex_list_post_terms_custom('content-categories',true,true,get_the_ID()); ?>
      </li>
      <?php }?>
    </ul>
      <?php
      // display category in post title for Contet Category landing pages, Service landing page, Service detail pages, Audience pages
      $catname = '';
      if(is_single() || is_tax('content-categories') || 'service' == get_post_type() || is_page('services') || is_page(9025) || is_page(12152) || is_page(12154) || is_page(12156) ) {
        if(has_category(5,get_the_ID())){
            $catname = 'Podcast: ';
        }elseif(has_category(4,get_the_ID())){
            $catname = 'Video: ';
        }elseif(has_category(6,get_the_ID())){
            $catname = 'Webinar: ';
        }else{
            $catname = '';
        }
      }
      ?>
    <h4 class="related-post-title entry-title"> <a href="<?php wpex_permalink(); ?>" rel="bookmark">
      <?php //$hout = the_title(); $tout = strlen($hout) > 60 ? substr($hout,0,60)."..." : $hout;?>
      <?php echo $catname . wp_trim_words( get_the_title(), 9, '...' ); ?>
      <?php //the_title()?>
      </a> </h4>
    <!-- .related-post-title -->
    
    <div class="related-post-excerpt clr">
      <?php
              //  wpex_excerpt(array(
                //    'length' => wpex_get_mod('blog_related_excerpt_length', '15'),
              //  ));
                ?>
                 <p><?php $summary = get_field('post_summary');
				 echo $out = strlen($summary) > 100 ? substr($summary,0,100)."..." : $summary;
	 ?></p>
    </div>
    <!-- related-post-excerpt -->
    
    <?php
            $employeeIdArr = get_post_meta($post_id, $key = 'post_authors');
            $str = '';

            if (isset($employeeIdArr[0][0]) && $employeeIdArr[0][0] != ""):

                $args = array(
                    'include' => $employeeIdArr[0][0], // display only firts selected
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
                        foreach ($posts_array as $key => $value) {
                            $employee_id = $value->ID;
                            $employee_url = get_permalink($employee_id);
                            $designation = get_the_terms($employee_id, 'jobtitles');
                            $email = get_post_meta($employee_id, $key = 'emd_employee_email');

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
      <li class="authors-details"> <a href="<?php echo $employee_url; ?>"> <img class="emd-img thumb" src="<?php echo $img_url; ?>" alt="<?php echo $value->post_title; ?>"/> <span class="emp_name"><?php echo $value->post_title; ?></span> </a> </li>
      <?php }
                        ?>
    </ul>
    <?php
                }
            endif;
            ?>
  </div>
  <!-- .related-post-content -->
  
  <?php endif; ?>
</article>
<!-- .related-post -->