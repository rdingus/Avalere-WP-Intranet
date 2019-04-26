<?php
/**
 * Blog entry avatar
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.5.4.2
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get meta sections
$sections = wpex_blog_entry_meta_sections();

// Return if sections are empty
if (empty($sections)) {
    return;
}

// Add class for meta with title
$classes = 'meta clr';
if ('custom_text' == wpex_get_mod('blog_single_header', 'custom_text')) {
    $classes .= ' meta-with-title';
}
?>
<div class="post_meta">
    <ul class="<?php echo esc_attr($classes); ?> <?php echo get_post_type();?>" style="margin-bottom:5px;">
    <?php if(get_post_type() == 'service'){?>
    	<li style="color:#27405d;font-weight:700;"><?php echo ucfirst(get_post_type());?></li>
        <li style="color:#27405d;"><a href="<?php echo get_permalink(get_the_ID()); ?>" rel="bookmark"><?php echo get_the_title(get_the_ID()); ?></a></li>
        <?php }?>
        <?php
        // Loop through meta sections
        foreach ($sections as $key => $val) :
            ?>
            <?php
            // Display Date
            if ('date' == $val && get_post_type() != 'service') :
                ?>
                <li class="meta-date">
                    <time style="color:#27405d;" class="updated" datetime="<?php the_date('Y-m-d'); ?>"<?php wpex_schema_markup('publish_date'); ?>><?php echo get_the_date(); ?></time>
                </li>
                <?php
            // Display Categories
            elseif ('categories' == $val && get_post_type() != 'service')  :
                ?>
                <li class="umbrella-category">
                    <?php 
					if ( is_tax('content-categories' ) ) {
		 $terms = get_the_terms ($post->id, 'content-categories');
		 $cat_links = wp_list_pluck($terms, 'name'); 
    	 $skills_name = implode(", ", $cat_links);
		 echo $skills_name; }else
	 	wpex_list_post_terms_custom('content-categories',true,true,get_the_ID());?>
                </li>
                <?php
            endif;
        endforeach;		
        ?>
        <?php if(is_search()){?>
        <!--<li><?php //echo get_search_query();?></li>-->
        <?php }?>
    </ul>
</div>
<header class="blog-entry-header wpex-clr parvez">
<?php if (get_post_type() == 'service') : ?>
    <h2 class="blog-entry-title entry-title"><a href="<?php wpex_permalink(); ?>" rel="bookmark"><?php the_field('subheading'); ?></a></h2>
<?php else: ?>
	<?php if(/*is_category() || */is_tax('content-categories') ){
		if(has_category(5,get_the_ID())){
			$catname = 'Podcast: ';
		}elseif(has_category(4,get_the_ID())){
			$catname = 'Video: ';
		}elseif(has_category(6,get_the_ID())){
			$catname = 'Webinar: ';
		}else{
			$catname = '';
		}
		
		?>
    <h2 class="blog-entry-title entry-title"><a href="<?php wpex_permalink(); ?>" rel="bookmark"><?php echo $catname;?><?php the_title(); ?></a></h2>
    <?php }else{?>
    <h2 class="blog-entry-title entry-title"><a href="<?php wpex_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
    <?php }?>
<?php endif; ?>
        <?php if (wpex_get_mod('blog_entry_author_avatar')) : ?>
            <?php get_template_part('partials/blog/blog-entry-avatar'); ?>
        <?php endif; ?>
</header>