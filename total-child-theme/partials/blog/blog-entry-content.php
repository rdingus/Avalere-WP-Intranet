<?php
/**
 * Blog entry layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.6.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$post_id = get_the_ID();

// Should we check for the more tag?
$check_more_tag = apply_filters('wpex_check_more_tag', true);
?>

<div class="blog-entry-excerpt wpex-clr">

    <?php
    // Display excerpt if auto excerpts are enabled in the admin
    if (wpex_get_mod('blog_exceprt', true)) :

        // Check if the post tag is using the "more" tag
        if ($check_more_tag && strpos(get_the_content(), 'more-link')) {

            // Display the content up to the more tag
            the_content('', '&hellip;');

            // Otherwise display custom excerpt
        } else {
			if(get_field('description',$post_id)){
				echo '<p>';
				wpex_excerpt(array(
				'post_id'=>$post_id,
				'custom_output'=>get_field('description',$post_id),
                'length' => wpex_excerpt_length(),
            ));
			echo '</p>';
			}else{
			 echo '<p>'.get_field('post_summary').'</p>';
            // Display custom excerpt
           // wpex_excerpt(array(
			//	'post_id'=>$post_id,
          //      'length' => wpex_excerpt_length(),
         //   ));
			}

            $employeeIdArr = get_post_meta($post_id, $key = 'post_authors');
			//echo '<pre>';print_r($employeeIdArr);
            $str = '';
            if (isset($employeeIdArr[0]) && !empty($employeeIdArr[0])):

                $args = array(
                    'post__in' => $employeeIdArr[0],
                    'posts_per_page' => -1,
                    'orderby' => 'post__in',
                    'order' => 'DESC',
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
        }

    // If excerpts are disabled, display full content
    else :

        the_content('', '&hellip;');

    endif;
    ?>

</div><!-- .blog-entry-excerpt -->