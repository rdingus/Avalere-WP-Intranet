<?php
/**
 * Single blog post title
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<style type="text/css">
.pdfprnt-button-pdf {
 background-image: url(<?php echo get_stylesheet_directory_uri() ?>/images/post-icon-download.png); background-repeat: no-repeat; background-position: center; width: 20px; height: 20px; }
.addtoany_list { display: none; }
.addtoany_list.a2a_kit_size_32 a { margin-top: -7px; }
</style>
<?php
// Return if disabled
if (!wpex_get_mod('blog_post_meta', true)) {
    return;
}

// Get meta sections
$sections = wpex_blog_single_meta_sections();

// Return if sections are empty
if (empty($sections)) {
    return;
}

// Add class for meta with title
$classes = 'meta clr';
if ('custom_text' == wpex_get_mod('blog_single_header', 'custom_text')) {
    $classes .= ' meta-with-title';
}

$post_id = get_the_ID();
?>
<div class="post-header">
  <div class="title_top">
    <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px">
      <div class="wpb_column vc_column_container vc_col-sm-10">
        <div class="post_meta left_section">
          <ul class="<?php echo esc_attr($classes); ?>">
            <?php
                // Loop through meta sections
                foreach ($sections as $key => $val) :
                    ?>
            <?php
                    // Display Date
                    if ('date' == $val) :
                        ?>
            <li class="meta-date">
              <time class="updated" datetime="<?php the_date('Y-m-d'); ?>"<?php wpex_schema_markup('publish_date'); ?>><?php echo get_the_date(); ?></time>
            </li>
            <?php
                    // Display Categories
                    elseif ('categories' == $val) :
                        ?>
            <?php if(is_singular('emd_employee') && wp_get_post_terms($post_id,'category') || is_single()){?>
            <li class="umbrella-category">
              <?php wpex_list_post_terms_custom('category'); ?>
            </li>
            <?php }?>
            <?php if(wp_get_post_terms($post_id,'content-categories')){?>
            <li class="umbrella-category">
              <?php wpex_list_post_terms_custom('content-categories'); ?>
            </li>
            <?php }?>
            <?php
                    endif;
                endforeach;
                ?>
          </ul>
        </div>
      </div>
      <div class="wpb_column vc_column_container vc_col-sm-2">
        <div class="right_section">
        <ul>
          <li>
            <?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { ADDTOANY_SHARE_SAVE_KIT(); } ?>
            <a class="post-icon-share" data-network="sharethis" id="post-icon-share" href="javascript:void(0);"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/post-icon-share.png" /></a> </li>
          <li><a class="post-icon-print" href="javascript:window.print()"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/post-icon-print.png" /></a></li>
          <li>
            <?php if(function_exists('mpdf_pdfbutton_my')) mpdf_pdfbutton_my(true, '', ''); ?>
          </li>
        </ul>
        <script>
                jQuery(document).ready(function(){
                    var x = 0;  
                    jQuery('#post-icon-share').click( function(e) {                       
                        e.preventDefault(); 
                        e.stopPropagation(); 
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
  </div>
  <header class="single-blog-header clr">
  <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px">
    <div class="wpb_column vc_column_container vc_col-sm-10">
      <h1 class="single-post-title entry-title"<?php wpex_schema_markup('headline'); ?>>
        <?php the_title(); ?>
      </h1>
      <?php
        $employeeIdArr = get_post_meta($post_id, $key = 'post_authors');
        if ((is_singular('post')) && (isset($employeeIdArr[0]) && !empty($employeeIdArr[0]))):
            ?>
      <ul class="post_authors">
        <?php
                foreach ($employeeIdArr[0] as $employee_id) {
                    $employeeData = get_post($employee_id);
                    if (!empty($employeeData)) {
                        $employee_url = get_the_permalink($employee_id);
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
        <li class="authors-details"> <a href="<?php echo $employee_url; ?>"> <img class="emd-img thumb" src="<?php echo $img_url; ?>" alt="<?php echo $employeeData->post_title; ?>"/> <span class="emp_name"><?php echo $employeeData->post_title; ?></span> </a> </li>
        <?php
                    }
                }
                ?>
      </ul>
      <?php
        endif;
        ?>
    </div>
    <div class="wpb_column vc_column_container vc_col-sm-2"> </div>
  </div>
</header>
</div>


<?php
$old_medium_image = get_post_meta($post_id, $key = 'old_medium_image');
$old_pdf_download = get_post_meta($post_id, $key = 'old_pdf_download');
$old_pdf_chart_pack = get_post_meta($post_id, $key = 'old_pdf_chart_pack');
$pdf_download = get_field('pdf_download', $post_id);
$pdf_chart_pack = get_field('pdf_chart_pack', $post_id);
$white_paper_pdf = get_field('white_paper_pdf', $post_id);

//if ((isset($old_pdf_download[0]) && $old_pdf_download[0] != "") || (!empty($pdf_download)) || (isset($old_pdf_chart_pack[0]) && $old_pdf_chart_pack[0] != "") || (!empty($pdf_chart_pack)) || (!empty($white_paper_pdf))) :
?>
<div class="vc_row wpb_row vc_row-fluid">
  <div class="wpb_column vc_column_container vc_col-sm-12">
    <div class="wpb_wrapper post-container">
      <ul class="pdf_chart">
        <?php
                if ((isset($old_pdf_download[0]) && $old_pdf_download[0] != "") || (!empty($pdf_download))) {

                    $pdfDownloadUrl = '';

                    if (!empty($pdf_download)) {
                        $pdfDownloadUrl = $pdf_download['url'];
                    } else if (isset($old_pdf_download[0]) && $old_pdf_download[0] != "") {
                        $pdfDownloadUrl = $old_pdf_download[0];
                    }

                    if ($pdfDownloadUrl != "") {
                        ?>

                        <li id="pdf-download">
                            <a href="<?php echo $pdfDownloadUrl; ?>" title="PDF Download" download><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/post-icon-download.png" /><span>PDF</span></a>
                        </li>
                        <?php
                    }
                }

                if (!empty($white_paper_pdf) && $white_paper_pdf['url'] != "") {
                    $whitePaperDownloadUrl = $white_paper_pdf['url'];
                    ?>
        <li id="white-paper-download"> <a href="<?php echo $whitePaperDownloadUrl; ?>" title="White Paper Download" download><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/post-icon-download.png" /> <span>White Paper</span></a> </li>
        <?php }

                if ((isset($old_pdf_chart_pack[0]) && $old_pdf_chart_pack[0] != "") || (!empty($pdf_chart_pack))) {
                    $chartDownloadUrl = '';

                    if (!empty($pdf_chart_pack)) {
                        $chartDownloadUrl = $pdf_chart_pack['url'];
                    } else if (isset($old_pdf_chart_pack[0]) && $old_pdf_chart_pack[0] != "") {
                        $chartDownloadUrl = $old_pdf_chart_pack[0];
                    }

                    if ($chartDownloadUrl != "") {
                        ?>
        <li id="chart-download"> <a href="<?php echo $chartDownloadUrl; ?>" title="Chart Download" download><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/post-icon-download.png" /> <span>Chart Pack</span></a> </li>
        <?php
                    }
                }

                ?>
      </ul>
    </div>
  </div>
</div>
<?php
//endif;
?>
<div class = "vc_row wpb_row vc_row-fluid">
  <div class = "wpb_column vc_column_container vc_col-sm-12">
    <div class = "vc_column-inner">
      <div class = "wpb_wrapper post-container"> 
        <!--<div class = "vc_separator wpb_content_element vc_separator_align_center vc_sep_width_100 vc_sep_border_width_2 vc_sep_pos_align_center vc_separator_no_text vc_sep_color_grey"> <span class = "vc_sep_holder vc_sep_holder_l"> <span class = "vc_sep_line"></span> </span> <span class = "vc_sep_holder vc_sep_holder_r"> <span class = "vc_sep_line"></span> </span> </div>-->
        <div class = "post_summary">
          <?php
            if (has_category('webinars')) {

                $start_date = get_field('start_time',$post_id);
                $end_date = get_field('end_time',$post_id);
                $current_date = date('YmdHis'); //2018-06-14 13:00:00

                if (isset($start_date) && $start_date != "" && isset($end_date) && $end_date) {
                  
                  $start_time = strtotime($start_date);
                  $end_time = strtotime($end_date);
                  $current_time = strtotime($current_date);

                  // if Webinar date / time has not passed, display additional details
                  if($current_time < $end_time) {
                    echo("<h2>Webinar Details</h2>");
                    echo(the_title() . "<br />");
                    echo(date("F j, Y", $start_time) . "<br />");
                    echo(date("g:i", $start_time) ."&ndash;". date("g:i A", $end_time) ." ET");
                    echo("<h2>&nbsp;</h2>");
                  }

                }
                
            }
          ?>

          <h2>Summary</h2>
          <?php the_field('post_summary');?>
        </div>
        <?php
          $extraClass = '';
          if (has_category('videos')) {
              $extraClass = 'video-devider';
          }
        ?>
        <!--<div class="vc_separator wpb_content_element vc_separator_align_center vc_sep_width_100 vc_sep_border_width_2 vc_sep_pos_align_center vc_separator_no_text vc_sep_color_grey <?php //echo $extraClass; ?>"> <span class="vc_sep_holder vc_sep_holder_l"> <span class="vc_sep_line"></span> </span> <span class="vc_sep_holder vc_sep_holder_r"> <span class="vc_sep_line"></span> </span> </div>--> 
      </div>
    </div>
  </div>
</div>
