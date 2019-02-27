<?php
class vcAddressMap extends WPBakeryShortCode
{
    
    // Element Init
    function __construct()
    {
        add_action('init', array(
            $this,
            'vc_address_map_mapping'
        ));
        add_shortcode('vc_address_map', array(
            $this,
            'vc_address_map_html'
        ));
    }
    
    // Element Mapping
    public function vc_address_map_mapping()
    {
        
        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }
     
        // Map the block with vc_map()
        vc_map(array(
            'name' => __('VC Address Map', 'text-domain'),
            'base' => 'vc_address_map',
            'description' => __('Display address and map image', 'text-domain'),
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
                    'heading' => __('Heading', 'text-domain'),
                    'param_name' => 'heading',
                    'value' => ''
                ),
				array(
                    'type' => 'textfield',
                    'heading' => __('Sub Heading', 'text-domain'),
                    'param_name' => 'subheading',
                    'value' => ''
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __('Address', 'text-domain'),
                    'param_name' => 'address',
                    'value' => ''
                ),
				array(
                    'type' => 'textfield',
                    'heading' => __('Phone Number', 'text-domain'),
                    'param_name' => 'phone',
                    'value' => ''
                ),
				array(
                    'type' => 'textfield',
                    'heading' => __('Email Address', 'text-domain'),
                    'param_name' => 'email_address',
                    'value' => ''
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Map Button Text', 'text-domain'),
                    'param_name' => 'map_button_text',
                    'value' => ''
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Map Button URL', 'text-domain'),
                    'param_name' => 'ma_button_url',
                    'value' => ''
                ),
				array(
					'type' => 'attach_image',	
					'heading' => __( 'Map Image', 'text-domain' ),
					'value'=>'',
					'param_name' => 'map_image',                    													
				),
            )
        ));
        
    }    

    // Element HTML
    public function vc_address_map_html($atts)
    {
		extract(shortcode_atts(array(
		"title"=>"",
		"heading"=>"",
		"subheading"=>"",		
		"address"=>"",		
		"phone"=>"",		
		"email_address"=>"",		
		"map_button_text"=>"",
		"ma_button_url"=>"",
		"map_image"=>""
		) , $atts));		

		$mapimage = wp_get_attachment_url($map_image);

        $placeholder = get_stylesheet_directory_uri().'/images/map-placeholder.jpg';
        
        $output = '';
        $output .= '<div id="address-map-wrapper" class="address-map-wrapper">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output .='<div class="addressOuterWrapper">';
					$output .='<h2 class="section-title">'.$title.'</h2>';
					$output .='<div class="headingWrapper">'.$heading.'</div>';
					$output .='<div class="subheadingWrapper">'.$subheading.'</div>';
					$output .='<div class="addressWrapper">'.$address.'</div>';
					$output .='<div class="phoneWrapper"><a href="tel:'.$phone.'"><i></i> '.$phone.'</a></div>';
					$output .='<div class="emailWrapper"><a href="mailto:'.$email_address.'"><i></i> '.$email_address.'</a></div>';
					$output .='<div class="mapURLWrapper"><a href="'.$ma_button_url.'" target="_blank">'.$map_button_text.'</a></div>';
					$output .= '</div>';
				$output .= '</div>';			
				$output .= '<div class="vc_column_container vc_col-sm-8">';
					$output .='<div class="imageWrapper">';
						$output .= '<img src="'.$placeholder.'" data-original="'.$mapimage.'" alt=""/>';
					$output .= '</div>';			
				$output .= '</div>';			
				
			$output .= '</div>';				
		$output .= '</div>';	
			
			$output .= "<script type='text/javascript'>
	
	
	(function($) {
		
    $(window).bind('load', function() {
		var images = $('.address-map-wrapper .imageWrapper img');
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
new vcAddressMap();
?>