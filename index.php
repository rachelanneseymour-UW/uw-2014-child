<!-- This page is used to display individual blog posts -->
<?php if(function_exists('get_header')) { get_header(); } ?>

<div class="uw-hero-image hero-blank">
	<h1 class="container uw-site-title-blank hprc-blog-page">&nbsp;</h1>
</div>

<div class="container uw-body">

  <div class="row">

    <div <?php if(function_exists('uw_content_class')){uw_content_class();} ?> role='main'>

      <?php get_template_part('menu', 'mobile'); ?>

      <?php get_template_part( 'breadcrumbs' ); ?>

      <div id='main_content' class="uw-body-copy" tabindex="-1">

  			<?php
  				// Start the Loop.
  				while ( have_posts() ) : the_post();

  					/*
  					 * Include the post format-specific template for the content. If you want to
  					 * use this in a child theme, then include a file called called content-___.php
  					 * (where ___ is the post format) and that will be used instead.
  					 */
  					get_template_part( 'content', get_post_format() );

  					// If comments are open or we have at least one comment, load up the comment template.
  					if ( comments_open() || get_comments_number() ) {
  				        comments_template();
  					}

  				endwhile;
  			?>

            <span class="next-page"><?php next_posts_link( 'Next page', '' ); ?></span>

      </div>
			<?php the_tags(); ?>
    </div>

		<div class="col-md-4 uw-sidebar uw-blog-sidebar">
			<?php dynamic_sidebar( 'uw_child_blog_sidebar' ); ?>
		</div>

    <?php //get_sidebar() ?>

  </div>

</div>

<?php get_footer(); ?>
