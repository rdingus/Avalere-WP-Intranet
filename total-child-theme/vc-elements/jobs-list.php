<?php
class vcJobsList extends WPBakeryShortCode
{
    
    // Element Init
    function __construct()
    {
        add_action('init', array(
            $this,
            'vc_jobs_list_mapping'
        ));
        add_shortcode('vc_jobs_list', array(
            $this,
            'vc_jobs_list_html'
        ));
    }
    
    // Element Mapping
    public function vc_jobs_list_mapping()
    {
        
        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }
     $jobsList = $jobs = array();
		$args   = array(
            'post_type' => 'emd_employee',
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => 10,
            'post_status' => 'publish',
            
        );
        $object = new WP_Query($args);
        if ($object->have_posts()):
			$jobsList = $object->get_posts();            
        endif;
         $jobs = array_map(function($employee){ return array($employee->ID,$employee->post_title);},$jobsList);   
        // Map the block with vc_map()
        vc_map(array(
            'name' => __('VC Jobs List', 'text-domain'),
            'base' => 'vc_jobs_list',
            'description' => __('Display employee list', 'text-domain'),
            'category' => __('My Custom Shortcodes', 'text-domain'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'text-domain'),
                    'param_name' => 'title',
                    'value' => ''
                ),				
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'text-domain'),
                    'param_name' => 'description',
                    'value' => ''
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'text-domain'),
                    'param_name' => 'button_text',
                    'value' => ''
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button URL', 'text-domain'),
                    'param_name' => 'button_url',
                    'value' => ''
                ),

            )
        ));
        
    }    
   
    // Element HTML
    public function vc_jobs_list_html($atts)
    {
		extract(shortcode_atts(array(
		"title"=>"",
		"description"=>"",			
		"button_text"=>"",
		"button_url"=>"",
		) , $atts));	
		
		$arealist = get_terms( array(
		    'taxonomy' => 'job-opportunity-area',
		    'hide_empty' => false,
		) );
		$locationlist = get_terms( array(
		    'taxonomy' => 'job-opportunity-location',
		    'hide_empty' => false,
		) );	
        
		$args   = array(
            'post_type' => 'career',
            'order' => 'DESC',
			'meta_key' => 'last_date',
            'orderby' => 'meta_value_num',
            'posts_per_page' => 10,
            'post_status' => 'publish',
            
        );
		if(isset($_POST['area']) && $_POST['area'] != ""){
			$args['tax_query'] = array(array(
			'taxonomy' => 'job-opportunity-area',
            'field' => 'slug',
            'terms' => array($_POST['area']),
			));
		}
		if ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} elseif ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} else {
				$paged = 1;
			}
			$args['paged'] = $paged;
			//echo '<pre>';print_r($args);
        $object = new WP_Query($args);
        if ($object->have_posts()):
			$jobsList = $object->get_posts();            
        endif;

        
        
        $output = '';
        $output .= '<div id="jobs-list-wrapper" class="jobs-list-wrapper">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex jobs-header">';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output .='<h2 class="section-title">'.$title.'</h2>';
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					if($description){
						$output .='<div class="section-description">'.$description.'</div>';
					}
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-4 textright">';
					$output .= '<div class="buttonWrapper">';
					if($button_text){
						$output .='<a class="section-button" href="'.$button_url.'">'.$button_text.'</a>';
					}
									$output .= '</div>';
				$output .= '</div>';
				
			$output .= '</div>';
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output.='<div style="height:30px;"></div>';
				$output .= '</div>';	
			$output .= '</div>';
			$output .= '<form method="post" action="'.get_permalink(9505).'">';
			$output .= '<div id="category-archive-searchform" class="vc_row wpb_row vc_row-fluid filter">';
				$output .= '<div class="vc_column_container vc_col-sm-12" style="padding-left:15px;padding-right:15px;">';
					$output .= '<label><select name="area" class="changer" onchange="this.form.submit();">';
						$output .= '<option value="" >Jobs by Area</option>';
						foreach($arealist as $area){
							$sel = "";
							if(isset($_POST['area']) && $_POST['area'] == $area->slug) {
								$sel = 'selected="selected"';
							}
							$output .= '<option value="'.$area->slug.'" '.$sel.'>'.$area->name.'</option>';
						}
					$output .= '</select></label>';
					/*$output .= '<label id="location"><select name="location" class="changer">';
						$output .= '<option value="">Jobs by Location</option>';
						foreach($locationlist as $location){
							$output .= '<option value="'.$location->slug.'">'.$location->name.'</option>';
						}
					$output .= '</select></label>';*/
				$output .= '</div>';	
			$output .= '</div>';
			$output .= '</form>';
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output.='<div style="height:30px;"></div>';
				$output .= '</div>';	
			$output .= '</div>';	
			if($jobsList){

				foreach($jobsList as $job){
					$jobareas = wp_get_post_terms($job->ID,'job-opportunity-area');
					$joblocations = wp_get_post_terms($job->ID,'job-opportunity-location');
					$jobareaslist = $joblocationslist = '';
					if(!is_wp_error($jobareas)){
						$jobareaslist = implode(" ",array_map(function($a){ return $a->slug;},$jobareas));
					}
					if(!is_wp_error($joblocations)){
						$joblocationslist = implode(" ",array_map(function($a){ return $a->slug;},$joblocations));
					}
					$last ='';
					if ($job === end($jobsList)){
						$last = ' last';
					}
					
					$url = get_permalink($job->ID);
					
						
					$jobTitle = $job->post_title;
					$jobDescription = get_field('intro',$job->ID);
					$jobDate = date(get_option('date_format'),strtotime(get_field('last_date',$job->ID)));
					$placeholder = get_stylesheet_directory_uri().'/images/job-placeholder.jpg';
					$image = get_the_post_thumbnail_url($job->ID);
					$output .= '<div class="job-box '.$jobareaslist.' '.$joblocationslist.'">';																			
						$output .= '<a href="'.$url.'">';							
							$output .= '<div class="vc_row wpb_row vc_row-fluid jobs-content '.$last.'">';		
								$output .= '<div class="vc_column_container vc_col-sm-12 padding0">';
									$output .= '<div class="vc_row wpb_row vc_row-fluid">';		
									//$output .= '<div class="vc_column_container vc_col-sm-3" style="padding-left:15px;padding-right:15px;">';
									//$output .= '<div class="imageWrapper">';
									//$output .= '<img src="'.$placeholder.'" alt="'.$jobTitle.'" data-original="'.$image.'"/>';
									//$output .= '</div>';								
									//$output .= '</div>';	
										$output .= '<div class="vc_column_container vc_col-sm-9" style="padding-left:15px;padding-right:15px;">';		
											$output .= '<div class="titleWrapper">';
											$output .= $jobTitle;									
											$output .= '</div>';
											$output .= '<div class="descriptionWrapper">';
											$output .= $jobDescription;									
											$output .= '</div>';
										$output .= '</div>';
										$output .= '<div class="vc_column_container vc_col-sm-3" style="padding-left:15px;padding-right:15px;">';	
											$output .= '<div class="dateWrapper textright">';
											$output .= $jobDate;									
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';					
						$output .= '</a>';
					$output .= '</div>';
				}
		// Arrow style
		$arrow_style = wpex_get_mod( 'pagination_arrow' );
		$arrow_style = $arrow_style ? esc_attr( $arrow_style ) : 'angle';
		
		// Arrows with RTL support
		$prev_arrow = is_rtl() ? 'fa fa-' . $arrow_style . '-right' : 'fa fa-' . $arrow_style . '-left';
		$next_arrow = is_rtl() ? 'fa fa-' . $arrow_style . '-left' : 'fa fa-' . $arrow_style . '-right';
		
			// Previous text
			$prev_text = '<span class="' . $prev_arrow . '" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Previous', 'total' ) . '</span>';
			// Next text
			$next_text = '<span class="' . $next_arrow . '" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Next', 'total' ) . '</span>';
			
			if ( $current_page = get_query_var( 'paged' ) ) {
				$current_page = $current_page;
			} elseif ( $current_page = get_query_var( 'page' ) ) {
				$current_page = $current_page;
			} else {
				$current_page = 1;
			}
			$format = 'page/%#%';
			// Define and add filter to pagination args
			$args = array(				
			'format'             => $format,
				'current'            => max( 1, $current_page ),
				'total'              => $object->max_num_pages,
				'mid_size'           => 3,
				'type'               => 'list',
				'prev_text'          => $prev_text,
				'next_text'          => $next_text,
				'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'total' ) . ' </span>',
			);
			$output .= '<div class="wpex-pagination wpex-clr left job-bottom" style="margin-top:30px;clear:both;display:inline-block;">' . paginate_links( $args ) . '</div>';
			}
	
	$output .= "<script type='text/javascript'>
	(function($) {
    var joblistwrapper = $('.jobs-list-wrapper');
    /*$(document).on('change', '.changer', function() {
		joblistwrapper.find('.job-box').hide();
        var area_class = $('select[name=area]').val();
       // var location_class = $('select[name=location]').val();

        if (area_class != '') {            
            joblistwrapper.find('.' + area_class).show();            
        } else {
            joblistwrapper.find('.job-box').show();
        }
    });*/
    $(window).bind('load', function() {
        var images = $('.job-box .imageWrapper img');
        images.each(function(index, element) {

            var original = $(this).attr('data-original');
            $(this).attr('src', original);
        });


    });

})(jQuery);
	</script>";
        return $output;
    }
    
} // End Element Class

// Element Class Init
new vcJobsList();
?>