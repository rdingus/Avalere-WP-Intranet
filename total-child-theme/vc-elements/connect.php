<?php
class vcConnect extends WPBakeryShortCode
{
    
    // Element Init
    function __construct()
    {
        add_action('init', array(
            $this,
            'vc_connect_mapping'
        ));
        add_shortcode('vc_connect', array(
            $this,
            'vc_connect_html'
        ));
    }
    
    // Element Mapping
    public function vc_connect_mapping()
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
            'name' => __('VC Connect', 'text-domain'),
            'base' => 'vc_connect',
            'description' => __('Display connect list', 'text-domain'),
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
                            'heading' => __('Name', 'text-domain'),
                            'value' => $employees,
                            'param_name' => 'name'
                        )
                    )
                )
            )
        ));
        
    }
    
    
    // Element HTML
    public function vc_connect_html($atts)
    {
		extract(shortcode_atts(array(
		"title"=>"",
		"description"=>"",		
		"button_text"=>"",
		"button_url"=>"",
		"empolyees_list"=>""
		) , $atts));
		
        $empolyees_list = vc_param_group_parse_atts($empolyees_list);
		

        
        
        $output = '';
        $output .= '<div id="connect-wrapper" class="connect-wrapper">';
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
			if($empolyees_list){
				$placeholder = get_stylesheet_directory_uri().'/images/job-placeholder.jpg';
				$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">';
				foreach($empolyees_list as $employee){
					$employeeObject = get_post($employee['name']);
					$employeeName = $employeeObject->post_title;
					$image = wp_get_attachment_url(get_post_meta($employeeObject->ID,'emd_employee_photo',true));
					$departments = wp_get_post_terms( $employeeObject->ID, 'departments' )[0]->name;
					$output .= '<div class="vc_column_container vc_col-sm-3 custom-padding">';
						$output .= '<div class="employee-box">';
							$output .='<div class="imageWrapper">';
								$output .= '<img src="'.$placeholder.'" data-original="'.$image.'" alt=""/>';
								$output .= '<i class="arrow-up"></i>';								
							$output .= '</div>';
							$output .='<div class="contentWrapper">';		
								$output .= '<h6 class="name">'.$employeeName.'</h6>';						
								$output .= '<h6 class="department">'.$departments.'</h6>';						
							$output .= '</div>';	
						$output .= '</div>';	
					$output .= '</div>';
				}
				$output .= '</div>';
			}
			$output .= "<script type='text/javascript'>
	
	
	(function($) {
		
    $(window).bind('load', function() {
		var images = $('.connect-wrapper .imageWrapper img');
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
new vcConnect();
?>