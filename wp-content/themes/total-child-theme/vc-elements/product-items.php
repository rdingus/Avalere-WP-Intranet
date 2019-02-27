<?php
class vcProductItems extends WPBakeryShortCode
{
    
    // Element Init
    function __construct()
    {
        add_action('init', array(
            $this,
            'vc_product_items_mapping'
        ));
        add_shortcode('vc_product_items', array(
            $this,
            'vc_product_items_html'
        ));
    }
    
    // Element Mapping
    public function vc_product_items_mapping()
    {
        
        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }
     $ProductItems = $rows = array();
		$args   = array(
            'post_type' => 'emd_employee',
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            
        );
        $object = new WP_Query($args);
        if ($object->have_posts()):
			$ProductItems = $object->get_posts();            
        endif;
         $rows = array_map(function($row){ return array($row->ID,$row->post_title);},$ProductItems);   
        // Map the block with vc_map()
        vc_map(array(
            'name' => __('VC Product Item Rows', 'text-domain'),
            'base' => 'vc_product_items',
            'description' => __('Display product items row', 'text-domain'),
            'category' => __('My Custom Shortcodes', 'text-domain'),
            'params' => array(               
                array(
                    'type' => 'param_group',
                    'value' => '',
                    'heading' => __('Rows', 'pt-vc'),
                    'param_name' => 'rows',
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
                            'heading' => __('Description', 'text-domain'),
                            'param_name' => 'description',
                            'value' => ''
                        ),                       
                        
                        
                    )
                )
            )
        ));
        
    }
    
    public function renderImageVideo($row,$ad){

		$rowName = $row['title'];
		$imageVideo = '';
		$arrow = '';
		$placeholder = get_stylesheet_directory_uri().'/images/placeholder-employee-list.jpg';
		if($row['layout_type'] == 'horizontal_image_content'){
			$arrow = 'arrow-left';
		}elseif($row['layout_type'] == 'horizontal_content_image'){
			$arrow = 'arrow-right';
		}elseif($row['layout_type'] == 'vertical'){
			$arrow = 'arrow-up';
		}
		switch($row['display_type']){
			case 'video':
				$rowObject = get_post($row['name']);
				$rowName = $rowObject->post_title;
				$image = wp_get_attachment_url($row['video_image']);
				//$imageVideo .= '<div class="imageWrapper wrap-'.$ad.'">';
				$imageVideo .= '<div class="imageWrapper">';
				if($image){
					$imageVideo .= '<img src="'.$placeholder.'" alt="'.$rowName.'" data-original="'.$image.'"/>';
				//	$imageVideo .= '<i class="'.$arrow.'"></i>';
					$imageVideo .= '<a href="'.$row['video_url'].'" class="fancybox"><img src="'.get_stylesheet_directory_uri().'/images/video-button.png" alt=""></a>';
				}else{
					$imageVideo .= '<img src="'.$placeholder.'" alt="'.$rowName.'" data-original="'.$placeholder.'"/>';
					//$imageVideo .= '<i class="'.$arrow.'"></i>';
				}
				$imageVideo .= '</div>';		
			break;
			case 'image':				
				$image = wp_get_attachment_url($row['image']);
				//$imageVideo .= '<div class="imageWrapper wrap-'.$ad.'">';
				$imageVideo .= '<div class="imageWrapper">';
					if($image){
						$imageVideo .= '<img src="'.$placeholder.'" alt="'.$rowName.'" data-original="'.$image.'"/>';
					//$imageVideo .= '<i class="'.$arrow.'"></i>';
					}else{
						$imageVideo .= '<img src="'.$placeholder.'" alt="'.$rowName.'" data-original="'.$placeholder.'"/>';
				//	$imageVideo .= '<i class="'.$arrow.'"></i>';
					}
					
				$imageVideo .= '</div>';		
			break;
		}
		return $imageVideo;
	}
	public function renderContent($row){		
		$rowTitle = $row['title'];
		$rowcontent = $row['description'];		
		$content = '';
		$content .= '<div class="contentWrapper">';		
		$content .= '<div class="titleWrapper"><h2>'.$rowTitle.'</h2></div>';		
		$content .= '<div class="horizontalFixLine"></div>';
		$content .= '<div class="desc">'.$rowcontent.'</div>';
		
		$content .= '</div>';
		return $content;
	}
    // Element HTML
    public function vc_product_items_html($atts)
    {
		extract(shortcode_atts(array(		
		"rows"=>""
		) , $atts));
		
        $rows = vc_param_group_parse_atts($rows);
        $output = '';
        $output .= '<div id="product-items-wrapper" class="product-items-wrapper">';		
				
			if($rows){
				//$output .= '<div class="">';		
				foreach($rows as $row){
					$output .= '<div class="product-box '.$row['display_type'].' '.$row['layout_type'].'">';														
						switch($row['layout_type']){
						case 'horizontal_image_content':
								$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';		
	
									$output .= '<div class="vc_column_container vc_col-sm-6  custom-margin-bttom pr-0">';									
										$output .= $this->renderImageVideo($row,'right');
									$output .= '</div>';
									$output .= '<div class="vc_column_container vc_col-sm-6  custom-margin-bttom pr-0">';
										$output .= $this->renderContent($row);
									$output .= '</div>';	
								$output .= '</div>';
	
							
						break;
						case 'horizontal_content_image':
							
								$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';		
									$output .= '<div class="vc_column_container vc_col-sm-6  custom-margin-bttom pl-0">';
										$output .= $this->renderContent($row);
									$output .= '</div>';	
									$output .= '<div class="vc_column_container vc_col-sm-6  custom-margin-bttom pl-0">';									
										$output .= $this->renderImageVideo($row,'left');
									$output .= '</div>';
								$output .= '</div>';
	
						break;
						case 'vertical':
							
								
									$output .= '<div class="vc_column_container vc_col-sm-6  custom-margin-bttom" data-mh="vertical">';
									
										$output .= $this->renderImageVideo($row);	
										$output .= $this->renderContent($row);
		
		
								$output .= '</div>';	
							
						break;
					}
					$output .= '</div>';
				}
				$output .= '</div>';
			}
			//$output .= '</div>';
			
		$output .= "<script type='text/javascript'>
	
	
	(function($) {
		$(window).resize(function() {
        
        $.fn.matchHeight._update();
        
    });
    $(window).bind('load', function() {
		var images = $('.product-box .imageWrapper img');
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
new vcProductItems();
?>