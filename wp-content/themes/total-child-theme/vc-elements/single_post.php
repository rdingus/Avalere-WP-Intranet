<?php 
class vsSinglePost extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_single_post_mapping' ) );
        add_shortcode( 'vc_single_post', array( $this, 'vc_single_post_html' ) );
    }
     
    // Element Mapping
    public function vc_single_post_mapping() {
		$postsList = $posts = array();
		$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            
        );
        $object = new WP_Query($args);
        if ($object->have_posts()):
			$postsList = $object->get_posts();            
        endif;
         $posts = array_map(function($post){ return array($post->ID,$post->post_title);},$postsList);
		
        // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
    }
         
    // Map the block with vc_map()
    vc_map( 
  
        array(
            'name' => __('VC Single Post', 'text-domain'),
            'base' => 'vc_single_post',
            'description' => __('Display Single Post', 'text-domain'), 
            'category' => __('My Custom Shortcodes', 'text-domain'),               
            'params' => array(   
                      array(
                            'type' => 'dropdown',
                            'heading' => __('Display Type', 'text-domain'),
                            'value' => array(
								"Select Display Type"=>"",    
                                "Image"=>"image",
                                "Video"=>"video"
                            ),
                            'param_name' => 'display_type'
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => __('Image', 'text-domain'),
                            'value' => '',
                            'param_name' => 'image',
							'description'=>'Image size : 640x480'
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Video ID', 'text-domain'),
                            'param_name' => 'video_url',
                            'value' => ''
                        ),
						array(
                            'type' => 'attach_image',
                            'heading' => __('Video Image(Background)', 'text-domain'),
                            'value' => '',
                            'param_name' => 'video_image',
							'description'=>'Image size : 640x480'
                        ),                
						array(
        	        	    'type' => 'dropdown',            	        
                	    	'heading' => __( 'Posts', 'text-domain' ),
							'value'=>$posts,
        		            'param_name' => 'posts',                                        
                		),  
						array(
        	        	    'type' => 'colorpicker',            	        
                	    	'heading' => __( 'Background Color', 'text-domain' ),
							'value'=>'#ffffff',
        		            'param_name' => 'background_color',                                        
                		),  
array(
                            'type' => 'textfield',
                            'heading' => __('Box Class', 'text-domain'),
                            'param_name' => 'box_class',
                            'value' => ''
                        ),
            )
        )
    );   
        
    }
     
     
    // Element HTML
    public function vc_single_post_html( $atts ) {
         extract(shortcode_atts(array(
		"display_type"=>"",
		"image"=>"",
		"video_url"=>"",
		"video_image"=>"",
		"posts"=>"",
		"background_color"=>"",
		"box_class"=>""			
		) , $atts));
		$postObject = get_post($posts);		
		$postTitle = $postObject->post_title;

		// prepend media type category to post titles
		if(has_category(5,$postObject->ID)){
            $catname = 'Podcast: ';
        }elseif(has_category(4,$postObject->ID)){
            $catname = 'Video: ';
        }elseif(has_category(6,$postObject->ID)){
            $catname = 'Webinar: ';
        }else{
            $catname = '';
        }

		//$postMedia = wp_get_post_terms( $postObject->ID, 'category' );	
		//$postMediaString = implode('<span>|</span>',array_map(function($cat){ return '<a href="'.get_term_link($cat).'">'.$cat->name.'</a>';},$postMedia));
		
		$postCategories = wp_get_post_terms( $postObject->ID, 'content-categories' );	
		$postCategoriesString = implode('<span> | </span>',array_filter(array_map(function($cat){ 	
		return $cat->parent == 0 ? '<a href="'.get_term_link($cat).'">'.$cat->name.'</a>': '';},$postCategories)));
		$postTags = array();
		$postTagsString = '';
		
		/*if($postMediaString){
			$postTags[] = $postMediaString;
		}*/
		if($postCategoriesString){
			$postTags[] = $postCategoriesString;
		}
		$postTagsString = implode(', ',$postTags);
		
		$postURL = get_permalink($postObject->ID);
		$imageVideo = '';
		$arrow = 'arrow-up';
		$placeholder = get_stylesheet_directory_uri().'/images/placeholder-640320.jpg';
        
        $output = '';
		$output .= '<div class="single_post-wrapper '.$box_class.'">';						
		$output .= '<div style="background-color:'.$background_color.'">';
		
		switch($display_type){
			case 'video':				
				if($video_url){
					
				/*$imageurl = wp_get_attachment_url($video_image);
				$output .= '<a href="'.$video_url.'" class="wpex-lightbox-video"><div class="imageWrapper">';
					$output .= '<img src="'.$placeholder.'" alt="'.$postTitle.'" data-original="'.$imageurl.'"/>';
					$output .= '<i class="'.$arrow.'"></i>';
					//$output .= '<a href="'.$video_url.'" class="fancybox"><img src="'.get_stylesheet_directory_uri().'/images/video-button.png" alt=""></a>';
					$output .= '<img src="'.get_stylesheet_directory_uri().'/images/video-button.png" class="button-play" alt="">';
				$output .= '</div></a>';*/
				$output .= '<div class="imageWrapper">';
				$output .= '<iframe src="https://player.vimeo.com/video/'.$video_url.'?autoplay=1&amp;loop=1&amp;autopause=0&amp;background=1" allowfullscreen="allowfullscreen"></iframe>';
				$output .= '<i class="'.$arrow.' video-up"></i>';
				$output .= '</div>';
				}
			break;
			case 'image':
if($image){
				$imageurl = wp_get_attachment_url($image);								
				
				$output .= '<a href="'.$postURL.'"><div class="imageWrapper">';
					$output .= '<img src="'.$placeholder.'" alt="'.$postTitle.'" data-original="'.$imageurl.'"/>';
					$output .= '<i class="'.$arrow.'"></i>';
				$output .= '</div></a>';		
}
			break;
		}
		$output .= '<div class="contentWrapper">';
				$output .= '<div class="categoriesWrapper">'.$postTagsString.'</div>';
				$output .= '<a href="'.$postURL.'"><h2>'.$catname.$postTitle.'</h2></a>';
				//$output .= '<a href="'.$postURL.'"><h2>'.$out = strlen($postTitle) > 130 ? substr($postTitle,0,65)."..." : $postTitle.'</h2></a>';
		$output .= '</div>';		
			
		$output .= '</div>';	
		$output .= '</div>';
		$output .= "<script type='text/javascript'>
	
	(function($) {	
    $(window).bind('load', function() {
		var images = $('.single_post-wrapper .imageWrapper img');
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
new vsSinglePost(); ?>