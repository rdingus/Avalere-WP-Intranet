<?php
/**
 * Products Post Type Singular Simple Template Framework.
 */
get_header();
global $post;
$mainTitle = $post->post_title;
$subheading = get_field("subheading", $post->ID);
$intro = get_field("intro", $post->ID);
$servicesIds = get_field("advisory_services", $post->ID);
$services = null;
if ($servicesIds) {
    $services = getObjectsByCPT('service', -1, array(), $servicesIds, array(), 'title', 'ASC');
}
$learnMoreEmployeeIds = get_field('learn_more_employee', $post->ID);
$employees = getObjectsByCPT('emd_employee', -1, array(), $learnMoreEmployeeIds);
$employeeName = $employees[0]->post_title;
$employeesPhotoId = get_post_meta($employees[0]->ID, 'emd_employee_photo', true);
$employeeJob = wp_get_post_terms($employees[0]->ID, 'jobtitles')[0]->name;
$employeesPhotoURL = wp_get_attachment_url($employeesPhotoId);
$learnMoreEmployeeContent = get_field('learn_more_content', $post->ID);

/*
$featuredContentIds = get_field("featured_content", $post->ID);
$featuredContent = null;
if ($featuredContentIds) {
	$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',			
            'post_status' => 'publish',
			'post__in'=>$featuredContentIds            
        );		
		

        $featuredContent = new WP_Query($args);
   
}else{
		
		$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',			
            'post_status' => 'publish',
			'posts_per_page'=>3            
        );

		

        $featuredContent = new WP_Query($args);
}*/
global $post;
    $postsarray = array();    
    $posts = NULL;    
    $args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',      
            'post_status' => 'publish',
      'posts_per_page' => '3',  
      'meta_query'=>array(array('key'=>'related_products','value'=>sprintf(':"%s";', $post->ID),'compare'=>'LIKE'))           
        );
    
wp_reset_query();
$featuredContent = new WP_Query($args); 

$titleStyle = '';
if ($title_image = get_field("title_image", $post->ID)) {
    $titleStyle = 'style="background-image:url(' . $title_image . ')"';
}
?>

<div id="content-wrap" class="container clr">
  <?php wpex_hook_primary_before(); ?>
  <section id="primary" class="content-area clr" style="padding-bottom:0px;">
  <?php wpex_hook_content_before(); ?>
  <div id="content" class="site-content clr" role="main">
    <div id="header-wrapper" class="header-wrapper" <?php echo $titleStyle; ?>>
      <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">
        <div class="vc_column_container vc_col-sm-4">
          <h1 class="single-header"><?php echo $mainTitle; ?></h1>
        </div>
        <div class="vc_column_container vc_col-sm-4"> </div>
        <div class="vc_column_container vc_col-sm-4">
          <div class="buttonWrapper">
            <?php if(get_field('contact_button', $post->ID)){?>
            <a class="section-button" target="_blank" href="<?php echo get_field('contact_button', $post->ID); ?>"><?php if(get_field('contact_button_text', $post->ID)){ echo get_field('contact_button_text', $post->ID); } else {echo 'Connect';} ?></a> </div>
          <?php }else{?>
          <a class="section-button" href="<?php echo get_permalink(9553);?>"><?php if(get_field('contact_button_text', $post->ID)){ echo get_field('contact_button_text', $post->ID); } else {echo 'Connect';} ?></a></div>
        <?php }?>
      </div>
    </div>
    <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">
      <div class="vc_column_container vc_col-sm-6">
        <h3 class="single-tagline subheading"><?php echo $subheading; ?></h3>
        <h3 class="single-tagline intro"><?php echo $intro; ?></h3>
      </div>
    </div>
  </div>
  <?php wpex_hook_content_top(); ?>
  <?php
            // YOUR POST LOOP STARTS HERE
            while (have_posts()) : the_post();
                ?>
  <?php
                // Display the featured image
                // You could also instead use the function wpex_get_post_media which returns the post
                // thumbnail, gallery slider, video or audio
                if (has_post_thumbnail() && wpex_get_mod('page_featured_image')) :
                    ?>
  <div id="page-featured-img" class="clr">
    <?php the_post_thumbnail(); ?>
  </div>
  <!-- #page-featured-img -->
  <?php endif; ?>
  <?php
                // Display the post content
                // Note the "entry" class this is used for styling purposes so it's important to use it on any content element 
                ?>
  <div class="entry-content entry clr">
    <?php the_content(); ?>
  </div>
  <!-- .entry-content -->
  <?php
                // Display comments
                comments_template();
                ?>
  <?php
            // YOUR POST LOOP ENDS HERE
            endwhile;
            ?>
  <?php wpex_hook_content_bottom(); ?>
  <?php if ($learnMoreEmployeeIds) { ?>
  <div id="learn-more-wrapper" class="learn-more-wrapper">
    <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">
      <div class="vc_column_container vc_col-sm-4">
        <h2 class="section-title">Learn More</h2>
      </div>
      <div class="vc_column_container vc_col-sm-4"> </div>
      <div class="vc_column_container vc_col-sm-4">
        <div class="buttonWrapper"> <a class="section-button" href="<?php echo get_field('contact_button', $post->ID); ?>">Connect</a> </div>
      </div>
    </div>
    <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">
      <div class="vc_column_container vc_col-sm-12">
        <div style="height:90px;"></div>
      </div>
    </div>
    <div class="learn-more-box">
      <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">
        <div class="learn-more-item vc_column_container vc_col-sm-3"> <a href="<?php echo get_permalink($employees[0]->ID); ?>">
          <div class="learn-more-employee-wrapper">
            <div class="imageWrapper"> <img src="<?php echo $employeesPhotoURL; ?>" alt="<?php echo $employeeName; ?>" /> </div>
            <div class="detailWrapper">
              <h6 class="name"><?php echo $employeeName; ?></h6>
              <h6 class="designation"><?php echo $employeeJob; ?></h6>
            </div>
          </div>
          </a> </div>
        <div class="learn-more-item vc_column_container vc_col-sm-6">
          <div class="learn-more-description"> <?php echo $learnMoreEmployeeContent; ?> </div>
        </div>
      </div>
    </div>
    <div class="bottom-shadow"></div>
  </div>
  <?php } ?>
  <?php
            if ($services) {
                $allservice = (object) array('ID' => 9025, 'post_title' => 'See All Services');
                array_push($services, $allservice);
                ?>
  <div id="services" class="services-wrapper">
    <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">
      <div class="vc_column_container vc_col-sm-6">
        <h2 class="section-title">Advisory Services</h2>
      </div>
    </div>
    <?php 
					$columnItems = ceil(sizeof($services)/2);
					
					
					$servicesParts = array_chunk($services, $columnItems); 

					?>
    <div class="service-box">
      <div class="vc_row wpb_row vc_row-fluid " style="margin-left:0px;margin-right:0px;">
        <?php
                        foreach ($servicesParts as $servicesPart) {
                            
                            ?>
        <div class="wpb_column vc_column_container vc_col-sm-6">
          <?php
                                foreach ($servicesPart as $service) {
                                    $serviceURL = get_permalink($service->ID);
                                    $serviceTitle = $service->post_title;
									$rowClass = '';
                            if ($service == end($servicesPart) && $columnItems == count($servicesPart)) {
                                $rowClass = 'last';
                            }
                                    ?>
          <div class="service-item <?php echo $rowClass;?>"> <i class="arrow-right"></i><a href="<?php echo $serviceURL; ?>"><?php echo $serviceTitle; ?></a> </div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
    <div class="bottom-shadow"></div>
  </div>
  <?php } ?>
  <?php if ($featuredContent->have_posts()) { 
			$wpex_columns = apply_filters( 'wpex_related_blog_posts_columns', wpex_get_mod( 'blog_related_columns', '3' ) );
			?>
  <div id="featured-content" class="featured-content related-posts product-single-b">
    <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">
      <div class="vc_column_container vc_col-sm-4">
        <h2 class="section-title">Featured Content</h2>
      </div>
    </div>
    <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">
      <div style="height:30px;"></div>
    </div>
    <!--<div class="vc_row wpb_row vc_row-fluid">-->
      <?php 
					$i = 0;
					$wpex_count = 0;
					while ( $featuredContent->have_posts() ) : $featuredContent->the_post();
					 
					if($i > 2)
						break;
					
					$wpex_count++; 
				
				 include( locate_template( 'partials/blog/blog-single-related-entry.php' ) );

				 if ( $wpex_columns == $wpex_count ) $wpex_count=0;
				 
				 $i++;
					 endwhile;
					?>
    <!--</div>-->
  </div>
  <?php } ?>

<!-- #content -->
<?php wpex_hook_content_after(); ?>
</section>
<!-- #primary -->
<?php wpex_hook_primary_after(); ?>
</div>
<!-- #content-wrap -->
<?php get_footer(); ?>
