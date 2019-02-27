<?php
class vcEmployeesList extends WPBakeryShortCode
{
    
    // Element Init
    function __construct()
    {
        add_action('init', array(
            $this,
            'vc_employees_list_mapping'
        ));
        add_shortcode('vc_employees_list', array(
            $this,
            'vc_employees_list_html'
        ));
    }
    
    // Element Mapping
    public function vc_employees_list_mapping()
    {
        
        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }
     $employeesList = $employees = array();
		$args   = array(
            'post_type' => 'emd_employee',
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            
        );
        $object = new WP_Query($args);
        if ($object->have_posts()):
			$employeesList = $object->get_posts();            
        endif;
         $employees = array_map(function($employee){ return array($employee->ID,$employee->post_title);},$employeesList);   
        // Map the block with vc_map()
        vc_map(array(
            'name' => __('VC Employees List', 'text-domain'),
            'base' => 'vc_employees_list',
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
                array(
                    'type' => 'param_group',
                    'value' => '',
                    'heading' => __('Employees List', 'pt-vc'),
                    'param_name' => 'empolyees_list',
                    'params' => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Display Type', 'text-domain'),
                            'value' => array(
                                "Image"=>"image",
                                "Video"=>"video"
                            ),
                            'param_name' => 'display_type'
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => __('Image', 'text-domain'),
                            'value' => '',
                            'param_name' => 'image'
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Video URL', 'text-domain'),
                            'param_name' => 'video_url',
                            'value' => ''
                        ),
						array(
                            'type' => 'attach_image',
                            'heading' => __('Video Image(Background)', 'text-domain'),
                            'value' => '',
                            'param_name' => 'video_image'
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Display Type', 'text-domain'),
                            'value' => array(
                                "Horizontal (Image/Video + Content)"=>"horizontal_image_content",
                                "Horizontal (Content + Image/Video)"=>"horizontal_content_image",
                                "Vertical"=>"vertical"
                            ),
                            'param_name' => 'layout_type'
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Title', 'text-domain'),
                            'param_name' => 'title',
                            'value' => ''
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __('Quote', 'text-domain'),
                            'param_name' => 'quote',
                            'value' => ''
                        ),
                        
                        
                        array(
                            'type' => 'dropdown',
                            
                            'heading' => __('Name', 'text-domain'),
                            'value' => $employees,
                            'param_name' => 'name'
                            
                        )
                    )
                )
            )
        ));
        
    }
    
    public function renderImageVideo($employee){
		$employeeObject = get_post($employee['name']);
		$employeeName = $employeeObject->post_title;
		$imageVideo = '';
		$arrow = '';
		$placeholder = get_stylesheet_directory_uri().'/images/placeholder-employee-list.jpg';
		if($employee['layout_type'] == 'horizontal_image_content'){
			$arrow = 'arrow-left';
		}elseif($employee['layout_type'] == 'horizontal_content_image'){
			$arrow = 'arrow-right';
		}elseif($employee['layout_type'] == 'vertical'){
			$arrow = 'arrow-up';
		}
		switch($employee['display_type']){
			case 'video':
			wpex_enqueue_ilightbox_skin();
				$employeeObject = get_post($employee['name']);
				$employeeName = $employeeObject->post_title;
				$image = wp_get_attachment_url($employee['video_image']);
				$imageVideo .= '<div class="imageWrapper wpex-lightbox">';
					$imageVideo .= '<img src="'.$placeholder.'" alt="'.$employeeName.'" data-original="'.$image.'"/>';
					$imageVideo .= '<i class="'.$arrow.'"></i>';
					$imageVideo .= '<a data-type="iframe" data-show_title="false" data-options="iframeType:\'video\'" href="'.$employee['video_url'].'" target="_self" ><img src="'.get_stylesheet_directory_uri().'/images/video-button.png" alt=""></a>';
				$imageVideo .= '</div>';		
				
			break;
			case 'image':				
				$image = wp_get_attachment_url($employee['image']);
				$imageVideo .= '<div class="imageWrapper">';
					$imageVideo .= '<img src="'.$placeholder.'" alt="'.$employeeName.'" data-original="'.$image.'"/>';
					$imageVideo .= '<i class="'.$arrow.'"></i>';
				$imageVideo .= '</div>';		
			break;
		}
		return $imageVideo;
	}
	public function renderContent($employee){
		$employeeObject = get_post($employee['name']);
		$employeeName = $employeeObject->post_title;
		$employeeTitle = $employee['title'];
		$jobtitles = wp_get_post_terms( $employeeObject->ID, 'jobtitles' )[0]->name;
		$content = '';
		$content .= '<div class="contentWrapper">';
		if($employee['layout_type'] == 'horizontal_image_content' || $employee['layout_type'] == 'horizontal_content_image'){
			$content .= '<div class="horizontalFixLine mt-0"></div>';
		}
		$content .= '<div class="titleWrapper"><h2>'.$employeeTitle.'</h2></div>';
		$content .= '<div class="quote">&ldquo;'.$employee['quote'].'&rdquo;</div>';
		$content .= '<div class="horizontalFixLine"></div>';
		$content .= '<h6 class="name">'.$employeeName.'</h6>';
		$content .= '<h6 class="designation">'.$jobtitles.'</h6>';
		$content .= '</div>';
		return $content;
	}
    // Element HTML
    public function vc_employees_list_html($atts)
    {
		extract(shortcode_atts(array(
		"title"=>"",
		"description"=>"",		
		"button_text"=>"",
		"button_url"=>"",
		"empolyees_list"=>""
		) , $atts));
		
        $employees_list = vc_param_group_parse_atts($empolyees_list);
		

        
        
        $output = '';
        $output .= '<div id="employee-list-wrapper" class="employee-list-wrapper">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';
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
			if($employees_list){
				$output .= '<div class="vc_row wpb_row vc_row-fluid">';		
				foreach($employees_list as $employee){
				$output .= '<div class="employee-box '.$employee['display_type'].' '.$employee['layout_type'].'">';														
				switch($employee['layout_type']){
					case 'horizontal_image_content':
							$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';		

								$output .= '<div class="vc_column_container vc_col-sm-6 custom-padding custom-margin-bttom">';									
									$output .= $this->renderImageVideo($employee);
								$output .= '</div>';
								$output .= '<div class="vc_column_container vc_col-sm-6 custom-padding custom-margin-bttom">';
									$output .= $this->renderContent($employee);
								$output .= '</div>';	
							$output .= '</div>';

						
					break;
					case 'horizontal_content_image':
						
							$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';		
								$output .= '<div class="vc_column_container vc_col-sm-6 custom-padding custom-margin-bttom">';
									$output .= $this->renderContent($employee);
								$output .= '</div>';	
								$output .= '<div class="vc_column_container vc_col-sm-6 custom-padding custom-margin-bttom">';									
									$output .= $this->renderImageVideo($employee);
								$output .= '</div>';
							$output .= '</div>';

					break;
					case 'vertical':
						
							
								$output .= '<div class="vc_column_container vc_col-sm-6 custom-padding custom-margin-bttom" data-mh="vertical">';
								
									$output .= $this->renderImageVideo($employee);	
									$output .= $this->renderContent($employee);
	
	
							$output .= '</div>';	
						
					break;
				}
				$output .= '</div>';
				}
				$output .= '</div>';
			}
			
			
		$output .= "<script type='text/javascript'>
	
	
	(function($) {
		$(window).resize(function() {
        
        $.fn.matchHeight._update();
        
    });
    $(window).bind('load', function() {
		var images = $('.employee-box .imageWrapper img');
		images.each(function(index, element) {
			
			var original = $(this).attr('data-original');
    		$(this).attr('src',original);
		});
		
       
    });
	
	})(jQuery);
	</script>";
        return $output;
    }
    
} // End Element Class

// Element Class Init
new vcEmployeesList();
?>