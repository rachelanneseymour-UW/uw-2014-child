<!-- used to show tag results -->
<!-- considered making an archive.php but instead of separate tag & category pages but was worried
     it might override other pages too -->
<?php get_header(); ?>

<div class="uw-hero-image hero-blank">
	<h1 class="container uw-site-title-blank hprc-blog-page">&nbsp;</h1>
</div>

<div class="container uw-body">

  <div class="row">

    <div <?php uw_content_class(); ?> role='main'>

      <?php get_template_part( 'menu', 'mobile' ); ?>

      <?php get_template_part( 'breadcrumbs' ); ?>

      <div id='main_content' class="uw-body-copy" tabindex="-1">

      <h1><?php echo single_cat_title( '', false ); ?></h1><hr>

        <?php
          // Start the Loop.
          while ( have_posts() ) : the_post();

            /*
             * Include the post format-specific template for the content. If you want to
             * use this in a child theme, then include a file called called content-___.php
             * (where ___ is the post format) and that will be used instead.
             */
            get_template_part( 'content', 'archive' );


          endwhile;
        ?>
        </br>
        <?php posts_nav_link(' ', 'Previous page', 'Next page'); ?>

      </div>

    </div>

		<div class="col-md-4 uw-sidebar uw-blog-sidebar">
			<?php dynamic_sidebar( 'uw_child_blog_sidebar' ); ?>
		</div>

    <?php //get_sidebar() ?>

  </div>

</div>

<?php get_footer(); ?>
