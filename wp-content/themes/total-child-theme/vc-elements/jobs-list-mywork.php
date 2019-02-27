<?php
class vcJobsList extends WPBakeryShortCode {     
    // Element Init
    function __construct() {
    	$this->careerQuery = $this->careerQuery();
        add_action('init', array(
            $this,
            'vc_jobs_list_mapping'
        ));
        add_shortcode('vc_jobs_list', array(
            $this,
            'vc_jobs_list_html'
        ));
        add_action('wp_enqueue_scripts', array(
            $this,
            'career_assets'
        ));
        add_action('wp_ajax_careerloadmore', array(
            $this,
            'career_loadmore_ajax_handler'
        )); 
        add_action('wp_ajax_nopriv_careerloadmore', array(
            $this,
            'career_loadmore_ajax_handler'
        ));
    }

    public function careerQuery() {		 		
        $args   = array(
        	'post_type' => 'career',
            'order' => 'DESC',
			'meta_key' => 'last_date',
            'orderby' => 'meta_value_num',
            // 'posts_per_page' => $jobs_per_page,
            'posts_per_page' => 6,
            'post_status' => 'publish',			
        );
        $object = new WP_Query($args);
		
		return $object;
    }
    
    public function career_assets() {
        wp_register_script('lazy-js', get_stylesheet_directory_uri() . '/js/jquery.lazy.min.js', array(
            'jquery'
        ), '', true);
        wp_enqueue_script('lazy-js');
        
		if(is_page(9505)){
        
	        wp_register_script('career-ajax', get_stylesheet_directory_uri() . '/js/career-ajax.js', array(
	            'jquery'
	        ), '', true);
        
	        wp_localize_script('career-ajax', 'career_loadmore_params', array(
	            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
	            'posts' => json_encode($this->careerQuery->query), // everything about your loop is here            
	            'max_page' => $this->careerQuery->max_num_pages
	        ));       
        
        	wp_enqueue_script('career-ajax');
		}
    }

    public function career_loadmore_ajax_handler() {
        
        // prepare our arguments for the query
        $args          = json_decode(stripslashes($_POST['query']), true);
		$args['paged'] = $_POST['page'] + 1;

        if(isset($_POST['area']) && $_POST['area'] !=""){	
			if($_POST['area'] != 'all'){
				$args['meta_query'][] = array('key'=>'area','value'=>$_POST['area']);
			}
		}
		if(isset($_POST['location']) && $_POST['location'] !=""){			
			$args['meta_query'][] = array('key'=>'location','value'=>$_POST['location'],'compare'=>'LIKE');
		}       
        
        $object = new WP_Query($args);

        $members = array();
		$result = array('total'=>0,'items'=>'');
        if ($object->have_posts()):
            $members = $object->get_posts();			
			$result['total'] = $object->found_posts;
			$result['pages'] = $object->max_num_pages;
		 	$result['items'] =  $this->renderCareerBoxes($members,$args['paged']);
        endif;
        echo json_encode($result);
        die;
    }


    public function renderCareerBoxes( $jobsList = NULL, $paged = 0 ) {
        $output = '';        
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
		} else {
			$output = '';
		}
        return $output;
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
            'posts_per_page' => -1,
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
                    'type' => 'textfield',
                    'heading' => __('Jobs Per Page', 'text-domain'),
                    'param_name' => 'jobs_per_page',
                    'value' => '6'
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
		"jobs_per_page"=>"",	
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
            // 'posts_per_page' => $jobs_per_page,
            'posts_per_page' => 6,
            'post_status' => 'publish',
            
        );
        // print_r($args);
        $object = new WP_Query($args);
        if ($object->have_posts()):
			$jobsList = $object->get_posts();            
        endif;

        $loader = get_stylesheet_directory_uri().'/images/ajax-loader.gif';
		$loader2 = get_stylesheet_directory_uri().'/images/loadingDots.gif';
        
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
			$output .= '<div id="category-archive-searchform" class="vc_row wpb_row vc_row-fluid filter">';
				$output .= '<div class="vc_column_container vc_col-sm-12" style="padding-left:15px;padding-right:15px;">';
					$output .= '<label><select name="area" id="area" class="changer">';
						$output .= '<option value="" >Jobs by Area</option>';
						foreach($arealist as $area){
							$output .= '<option value="'.$area->slug.'">'.$area->name.'</option>';
						}
					$output .= '</select></label>';
					$output .= '<label id="location"><select id="location" name="location" class="changer">';
						$output .= '<option value="">Jobs by Location</option>';
						foreach($locationlist as $location){
							$output .= '<option value="'.$location->slug.'">'.$location->name.'</option>';
						}
					$output .= '</select></label>';
				$output .= '</div>';	
			$output .= '</div>';

			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output.='<div style="height:30px;"></div>';
				$output .= '</div>';	
			$output .= '</div>';

			$output .= '<div class="vc_column_container vc_col-sm-12 ">';
				$output .= '<div class="teamMembersWrapper">';
					$output .= '<div class="teamOverlay"><img src="'.$loader2.'"/></div>';
        			$output .= '<div id="team-member-list" data-pagination="1" data-pages="'.$this->careerQuery->max_num_pages.'" data-total="'.$this->careerQuery->found_posts.'">';
					        $output .= $this->renderCareerBoxes($this->careerQuery->get_posts());
				    $output .= '</div>';
				$output .= '</div>';
					
			    $output .= '<div id="team-member-scroller" class="vc_row wpb_row vc_row-fluid textcenter m-0">';
				    $output .= '<div class="vc_column_container vc_col-sm-12 custom-padding">';
					    $output .= '<div class="team-member-loader vcex-button theme-button clean black inline animate-on-hover"><img src="'.$loader.'"/>Please wait ...</div>';
				    $output .= '</div>';
			    $output .= '</div>';
	        $output .= '</div>';			
			
		$output .= "<script type='text/javascript'>
			(function($) {
			    var joblistwrapper = $('.jobs-list-wrapper');
			    /*$(document).on('change', '.changer', function() {
					joblistwrapper.find('.job-box').hide();
			        var area_class = $('select[name=area]').val();
			        var location_class = $('select[name=location]').val();
			        if (area_class != '') {            
			            joblistwrapper.find('.' + area_class).show();            
			        } else if(location_class != ''){
						joblistwrapper.find('.' + location_class).show();
					}else {
			            joblistwrapper.find('.job-box').show();
			        }
			    });
			    $(window).bind('load', function() {
			        var images = $('.job-box .imageWrapper img');
			        images.each(function(index, element) {

			            var original = $(this).attr('data-original');
			            $(this).attr('src', original);
			        });
			    });*/
			})(jQuery);
		</script>";
        return $output;
    }
    
} // End Element Class

// Element Class Init
new vcJobsList();
?>