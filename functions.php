<?php

// add code to the header (like analytics)
add_action('wp_head', 'inject_headers');
function inject_headers(){
    //Close PHP tags 
    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-196981146-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-196981146-1');
    </script>
    <?php //Open PHP tags
}

// add our JS script
wp_enqueue_script( 'uw-2014-child', get_stylesheet_directory_uri() . '/js/uw-2014-child.js', array( 'jquery' ), 1.0, false);

require_once(get_stylesheet_directory() . '/setup/class.hprc-page-attributes-meta-box.php' );

// set up a sidebar that is used only for the blog pages
function uw_2014_child_widgets_init() {

	register_sidebar( array(
		'name'          => 'Blog sidebar',
    'id'            => 'uw_child_blog_sidebar',
    'description'   => 'Right column widgets for blog pages',
		'before_widget' => '<div role="navigation" aria-label="sidebar_navigation" id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));

}
add_action( 'widgets_init', 'uw_2014_child_widgets_init' );

// The UW select messes up the order of the categories in the select input
// and basically causes it to not function. Easiest way to fix this is just
// prevent the UW select from being applied

// We can't grab the instance of the UW_Filters object to properly
// call remove_filter() so instead we just register another filter after theirs
// that undoes what they do
add_filter( 'widget_categories_dropdown_args', function( $args )
{
    $args['class' ] = '';
    return $args;
}, 11 );

// a copy of uw_breadcrumbs that omits the category link and links back to the posts page from a single post 
// (why doesn't the original have an option for this?) 

// also makes 'home' nav item the HPRC homepage
if ( ! function_exists('get_uw_breadcrumbs') ) :

  function get_uw_breadcrumbs()
  {

    global $post;
    $ancestors = array_reverse( get_post_ancestors( $post ) );
    //$html = '<li><a href="http://uw.edu" title="University of Washington">Home</a></li>';
    $html = '<li' . (is_front_page() ? ' class="current"' : '') . '><a href="' . home_url('/') . '" title="' . get_bloginfo('title') . '">' . get_bloginfo('title') . '</a><li>';

    if ( is_404() )
    {
        $html .=  '<li class="current"><span>Woof!</span>';
    } else

    if ( is_search() )
    {
        $html .=  '<li class="current"><span>Search results for ' . get_search_query() . '</span>';
    } else

    if ( is_author() )
    {
        $author = get_queried_object();
        $html .=  '<li class="current"><span> Author: '  . $author->display_name . '</span>';
    } else

    if ( get_queried_object_id() === (Int) get_option('page_for_posts')   ) {
        $html .=  '<li class="current"><span> '. get_the_title( get_queried_object_id() ) . ' </span>';
    }

    // If the current view is a post type other than page or attachment then the breadcrumbs will be taxonomies.
    if( is_category() || is_single() || is_post_type_archive() || is_tag())
    {

      if ( is_post_type_archive() )
      {
        $posttype = get_post_type_object( get_post_type() );
        //$html .=  '<li class="current"><a href="'  . get_post_type_archive_link( $posttype->query_var ) .'" title="'. $posttype->labels->menu_name .'">'. $posttype->labels->menu_name  . '</a>';
        $html .=  '<li class="current"><span>'. $posttype->labels->menu_name  . '</span>';
      }

      if ( is_category() )
      {
        $html .=  '<li><a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">'. get_the_title( (Int) get_option('page_for_posts') ) . '</a>';
        $category = get_category( get_query_var( 'cat' ) );
        //$html .=  '<li class="current"><a href="'  . get_category_link( $category->term_id ) .'" title="'. get_cat_name( $category->term_id ).'">'. get_cat_name($category->term_id ) . '</a>';
        $html .=  '<li class="current"><span>'. get_cat_name($category->term_id ) . '</span>';
      }

      if ( is_tag() )
      {
        $html .=  '<li><a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">'. get_the_title( (Int) get_option('page_for_posts') ) . '</a>';
        // better way to get tag?
        $tag = get_queried_object()->name;
        $html .=  '<li class="current"><span>'. $tag . '</span>';
      }

      if ( is_single() )
      {
        $html .=  '<li><a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">'. get_the_title( (Int) get_option('page_for_posts') ) . '</a>';
        if ( uw_is_custom_post_type() )
        {
          $posttype = get_post_type_object( get_post_type() );
          $archive_link = get_post_type_archive_link( $posttype->query_var );
          if (!empty($archive_link)) {
            $html .=  '<li><a href="'  . $archive_link .'" title="'. $posttype->labels->menu_name .'">'. $posttype->labels->menu_name  . '</a>';
          }
          else if (!empty($posttype->rewrite['slug'])){
            $html .=  '<li><a href="'  . site_url('/' . $posttype->rewrite['slug'] . '/') .'" title="'. $posttype->labels->menu_name .'">'. $posttype->labels->menu_name  . '</a>';
          }
        }
        $html .=  '<li class="current"><span>'. get_the_title( $post->ID ) . '</span>';
      }
    }

    // If the current view is a page then the breadcrumbs will be parent pages.
    else if ( is_page() )
    {

      if ( ! is_home() || ! is_front_page() )
        $ancestors[] = $post->ID;

      if ( ! is_front_page() )
      {
        foreach ( array_filter( $ancestors ) as $index=>$ancestor )
        {
          $class      = $index+1 == count($ancestors) ? ' class="current" ' : '';
          $page       = get_post( $ancestor );
          $url        = get_permalink( $page->ID );
          $title_attr = esc_attr( $page->post_title );
          if (!empty($class)){
            $html .= "<li $class><span>{$page->post_title}</span></li>";
          }
          else {
            $html .= "<li><a href=\"$url\" title=\"{$title_attr}\">{$page->post_title}</a></li>";
          }
        }
      }

    }

    return "<nav class='uw-breadcrumbs' aria-label='breadcrumbs'><ul>$html</ul></nav>";
  }

endif;

if ( ! function_exists('uw_breadcrumbs') ) :

  function uw_breadcrumbs()
  {
    echo get_uw_breadcrumbs();
  }

endif;
