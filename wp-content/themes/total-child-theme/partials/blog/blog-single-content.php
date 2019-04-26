<?php
/**
 * Single blog post content
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
$postId = get_the_ID();

if (has_category('webinars')) {

   /* $start_time = get_post_meta($postId, $key = 'start_time',true);
    $end_time = get_post_meta($postId, $key = 'end_time');
    $register_url = get_post_meta($postId, $key = 'register_url');
    $recording_url = get_post_meta($postId, $key = 'recording_url');
    $webinar_slides_url = get_post_meta($postId, $key = 'webinar_slides_url');*/
	$start_time = get_field('start_time',$postId);
    $end_time = get_field('end_time',$postId);
    $register_url = get_field('register_url',$postId);
    $recording_url = get_field('recording_url',$postId);
    $webinar_slides_url = get_field('webinar_slides_url',$postId);
	
    //if (isset($webinar_slides_url[0]) && $webinar_slides_url[0] != "") :
    ?>
    <div class="vc_row wpb_row vc_row-fluid">
        <div class="wpb_column vc_column_container vc_col-sm-12">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">
                    <div class="webinar_access post-container">
                        <?php
                        // set timezone
                        date_default_timezone_set ('America/New_York');
                        //echo(date_default_timezone_get());
                        $todayData = date('YmdHis'); //2018-06-14 13:00:00
                        if (isset($start_time) && $start_time != "" && isset($end_time) && $end_time) {

                            $A = strtotime($start_time);
                            $B = strtotime($end_time);
                            $C = strtotime($todayData);
							// var_dump($A);
       						// var_dump($B);
       						// var_dump($C);
                            // $arr = range($A,$B);
                            // print_r(range($A,$B));
                           //if ((($C < $A) && ($C > $B)) || (($C > $A) && ($C < $B))) {
							     //if (!in_array($C, range($A,$B))) { - ORIG
                                if($C > $B) {

                                ?>
                                <?php if (isset($recording_url) && $recording_url != "") {
                                    ?>
                                    <div class="vc_btn3-container vc_btn3-center" id="access_recording_btn">
                                        <a style="background-color:#fe6b2d; color:#ffffff;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom video-btn" href="<?php echo $recording_url; ?>" title="Access Recording" target="_blank">Access Recording</a>
                                    </div>
                                <?php } ?>
                                <?php if (isset($webinar_slides_url) && $webinar_slides_url != "") { ?>
                                    <div class="vc_btn3-container vc_btn3-center" id="webinar_slider_btn">
                                        <a style="background-color:#fe6b2d; color:#ffffff;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom video-btn" href="<?php echo $webinar_slides_url; ?>" title="Access Webinar Slides" target="_blank">Access Webinar Slides</a>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if (isset($register_url) && $register_url != "") { ?>
                                    <div class="vc_btn3-container vc_btn3-center" id="register_btn">
                                        <a style="background-color:#fe6b2d; color:#ffffff;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom video-btn" href="<?php echo $register_url; ?>" title="Register" target="_blank">Register</a>
                                    </div>
                                <?php } ?>
                            <?php }
                            ?>
                            <?php
                        } else {
                            ?>
                            <?php if (isset($register_url) && $register_url != "") { ?>
                                <div class="vc_btn3-container vc_btn3-center" id="register_btn">
                                    <a style="background-color:#fe6b2d; color:#ffffff;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom video-btn" href="<?php echo $register_url; ?>" title="Register">Register</a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
//endif;
}

if (has_category('podcasts')) {

    $featured_quote = get_post_meta($postId, $key = 'featured_quote');
    $featured_quote_author = get_post_meta($postId, $key = 'featured_quote_author');
    if (isset($featured_quote[0]) && $featured_quote[0] != "" && isset($featured_quote_author[0]) && $featured_quote_author[0] != "") :
        ?>
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner ">
                    <div class="wpb_wrapper">
                    	<!--<div style="color:#1c3857">-->
                        <div class="author_quotes post-container">
                            <q>&ldquo;<?php the_field('featured_quote'); ?>&rdquo;</q>
                            <span class="quotes_author_name"><?php the_field('featured_quote_author'); ?></span>
                        </div>
                        <!--</div>-->
                    </div>                    
                </div>
            </div>
        </div>
    <?php
    endif;

    $sound_cloud_url = get_post_meta($postId, $key = 'sound_cloud_url');
    if (isset($sound_cloud_url[0]) && $sound_cloud_url[0] != "") :
        ?>
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner ">
                    <div class="wpb_wrapper">
                        <div class="podcast_sound post-container">
                            <?php
                            $urls = get_field('sound_cloud_url');
                            $sce = new SoundCloudEmbed($urls);
                            echo $sce->getEmbededText();
                            //https://soundcloud.com/avalere-health/digital-health-the-missing-partner-in-pain-management
                            ?>
                            <!-- <iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/461089101&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endif;

    echo do_shortcode('[display-panelists]');
}
?>

<?php
$thecontent = get_the_content();
if(!empty($thecontent)) { ?>
<div class="single-blog-content entry clr"<?php wpex_schema_markup('entry_content'); ?>>
<?php the_content(); ?>
</div>
<?php } ?> 
<?php echo do_shortcode('[myjsonld]'); ?>

<?php
if (has_category('webinars')) {
    echo do_shortcode('[display-panelists]');
}
if (has_category('videos')) {
    $webinar_slides_url = get_post_meta($postId, $key = 'webinar_slides_url');
    if (isset($webinar_slides_url[0]) && $webinar_slides_url[0] != "") :
        ?>
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner ">
                    <div class="wpb_wrapper post-container">
                        <div class="webinar_slides">
                            <p>Access the 2018 Outlook Document and Webinar Slides: </p>
                            <div class="vc_btn3-container vc_btn3-center" id="see-full-calendar">
                                <a style="background-color:#fe6b2d; color:#ffffff;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom video-btn" href="<?php echo $webinar_slides_url[0]; ?>" title="Access">Access</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php
    $video_embed_url = get_post_meta($postId, $key = 'video_embed_url');
    if (isset($video_embed_url[0]) && $video_embed_url[0] != "") :
        ?>
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner ">
                    <div class="wpb_wrapper">
                        <div class="post_video">

                            <iframe src="<?php echo $video_embed_url[0]; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endif;
}

// Page links (for the <!-nextpage-> tag)
get_template_part('partials/link-pages');
?>