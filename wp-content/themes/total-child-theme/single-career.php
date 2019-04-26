<?php

/**

 * Products Post Type Singular Simple Template Framework.

 */

get_header();

?>
<style type="text/css">
.addtoany_list {
	display: none;
	line-height: 0 !important;
}
.addtoany_list.a2a_kit_size_32 a {
	margin-top: -7px;
}
</style>
<?php if (11998 == get_the_ID()) { ?>

<div id="content-wrap" class="container clr">
  <?php wpex_hook_primary_before(); ?>
  <div id="primary" class="content-area clr">
    <?php wpex_hook_content_before(); ?>
    <div id="content" class="site-content clr">
      <?php wpex_hook_content_top(); ?>
      <?php the_content(); ?>
      <?php wpex_hook_content_bottom(); ?>
    </div>
    <!-- #content -->
    <?php wpex_hook_content_after(); ?>
  </div>
  <!-- #primary -->
  <?php wpex_hook_primary_after(); ?>
</div>
<!-- .my-container -->
<?php

} else {

        wp_register_script('print', get_stylesheet_directory_uri() . '/js/print.js', array(), '', false);

        wp_enqueue_script('print');

        global $post;

        $mainTitle = $post->post_title;

        $jobDate = date(get_option('date_format'), strtotime(get_field('last_date', $post->ID)));

        $applyurl = get_field("job_url", $post->ID);

        $pdfurl = get_field("pdf_download_url", $post->ID);

        $intro = get_field("intro", $post->ID);

        $employeeObject = get_field("employee", $post->ID);

        $employeename = $employeeObject->post_title;

        $employeedesignation = wp_get_post_terms($employeeObject->ID, 'jobtitles')[0]->name;

        $employeeimageurl = wp_get_attachment_image_src(get_post_meta($employeeObject->ID, 'emd_employee_photo', true), 'thumbnail');

        $employeeurl = get_permalink($employeeObject->ID);



        //Schema Additions

        $description = get_field("description", $post->ID);

        $educationRequirements = get_field("education_requirements", $post->ID);

        $experienceRequirements = get_field("experience_requirements", $post->ID);

        $skills = get_field("skills", $post->ID);

        $employmentType = get_field("employment_type", $post->ID);

        $howToApply = get_field("how_to_apply", $post->ID); ?>

<div id="content-wrap" class="container clr">
  <?php wpex_hook_primary_before(); ?>
  <section id="primary" class="content-area clr" style="padding-bottom:0px;">
    <?php wpex_hook_content_before(); ?>
    <div id="content" class="site-content clr" role="main">
      <div id="header-wrapper" class="header-wrapper single-job vc_row wpb_row vc_row-fluid m-0">
        <div class="vc_column_container vc_col-sm-12 custom-padding">
          <div class="vc_column-inner" style="margin-bottom:0px;">
            <div class="vc_row wpb_row vc_row-fluid" style="margin-left:-30px;margin-right:-30px;">
              <div class="vc_column_container vc_col-sm-6 custom-padding">
                <ul class="breadcrumb">
                  <li><a href="<?php echo get_permalink(9505); ?>">Job Opportunity</a></li>
                  <li class="sep">|</li>
                  <li><?php echo $jobDate ?></li>
                </ul>
              </div>
              <div class="vc_column_container vc_col-sm-6 custom-padding">
                <div class="share-download-print-wrapper">
                  <ul>
                    <?php if (function_exists('ADDTOANY_SHARE_SAVE_KIT')) {

            ADDTOANY_SHARE_SAVE_KIT();

        } ?>
                    <a class="post-icon-share" data-network="sharethis" id="post-icon-share" href="javascript:void(0);"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/post-icon-share.png" /></a>
                    </li>
                    <li class="sep"></li>
                    <li><a href="javascript:window.print()" title="Print" ><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/post-icon-print.png"></a></li>
                    <!--<li class="sep"></li>

                                            <li><a href="<?php //echo $pdfurl;?>" title="PDF Download"><img src="<?php //echo get_stylesheet_directory_uri();?>/images/post-icon-download.png"></a></li>-->
                  </ul>
                  <script>

                                            jQuery(document).ready(function(){

                                                var x = 0;

                                                jQuery('#post-icon-share').click( function(e) {

                                                    e.preventDefault();

                                                    e.stopPropagation();

                                                    // jQuery('.addtoany_list').toggle();

                                                    if (x++ % 2 == 0) {

                                                        jQuery('.addtoany_list').css("display", "inline-block");

                                                    } else {

                                                        jQuery('.addtoany_list').css("display", "none");

                                                    }

                                                });

                                                jQuery('.addtoany_list').click( function(e) {

                                                    e.stopPropagation();

                                                });

                                                jQuery('body').click( function() {

                                                    jQuery('.addtoany_list').hide();

                                                });

                                            });

                                        </script>
                </div>
              </div>
            </div>
            <div class="vc_row wpb_row vc_row-fluid" style="margin-left:-30px;margin-right:-30px;">
              <div class="vc_column_container vc_col-sm-12 custom-padding">
                <h1><?php echo $mainTitle; ?></h1>
              </div>
              <div class="vc_column_container vc_col-sm-12 custom-padding">
                <div class="employeeWrapper"> <a href="<?php echo $employeeurl; ?>">
                  <div class="employeeImageWrapper"><img src="<?php echo $employeeimageurl[0]; ?>" alt="<?php echo $employeename; ?>" style="width:75px;height:75px;"></div>
                  <div class="employeeWrapper">
                    <h6 class="name"><?php echo $employeename; ?></h6>
                    <h6 class="designation"><?php echo $employeedesignation; ?></h6>
                  </div>
                  </a> </div>
              </div>
              <div class="vc_column_container vc_col-sm-12 custom-padding">
                <div style="height:30px;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php wpex_hook_content_top(); ?>
      <?php

                // YOUR POST LOOP STARTS HERE

                while (have_posts()) : the_post(); ?>
      <div style="display:none;">
        <div id="singleJobShare" ><?php echo do_shortcode('[vcex_social_share style="three-d"]'); ?></div>
      </div>
      <div class="job-content-wrapper vc_row wpb_row vc_row-fluid m-0">
        <div class="vc_column_container vc_col-sm-12">
          <div class="vc_column-inner" style="margin-bottom:0px;">
            <div class="vc_row wpb_row vc_row-fluid" style="margin-left:-30px;margin-right:-30px;">
              <?php if ($applyurl) {

                    ?>
              <div class="vc_column_container vc_col-sm-12 custom-padding">
                <div class="applyurl custom-padding"> <a href="<?php echo $applyurl; ?>">Apply</a> </div>
                <div class="custom-padding">
                  <hr />
                </div>
              </div>
              <?php

                } ?>
              <?php if ($intro) {

                    ?>
              <div class="vc_column_container vc_col-sm-12 custom-padding">
                <div class="intro custom-padding"> <?php echo $intro; ?> </div>
              </div>
              <?php

                } ?>
              <div class="vc_column_container vc_col-sm-12 custom-padding">
                <div class="entry-content entry clr custom-padding" vocab="http://schema.org/" typeof="JobPosting">
                  <meta property="title" content="<?php echo $mainTitle; ?>" />
                  <meta property="industry" content="healthcare" />
                  <meta property="datePosted" content="<?php echo $jobDate ?>" />
                  <meta property="employmentType" content="<?php echo $employmentType ?>" />
                  <?php the_content(); ?>
                  <?php if ($description) {

                    ?>
                  <div property="description"> <?php echo $description; ?> </div>
                  <?php

                } ?>
                  <h2>Skills, Experience, and Other Job-Related Requirements</h2>
                  <?php if ($educationRequirements) {

                    ?>
                  <h3>Education Requirements</h3>
                  <div property="educationRequirements"> <?php echo $educationRequirements; ?> </div>
                  <?php

                } ?>
                  <?php if ($skills) {

                    ?>
                  <h3>Skill Requirements</h3>
                  <div property="skills"> <?php echo $skills; ?> </div>
                  <?php

                } ?>
                  <?php if ($experienceRequirements) {

                    ?>
                  <h3>Experience Requirements</h3>
                  <div property="experienceRequirements"> <?php echo $experienceRequirements; ?> </div>
                  <?php

                } ?>
                  <?php if ($howToApply) {

                    ?>
                  <h2>How to Apply</h2>
                  <?php echo $howToApply; ?>
                  <?php

                } ?>
                  <div property="hiringOrganization" typeof="LocalBusiness">
                    <meta property="name" content="Avalere Health" />
                    <meta property="sameAs" content="https://avalere.com" />
                    <meta property="image" content="https://avalere.com/wp-content/uploads/2018/12/avalere-mobile-logo.jpg" />
                  </div>
                  <div property="jobLocation" typeof="Place">
                    <div property="address" typeof="PostalAddress">
                      <meta property="streetAddress" content="1350 Connecticut Ave NW, Suite 900"/>
                      <meta property="addressLocality" content="Washington"/>
                      <meta property="addressRegion" content="DC"/>
                      <meta property="postalCode" content="20036"/>
                      <meta property="addressCountry" content="US"/>
                    </div>
                  </div>
                </div>
              </div>
              <?php if (is_active_sidebar('job-disclaimer')) : ?>
              <?php dynamic_sidebar('job-disclaimer'); ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php

                    // Display the post content

                    // Note the "entry" class this is used for styling purposes so it's important to use it on any content element

                    ?>
      <!-- .entry-content -->
      <?php

                // YOUR POST LOOP ENDS HERE

                endwhile; ?>
      <?php wpex_hook_content_bottom(); ?>
    </div>
    <!-- #content -->
    <?php wpex_hook_content_after(); ?>
  </section>
  <!-- #primary -->
  <?php wpex_hook_primary_after(); ?>
</div>
<!-- #content-wrap -->
<?php

    } get_footer(); ?>
