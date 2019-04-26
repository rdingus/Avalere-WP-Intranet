<?php
/**
 * Products Post Type Singular Simple Template Framework.
 */
get_header();
global $post;
$placeholder = get_stylesheet_directory_uri() . '/images/single-member-image-placeholder.jpg';
$image = wp_get_attachment_url(get_post_meta($post->ID, 'emd_employee_photo', true));
$objectImagesmallArray = wp_get_attachment_image_src(get_post_meta($post->ID, 'emd_employee_photo', true));
$postimagesmall = $objectImagesmallArray[0];
if ($image == "") {
    $image = get_stylesheet_directory_uri() . '/images/single-member-no-image.jpg';
}
if ($postimagesmall == "") {
    $postimagesmall = get_stylesheet_directory_uri() . '/images/member-no-image-small.jpg';
}
$memberName = $post->post_title;
$phone = get_post_meta($post->ID, 'emd_employee_phone', true);
$email = get_post_meta($post->ID, 'emd_employee_email', true);
$address = get_post_meta($post->ID, 'emd_employee_address', true);
$twitter = get_post_meta($post->ID, 'emd_employee_twitter', true);
$linkedin = get_post_meta($post->ID, 'emd_employee_linkedin', true);
$post_authors = get_field('post_authors',$post->ID);
$desc1 = get_the_content($post->ID);

if($desc1 == ''){
	echo '<meta name="robots" content="noindex">';
}?>

<?php echo do_shortcode('[myjsonld]'); ?>

<div id="content-wrap" class="container clr">
    <?php wpex_hook_primary_before(); ?>
    <section id="primary" class="content-area clr" style="padding-bottom:0px;">
        <?php wpex_hook_content_before(); ?>
        <div id="content" class="site-content clr" role="main">
            <?php wpex_hook_content_top(); ?>
            <?php
            // YOUR POST LOOP STARTS HERE
            while (have_posts()) : the_post();
                ?>
                <div class="entry-content entry clr">
                    <?php //the_content();  ?>
                </div>
                <!-- .entry-content -->
                <div id="emd-gp"></div>
                <div class="single-member-wrapper">
                    <div class="single-member-inner-wrapper">
                        <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">
                            <div class="vc_column_container vc_col-sm-6 custom-padding">
                                <div class="imageWrapper"> <img src= "<?php echo $placeholder; ?>" alt="<?php echo $memberTitle; ?>" data-original="<?php echo $image; ?>"/> </div>
                            </div>
                            <div class="vc_column_container vc_col-sm-6 custom-padding">
                                <div class="vc_row wpb_row vc_row-fluid" style="width:100%;">
                                    <div class="vc_column_container vc_col-sm-12 custom-padding">
                                        <div class="member-title">
                                            <h1><span>Meet</span><br />
                                                <?php echo $memberName; ?></h1>
                                        </div>
                                        <div>
                                            <div class="horizontalFixLine"></div>
                                        </div>
                                        <div class="member-data">
                                            <ul>
                                                <?php if ($phone) { ?>
                                                    <li><i class="fa fa-phone"></i><a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a></li>
                                                <?php } ?>
                                                <?php if ($email) { ?>
                                                    <li><i class="fa fa-envelope"></i><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></li>
                                                <?php } ?>
                                                    
                                                <?php if ($address) { ?>
                                                    <li><i class="fa fa-map-marker"></i><?php echo $address; ?></li>
                                                <?php } ?>
                                                 
                                                <?php if ($twitter) { ?>
                                                    <li><i class="fa fa-twitter"></i><a href="<?php echo $twitter; ?>"><?php echo $twitter; ?></a></li>
                                                <?php }?>
                                                <?php if ($linkedin) { ?>
                                                    <li><i class="fa fa-linkedin"></i><a href="<?php echo $linkedin; ?>"><?php echo $linkedin; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="vc_row wpb_row vc_row-fluid ">
                            <div class="vc_column_container vc_col-sm-12 custom-padding">
                                <div style="height:30px;"></div>
                            </div>
                        </div>
                        <div class="vc_row wpb_row vc_row-fluid ">
                            <div class="vc_column_container vc_col-sm-12 custom-padding">
                                <div class="member-description"><?php 
                                /* New Code start 
                                // echo wpautop( get_the_content() );
                                $theContent = get_the_content();
                                $fewwords = explode(" ", $theContent);
                                $str = '';
                                for( $i = 0; $i < 25; $i++ ) {
                                	$str .= $fewwords[ $i ] . " ";
                                }
                                $str = trim($str);                                
                                $words = strlen($str);
                                $str1 = substr($theContent, 0, $words );
                                $str2 = substr($theContent, $words );
                                echo '<div class="emd-effect" style="margin-bottom: 30px; font-weight: bold;">'.$str1.'</div>'.wpautop($str2);
								New Code End */

								/* Old Code 
								if(strpos($post->post_content,".") !== false){
									$parts = explode(".",$post->post_content);
								}else{
									$parts = explode(".",$post->post_content);
								}
								*/
								
								/* Dough Code */
								$matches = null;
								preg_match('/[a-z]\./', $post->post_content, $matches, PREG_OFFSET_CAPTURE);

								if (count($matches) > 0) {
								    $firstMatch = reset($matches);

								    $post->post_content = '<div class="emd-effect" style="margin-bottom: 30px;">' . substr($post->post_content, 0, $firstMatch[1] + strlen($firstMatch[0]))."</p></div><p>".trim(substr($post->post_content, $firstMatch[1]+ strlen($firstMatch[0])));
								}
    
								$post->post_content = str_ireplace("<p>&nbsp;</p>","", str_ireplace("</p><br><p>","</p>\n<p>",$post->post_content));
								
								echo $post->post_content;
								/*	
								foreach($parts as $key=>$part){
									if($key == 0){
										echo '<div class="emd-effect" style="margin-bottom: 30px;">';
											echo $part.'.';
										echo '</div>';
									}else{
										if(trim($part) != "</p>"){

											echo $part.'.';

										}
									}
								}*/
								 ?></div>
                            </div>
                        </div>
                    </div>
                    <?php
					// YOUR POST LOOP ENDS HERE
                endwhile;
                ?>
                <?php
                //$user = get_user_by('email', $email);
				//echo '<pre>';print_r($post_authors);
               // if ($user) {
                    $args = array(
                       // 'author__in' => array($user->ID), //Authors's id's you like to include
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'order' => 'DESC',
                        'orderby' => 'date',
						'post_type'=>'post',
						'meta_query'=>array(array('key'=>'post_authors','value'=>sprintf(':"%s";', $post->ID),'compare'=>'LIKE'))
                    );

                    $object = new WP_Query($args);
					                   
                    $posts = NULL;
                    if ($object->have_posts()):
                        $posts = $object->get_posts();
                    endif;
                    if ($posts) {
                        $placeholder2 = get_stylesheet_directory_uri() . '/images/team-placeholder.jpg';
                        ?>
                        <div class="single-member-posts-wrapper">
                            <div class="vc_row wpb_row vc_row-fluid">
                                <div class="vc_column_container vc_col-sm-12 custom-padding">
                                    <h4 class="heading">Authored Content</h4>
                                </div>
                            </div>
                            <div class="vc_row wpb_row vc_row-fluid">
                                <div class="vc_column_container vc_col-sm-12 custom-padding">
                                    <hr />
                                </div>
                            </div>
                            <?php
                            foreach ($posts as $postauthor) {
                                $postTitle = $postauthor->post_title;
                                if(has_category(5,$postauthor->ID)){
                                    $catname = 'Podcast: ';
                                }elseif(has_category(4,$postauthor->ID)){
                                    $catname = 'Video: ';
                                }elseif(has_category(6,$postauthor->ID)){
                                    $catname = 'Webinar: ';
                                }else{
                                    $catname = '';
                                }                                
                                if (has_post_thumbnail($postauthor->ID)) {
                                    $objectImage = wp_get_attachment_image_src(get_post_thumbnail_id($postauthor), 'full');
                                    $postimage = $objectImage[0];
                                } else {
                                    $postimage = get_stylesheet_directory_uri() . '/images/member-no-image.jpg';
                                }
                                $url = get_permalink($postauthor->ID);
                                $postMedia = wp_get_post_terms($postauthor->ID, 'category');

                                $postMediaString = implode(', ', array_map(function($cat) {
                                            return '<a href="'.get_term_link($cat).'">'.$cat->name.'</a>';
                                        }, $postMedia));
										
										
                                $postCategories = wpex_list_post_terms_custom('content-categories',true,false,$postauthor->ID);

                                
                                $desc = get_field('post_summary', $postauthor->ID);
                                ?>
                                <div class="vc_row wpb_row vc_row-fluid">
                                    <!--<div class="vc_column_container vc_col-sm-3 custom-padding">
                                        <div class="imageWrapper"><a href="<?php //echo $url ?>"> <img src= "<?php //echo $placeholder2; ?>" alt="<?php //echo $postTitle; ?>" data-original="<?php //echo $postimage; ?>"/> </a></div>
                                    </div>-->
                                    <div class="vc_column_container vc_col-sm-12 custom-padding">
                                    	<div class="postmeta"><span class="date"><?php echo date(get_option("date_format"), strtotime($postauthor->post_date)); ?></span> | <span class="meta"><?php echo $postMediaString; ?></span> <span class="meta"><?php if (isset($postCategories)) { echo ' > '.$postCategories; } ?></span></div>
                                        <div class="title">
                                    	<a href="<?php echo get_permalink($postauthor->ID)?>"><h2 style="margin-bottom:0px;margin-top:0px;"><?php echo $catname . $postTitle;?></h2></a>
                                        </div>
                                        
                                        <div class="postdescription"><?php echo $desc; ?></div>
                                        <?php /*?><div class="postauthor"> 
                                            <div class="employeeWrapper">
                                                <div class="employeeImageWrapper"><img src="<?php echo $postimagesmall; ?>" alt="" style="width:64px;height:64px;"/></div>
                                                <div class="employeeWrapper">
                                                    <h6><?php echo $memberName; ?></h6>
                                                </div>
                                            </div>
                                        </div><?php */?>
                                    </div>
                                </div>
                                <?php if ($postauthor !== end($posts)) {
                                    ?>
                                    <div class="vc_row wpb_row vc_row-fluid">
                                        <div class="vc_column_container vc_col-sm-12 custom-padding">
                                            <hr />
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php
               // }
                ?>
            </div>
            <?php wpex_hook_content_bottom(); ?>
        </div>
        <!-- #content -->
        <?php wpex_hook_content_after(); ?>
    </section>
    <!-- #primary -->
    <?php wpex_hook_primary_after(); ?>
</div>
<!-- #content-wrap --> 
<script type='text/javascript'>
    (function ($) {
        $(window).bind('load', function () {
            var image = $(".single-member-inner-wrapper,.single-member-posts-wrapper").find('.imageWrapper img');
            image.each(function (index, element) {
                var original = $(this).attr('data-original');
                $(this).attr('src', original);
            });
        });
    })(jQuery);
</script>
<?php get_footer(); ?>
