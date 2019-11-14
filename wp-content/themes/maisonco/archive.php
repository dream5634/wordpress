<?php
get_header(); ?>
<?php if (have_posts()) : ?>
    <header class="page-header">
		<?php
		the_archive_title( '<h1 class="page-title screen-reader-text">', '</h1>' );
		the_archive_description( '<div class="taxonomy-description">', '</div>' );
		?>
    </header><!-- .page-header -->
<?php endif; ?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">

				<?php
				if (have_posts()) :
					get_template_part('template-parts/post');

                    the_posts_pagination( array(
                        'prev_text'          => '<span class="opal-icon-arrow-left"></span><span class="screen-reader-text">' . esc_html__( 'Previous', 'maisonco' ) . '</span>',
                        'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next', 'maisonco' ) . '</span><span class="opal-icon-arrow-right"></span>',
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'maisonco' ) . ' </span>',
                    ) );
				else :
					get_template_part( 'template-parts/post/content', 'none' );

				endif; ?>

            </main><!-- #main -->
        </div><!-- #primary -->
		<?php get_sidebar(); ?>
    </div><!-- .wrap -->

<?php get_footer();
