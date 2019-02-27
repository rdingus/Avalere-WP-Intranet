<?php
get_header();
$categoryId = get_queried_object()->term_id;
$featuredContentArgs = array(
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post_status' => 'publish',
			'tax_query'=>array(array("taxonomy"=>"category","field"=>"id","terms"=>array($categoryId))),
			'meta_key'=>'evergreen_content',
			'meta_query'=>array("relation"=>"OR",array("key"=>"featured_post","value"=>"Yes","compare"=>"="),array("key"=>"evergreen_content","value"=>"Yes","compare"=>"=")),
			'orderby'=>array('meta_value'=>'DESC','date'=>'DESC')
        );

$featuredPostObject = new WP_Query($featuredContentArgs);
//$featuredPosts = $featuredPostObject->get_posts();
$categoryPosts = new CategoryPosts($categoryId);
?>

<div id="content-wrap" class="container clr">
  <div id="primary" class="content-area clr">
    <div id="content" class="site-content" style="background-color:#fff;">
      <!--<div id="category_landing_title">
        <h1 class="vcex-module vcex-heading vcex-heading-plain"><?php //echo single_cat_title();?></h1>
        <div class="category_desc"><?php //echo category_description();?></div>
      </div>-->
      <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" id="category-header-box">
      <div id="category_landing_title">
      <div class="vc_column_container vc_col-sm-6 vc_col-md-5 vc_col-lg-4">
      <h1 class="jump-header"><?php echo single_cat_title();?></h1>
      </div>
      <div class="vc_column_container vc_col-sm-6 vc_col-md-4 vc_col-md-offset-1">
      <div class="header-description">
	  <?php echo category_description();?>
      </div>
      </div>
      </div></div>

      <?php if($featuredPostObject->have_posts()){
		  $wpex_columns = apply_filters( 'wpex_related_blog_posts_columns', wpex_get_mod( 'blog_related_columns', '3' ) );
		  ?>
      <div class="featuredPostWrapper related-posts">
        <div class="vc_row wpb_row vc_row-fluid m-0">
          <div class="vc_column_container vc_col-sm-12">
          	<div class="vc_column-inner" style="padding-left:0px;">
            <h2 class="section-title">Featured Content</h2>
            </div>
          </div>
        </div>

                <?php $wpex_count = 0; ?>
			<?php foreach( $featuredPostObject->get_posts() as $post ) : setup_postdata( $post );?>
				<?php $wpex_count++; ?>
				<?php include( locate_template( 'partials/blog/blog-single-related-entry.php' ) ); ?>
				<?php if ( $wpex_columns == $wpex_count ) $wpex_count=0; ?>
			<?php endforeach;
			wp_reset_postdata();?>
      </div>
      <div class="vc_row wpb_row vc_row-fluid m-0">
          <div class="vc_column_container vc_col-sm-12">

            <div style="height:30px;"></div>

          </div>
        </div>
        <?php }?>
      <?php $categoryPosts->renderSearchForm();?>
      <?php $categoryPosts->renderPosts();?>
    </div>
    <!-- #content -->

  </div>
  <!-- #primary -->

</div>
<?php
get_footer();
?>
<?php
class CategoryPosts{
	private $categoryPostsQuery = NULL;
	private $categoryId = NULL;
	function __construct($categoryId){
		$this->categoryId = $categoryId;
		$this->categoryPostsQuery = $this->categoryPostsQuery();
	}


	function categoryPostsQuery(){
		 $args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',
			'cat'=>$this->categoryId,
            'posts_per_page' =>20,
            'post_status' => 'publish'
        );
		if ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} elseif ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} else {
				$paged = 1;
			}
			$args['paged'] = $paged;
			if(isset($_GET['search']) && $_GET['search'] != ''){
				$args['s'] = $_GET['search'];
			}
        $object = new WP_Query($args);

		return $object;
	}
	function renderPosts(){
		$loader = get_stylesheet_directory_uri().'/images/ajax-loader.gif';
		$loader2 = get_stylesheet_directory_uri().'/images/loadingDots.gif';
		//echo '<pre>';print_r($this->categoryPostsQuery);
		if($this->categoryPostsQuery->have_posts()):
			//echo '<div id="blog-entries" class="entries clr left-thumbs" data-pagination="1" data-pages="'.$this->categoryPostsQuery->max_num_pages.'" data-total="'.$this->categoryPostsQuery->found_posts.'">';
			echo '<div id="blog-entries" class="entries clr left-thumbs">';
			//echo '<div class="teamOverlay"><img src="'.$loader2.'"/></div>';
			echo '<div class="categorypostswrapper">';
			$wpex_count = 0;
			while ( $this->categoryPostsQuery->have_posts() ) : $this->categoryPostsQuery->the_post();
			wpex_get_template_part( 'blog_entry' );
			endwhile;
			echo '</div></div>';


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
				'total'              => $this->categoryPostsQuery->max_num_pages,
				'mid_size'           => 3,
				'type'               => 'list',
				'prev_text'          => $prev_text,
				'next_text'          => $next_text,
				'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'total' ) . ' </span>',
			);


			echo '<div class="wpex-pagination wpex-clr left">' . paginate_links( $args ) . '</div>';

		endif;
	}
	function renderSearchForm(){
		$terms = get_terms( array('taxonomy' => 'content-categories','parent'=>0,'hide_empty' => false) );
		echo '<div class="vc_row wpb_row vc_row-fluid category-searchform">
        <div class="wpb_column vc_column_container vc_col-sm-12">
          <div class="vc_column-inner vc_custom_1497499115628">
            <div class="wpb_wrapper">
              <div id="category-archive-searchform">

                  <ul>
                    <li class="sf-field-taxonomy-content-categories" data-sf-field-name="_sft_content-categories" data-sf-field-type="taxonomy" data-sf-field-input-type="select">
                      <label>
                        <select id="category-topic" name="topic" class="sf-input-select" title="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                          <option value="">Sort by Topic</option>';
                          foreach($terms as $term){
							  $psel = '';
							  $csel = '';
							 if(isset($_GET['topic']) && $_GET['topic'] == $term->slug){
								 $psel = 'selected="selected"';
							 }
							  $cterms = get_terms( array('taxonomy' => 'content-categories','parent'=>$term->term_id,'hide_empty' => false) );

                          echo '<option value="'.get_term_link($term).'" '.$psel.'>'.$term->name.'</option>';
                          foreach($cterms as $cterm){
							  if(isset($_GET['topic']) && $_GET['topic'] == $cterm->slug){
								 $csel = 'selected="selected"';
							 }
                          echo '<option value="'.get_term_link($cterm).'" '.$csel.'>&nbsp;&nbsp;&nbsp;'.$cterm->name.'</option>';
                           }
                           }
                        echo '</select>
                      </label>
                    </li>
                    <li class="sf-field-search" data-sf-field-name="search" data-sf-field-type="search" data-sf-field-input-type="">
					<form id="filterform" onsubmit="submit();" method="get" action="'.get_term_link($this->categoryId).'">
                      <label>
                        <input placeholder="Search …" id="search" class="sf-input-text" type="text" value="'.$_GET['search'].'" title="" name="search">
                      </label>
	                  </form>
                    </li>
                  </ul>
              </div>
            </div>
          </div>
        </div>
      </div>';
	}
}

?>
