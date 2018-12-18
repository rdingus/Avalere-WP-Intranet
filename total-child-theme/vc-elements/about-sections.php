<?php 
class vcAboutUs extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_about_us_mapping' ) );
        add_shortcode( 'vc_about_us', array( $this, 'vc_about_us_html' ) );
    }
     
    // Element Mapping
    public function vc_about_us_mapping() {		
		
        // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
    }
         
    // Map the block with vc_map()
    vc_map( 
  
        array(
            'name' => __('VC About Us', 'text-domain'),
            'base' => 'vc_about_us',
            'description' => __('Display about us sections', 'text-domain'), 
            'category' => __('My Custom Shortcodes', 'text-domain'),               
			'params' => array(
							array(
								'type' => 'param_group',
								'value' => '',
								'heading' =>  __( 'Sections', 'pt-vc' ),
								'param_name' => 'sections',	
								'params' => array(
												array(
													'type' => 'textfield',
													'value' => '',
													'heading' => __( 'Title', 'pt-vc' ),
													'param_name' => 'title',
												),
												array(
													'type' => 'textarea',
													'value' => '',
													'heading' => __( 'Description Left', 'pt-vc' ),
													'param_name' => 'description_left',
												),
												array(
													'type' => 'textarea',
													'value' => '',
													'heading' => __( 'Description Right', 'pt-vc' ),
													'param_name' => 'description_right',
												),
												array(
													'type' => 'attach_image',
													'heading' => __( 'Icon', 'text-domain' ),
													'value'=>'',
													'param_name' => 'icon',                    													
												),
												array(
													'type' => 'attach_image',
													'heading' => __( 'Image', 'text-domain' ),
													'value'=>'',
													'param_name' => 'image',                    													
												),
								)
						)
			)
)

    );
        
    } 
     
     
    // Element HTML
    public function vc_about_us_html( $atts ) {
         $sections  = vc_param_group_parse_atts($atts["sections"]);
		


		$output = '';
      
         
		$output .= '<div id="about-wrapper" class="about-wrapper">';
		foreach($sections as $section){
			$image = wp_get_attachment_url($section['image']);
			$icon = wp_get_attachment_url($section['icon']);
			$title = $section['title'];
			$description_left = $section['description_left'];
			$description_right = $section['description_right'];
			$output .= '<div class="vc_row wpb_row vc_row-fluid section" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output .= '<div class="sectionImageWrapper">';
						$output .= '<img src="'.get_stylesheet_directory_uri().'/images/about-placeholder.jpg" alt="" width="100%" data-original="'.$image.'">';
						$output .= '<h2>'.$title.'</h2>';
						$output .= '<i class="arrow-down"></i>';
					$output .= '</div>';
					$output .= '<div class="sectionContentWrapper">';
						$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex vc_row-o-content-middle" style="margin-left:0px;margin-right:0px;">';
				
							$output .= '<div class="vc_column_container vc_col-sm-6">';
								$output .= '<div class="iconWrapper">';
									$output .= '<img src="'.$icon.'" alt=""/>';
								$output .= '</div>';
								$output .= '<div class="description left">';
									$output .= $description_left;
								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="vc_column_container vc_col-sm-6">';
								$output .= '<div class="description right">';
									$output .= $description_right;
								$output .= '</div>';
							$output .= '</div>';							
						$output .= '</div>';						
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
			
		}
			
		$output .= '</div>';
		$output .= "<script type='text/javascript'>
	
	
	(function($) {
    $(window).bind('load', function() {
		var images = $('.about-wrapper .sectionImageWrapper img');
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
new vcAboutUs(); ?>