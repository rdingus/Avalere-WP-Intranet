<?php
/*
 * Theme Name: Avalere
 * Description: This is a custom theme to fit PDF requirements as requested.
 * Version: 0.0.1
*/
	//Standard Plan Template
	
	global $post;
	global $pdf_output;
	global $pdf_header;
	global $pdf_footer;

	global $pdf_template_pdfpage;
	global $pdf_template_pdfpage_page;
	global $pdf_template_pdfdoc;

	global $pdf_html_header;
	global $pdf_html_footer;

	global $pdf_margin_top;
	global $pdf_margin_bottom;

	//Set a pdf template. if both are set the pdfdoc is used. (You didn't need a pdf template)
	$pdf_template_pdfpage 		= ''; //The filename off the pdf file (you need this for a page template)
	$pdf_template_pdfpage_page 	= 1;  //The page off this page (you need this for a page template)

	$pdf_template_pdfdoc  		= ''; //The filename off the complete pdf document (you need only this for a document template)
	
	$pdf_html_header 			= true; //If this is ture you can write instead of the array a html string on the var $pdf_header
	$pdf_html_footer 			= true; //If this is ture you can write instead of the array a html string on the var $pdf_footer

	$pdf_margin_top 			= 30;
	$pdf_margin_bottom			= 20;
	//Set the Footer and the Header
	/*$pdf_header = array (
  		'odd' => 
  			array (
  				'L' =>
  					array(
  						'content' => '<div style="padding-bottom: 3em;"><img src="avalere-gray-logo.jpg" style="width: 75px;" alt="avalere-gray-logo" ></div>',
  					),
    			'R' => 
   					array (
						'content' => '{PAGENO}',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'helveticaneue,Arial,sans-serif',
    				),
    				'line' => 1,
  				),
  		'even' => 
  			array (
  				'L' =>
  					array(
  						'content' => '<img src="avalere-gray-logo.jpg" style="width: 75px; margn-top: 3mm;" alt="avalere-gray-logo" >',
  					),
    			'R' => 
    				array (
						'content' => '{PAGENO}',
						'font-size' => 8,
						'font-style' => 'B',
						'font-family' => 'helveticaneue,Arial,sans-serif',
    				),
    				'line' => 1,
  			),
	);*/
	// $pdf_footer = array (
	//   	'odd' => 
	//  	 	array (
	//  	 		'L' => 
	//     			array (
	//       				'content' => '<b>Avalere Health</b> | An Inovalon Company | © {DATE Y}. Avalere Health LLC. All Rights Reserved.',
	//       				'font-size' => 8,
	//       				/*'font-style' => 'BI',*/
	//       				'font-family' => 'helveticaneue,Arial,sans-serif',
	//     			),
	//     		'R' => 
	//     			array (
	// 					'content' => '<a href="'.get_home_url().'">www.avalere.com</a>',
	// 				    'font-size' => 8,
	// 				    /*'font-style' => 'BI',*/
	// 				    'font-family' => 'helveticaneue,Arial,sans-serif',
	//     			),
	//     		/*'C' => 
	//     			array (
	//       				'content' => ' ',
	//       				'font-size' => 8,
	//       				'font-style' => '',
	//       				'font-family' => '',
	//     			),*/
	    		
	//     		'line' => 1,
	//   		),
	//   	'even' => 
	// 		array (
	// 			'L' => 
	//     			array (
	//       				'content' => '<b>Avalere Health</b> | An Inovalon Company | © {DATE Y}. Avalere Health LLC. All Rights Reserved.',
	//       				'font-size' => 8,
	//       				/*'font-style' => 'BI',*/
	//       				'font-family' => 'helveticaneue,Arial,sans-serif',
	//     			),
	//     		'R' => 
	//     			array (
	// 					'content' => '<a href="'.get_home_url().'">www.avalere.com</a>',
	// 				    'font-size' => 8,
	// 				    /*'font-style' => 'BI',*/
	// 				    'font-family' => 'helveticaneue,Arial,sans-serif',
	//     			),
	//     		/*'C' => 
	//     			array (
	//       				'content' => ' ',
	//       				'font-size' => 8,
	//       				'font-style' => '',
	//       				'font-family' => 'helveticaneue,Arial,sans-serif',
	//     			),*/	    		
	//     		'line' => 1,
	//   		),
	// );

	$pdf_header = '<table width="100%" div style="border-bottom: 1px solid #000000; padding-bottom: 1em;">
		<tr>
		<td width: "90%"><img src="avalere-gray-logo.jpg" style="width: 80px;" alt="avalere-gray-logo" >
		</td>
		<td width: "10%" style="text-align: right;"><span style="font-family:helveticaneue,Arial,sans-serif;font-size:12px; text-align:right; padding-top:1em;">{PAGENO}</span>
		</td>
		</tr>
	</table>';
	
	$pdf_footer = '<table width="100%" style="border-top: 1px solid #000000; padding-top: 1em;">
	    <tr>
			<td width="80%"><span style="font-family:helveticaneue,Arial,sans-serif;font-size:12px;"><b>Avalere Health</b> | An Inovalon Company | © {DATE Y}. Avalere Health LLC. All Rights Reserved.</span></td>
			<td width="20%" style="text-align: right;"><a href="'.get_home_url().'"><span style="font-family:helveticaneue,Arial,sans-serif;font-size:12px;color: #028cca;">www.avalere.com</span></a></td>
	    </tr>
	</table>';
	$post_id = $post->ID;
	$classes = 'meta clr';
	if ('custom_text' == wpex_get_mod('blog_single_header', 'custom_text')) {
	    $classes .= ' meta-with-title';
	}
	$sections = wpex_blog_single_meta_sections();
	$pdf_output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xml:lang="en">
		
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>' . get_bloginfo() . '</title>
		</head>
		<body xml:lang="en" style="font-family: "helveticaneue",Arial,sans-serif;">
			<bookmark content="'.htmlspecialchars(get_bloginfo('name'), ENT_QUOTES).'" level="0" /><tocentry content="'.htmlspecialchars(get_bloginfo('name'), ENT_QUOTES).'" level="0" />			
			<div id="content" class="widecolumn">';
			if(have_posts()) :
				if(is_search()) $pdf_output .=  '<div class="post"><h2 class="pagetitle">Search Results</h2></div>';
			if(is_archive()) {
				global $wp_query;
				if(is_category()) {
					$pdf_output .= '<div class="post"><h2 class="pagetitle">Archive for the "' . single_cat_title('', false) . '" category</h2></div>';
				} elseif(is_year()) {
					$pdf_output .= '<div class="post"><h2 class="pagetitle">Archive for ' . get_the_time('Y') . '</h2></div>';
				} elseif(is_month()) {
					$pdf_output .= '<div class="post"><h2 class="pagetitle">Archive for ' . get_the_time('F, Y') . '</h2></div>';
				} elseif(is_day()) {
					$pdf_output .= '<div class="post"><h2 class="pagetitle">Archive for ' . get_the_time('F jS, Y') . '</h2></div>';
				} elseif(is_search()) {
					$pdf_output .= '<div class="post"><h2 class="pagetitle">Search Results</h2></div>';
				} elseif (is_author()) {
					$pdf_output .= '<div class="post"><h2 class="pagetitle">Author Archive</h2></div>';
				}
			}
			
			while (have_posts()) : the_post();
			
				$cat_links = "";
				foreach((get_the_category()) as $cat) {
					$cat_links .= '<a href="' . get_category_link($cat->term_id) . '" title="' . $cat->category_description . '">' . $cat->cat_name . '</a>, ';
				}
				$cat_links = substr($cat_links, 0, -2);

				// Create comments link
				if($post->comment_count == 0) {
					$comment_link = 'No Comments &#187;';
				} elseif($post->comment_count == 1) {
					$comment_link = 'One Comment &#187;';
				} else {
					$comment_link = $post->comment_count . ' Comments &#187;';
				}
				
				$pdf_output .= '<div class="post-header" style="border-bottom: 1px solid #000000;">';
				    $pdf_output .= '<div class="title_top">
				        <div class="post_meta left_section">
				            <table style="width: 100%;"><tr>';
				                foreach ($sections as $key => $val) :
				                    if ('date' == $val) :       
				                        $pdf_output .= '<td class="meta-date" style="width: 100px; text-align: left; border-right: 1px solid #a0acb6;">
				                            <time class="updated" datetime="'.the_date('Y-m-d').'"'.wpex_schema_markup("publish_date").'>'.get_the_date().'</time>
				                        </td>';				                    
				                    //elseif ('categories' == $val && !has_category('press-releases')) : 
				                    elseif ('categories' == $val) :     
				                        $pdf_output .= '<td class="meta-category" style="width: 120px; text-align: center; border-right: 1px solid #a0acb6;">';
		                        	    $cats = wp_get_object_terms($post_id, 'category');
		                        	    foreach ($cats as $ct ) {
		                        	    	$pdf_output .= '<a href="'.get_term_link($ct->slug, 'category').'" title="'.$ct->name.'" style="color: #028cca;">'.$ct->name.'</a>';
		                        	    }
				                        $pdf_output .= '</td>';
				                        $umbrellaCat = wp_get_object_terms($post_id, 'content-categories');
				                        if (!empty($umbrellaCat)) {     
				                            $pdf_output .= '<td style="width: 40%; padding-left: 25px; text-align: left;" class="meta-category">';
				                                $cat_str = '';
				                                foreach ($umbrellaCat as $pterm) {
				                                    if ($pterm->parent != 0)
				                                        continue;
				                                    $cat_str .= '<a href="' . get_term_link($pterm->slug, 'content-categories') . '" title="' . $pterm->name . '" style="color: #028cca;">' . $pterm->name . '</a>, ';
				                                }
				                                foreach ($umbrellaCat as $cterm) {
				                                    if ($cterm->parent == 0)
				                                        continue;
				                                    $cat_str .= '<a href="' . get_term_link($cterm->slug, 'content-categories') . '" title="' . $cterm->name . '" style="color: #028cca;">' . $cterm->name . '</a>, ';
				                                }
				                                $str = (substr($cat_str, -2) == ', ') ? substr($cat_str, 0, -2) : $cat_str;
				                                $pdf_output .= $str;
				                            $pdf_output .= '</td>';
				                        }
				                    endif;
				                endforeach;				                
				            $pdf_output .= '</tr></table>
				        </div>
			        </div>';
			        $pdf_output .= '<bookmark content="'.the_title('','', false).'" level="1" /><tocentry content="'.the_title('','', false).'" level="1" />';
					$pdf_output .= '<div style="text-align: left;"><a href="' . get_permalink() . '" rel="bookmark" title="Permanent Link to ' . the_title('','', false) . '">
					<h1 style="text-align: left;font-size: 36px;color: #333333 !important;font-weight: bold !important; line-height: 40px;margin: -10px 0 20px;letter-spacing: -0.01em;">' . the_title('','', false) . '</h1></a></div>';
			        /*$pdf_output .= '<header class="single-blog-header clr" style="float: left;width: 100%;margin-top: -10px;">';
				        $pdf_output .= '<h1 class="single-post-title entry-title"'.wpex_schema_markup('headline').'>';
				            $pdf_output .= the_title();
				        $pdf_output .= '</h1>';*/
				        $employeeIdArr = get_post_meta($post_id, $key = 'post_authors');
				        if ((is_singular('post')) && (isset($employeeIdArr[0]) && !empty($employeeIdArr[0]))):
				            
				            $pdf_output .= '<div style="width: 100%; padding-bottom: 1em;">';
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
				                        $pdf_output .= '<div style="width: 33%; float: left;">
			                            	<div style="background-image: url('.$img_url.'); background-position: center;
			                            		background-size: 270px 180px;
			                            		object-fit: cover; background-repeat: no-repeat;
			                            		border-radius: 50% / 50%; background-clip: border-box; padding: 15px; width: 33px; height:33px; float: left;"></div>
			                            	<div style="float: left; text-align: left; padding-left: 8px;"><a href="'.$employee_url.'">
			                                	<span style="color: #3fa9f5;font-weight: 600;float: left;"> '.$employeeData->post_title.'</span></a>
			                                </div>
			                            </div>';				                        
				                    }
				                }				                
				            $pdf_output .= '</div>';				            
				        endif;	

				    $pdf_output .= '</header>';
				$pdf_output .= '</div>';
				$pdf_output .= '<div style="text-align: left;" class = "vc_row wpb_row vc_row-fluid">
				    <div class = "wpb_column vc_column_container vc_col-sm-12">
				        <div class = "vc_column-inner">
				            <div class = "wpb_wrapper post-container">
				                <div class = "post_summary">
				                    <h2>Summary</h2>';
				                    $pdf_output .= get_field("post_summary", $post_id);
				                $pdf_output .= '</div>';
				                $extraClass = '';
				                if (has_category('videos')) {
				                    $extraClass = 'video-devider';
				                }
				            $pdf_output .= '</div>
				        </div>
				    </div>
				</div>';
				
				$pdf_output .= '<div style="text-align: left;" class="single-blog-content entry clr">' .	wpautop($post->post_content, true) . '</div>';				
				
				if(!is_page() && !is_single()) $pdf_output .= '<p class="postmetadata">Posted in ' . $cat_links . ' | ' . '<a href="' . get_permalink() . '#comment">' . $comment_link . '</a></p>';
			endwhile;
		else :
			$pdf_output .= '<h2 class="center">Not Found</h2>
				<p class="center">Sorry, but you are looking for something that isn\'t here.</p>';
		endif;
		$pdf_output .= '</div> <!--content-->';		
	$pdf_output .= '
		</body>
		</html>';