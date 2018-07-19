<?php
//template for archive pages such as the category page

get_header(); ?>
<div id="content" class="site-content">
	<div class="container pad-top-40">
		<div class="row">
			<?php if ( get_theme_mod( 'ads_sidebar_layout' ) == 'left'  ) : ?>
                       <?php get_sidebar('ads'); ?>
			<?php endif; ?>
			<?php if  ( get_theme_mod( 'archive_post_layout' ) =='sidebar-left'  ) : ?>
                <?php get_sidebar(); ?>
			<?php endif; ?>
			<div id="primary" class="content-area column content-width">
				<main id="main" class="site-main">

				<?php
				if ( have_posts() ) : ?>
                    <div class="catPage">
                        <header class="catPageHeader">
                            <?php
                                the_archive_title( '<h1 class="catTitle">', '</h1>' );
                                the_archive_description( '<div class="catDescription">', '</div>' );
                            ?>
                        </header><!-- .catPageHeader -->

                        <?php if ( get_theme_mod( 'post_display_layout' ) == 'grid'  ) : ?>

                            <div class="row">
                                <?php
                                /* Start the Loop */
                                while ( have_posts() ) : the_post();  ?>
                                    <div class="column column-50">

                                        <?php	get_template_part( 'template-parts/content', get_post_format() ); ?>
                                    </div>
                                <?php
                                endwhile;  ?>
                            </div>

                    <?php else : ?>
                            <ul style="list-style-type: none">
                            <?php
                                /* Start the Loop */
                                while ( have_posts() ) : the_post();
                                    $excerpt = get_the_excerpt(get_the_ID());
                                    // if page excerpt is long than 150 characters then cut it off
                                    if (strlen($excerpt) > 150) {
                                        $excerpt = substr($excerpt, 0, 149) . '...';
                                    }
                            ?>
                                <!--Template for displaying each item-->
                                <li>
                                    <div class="catPageItem">
                                        <img class="catPageThumbnail" src="<?php echo get_the_post_thumbnail_url()?>">
                                        <div>
                                            <h2 class="catPageTitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2> 
                                            <p class="catPageExcerpt"><?php echo $excerpt; ?></p>
                                        </div>
                                    </div>
                                    <hr class="catPageHr">
                                </li>
                            <?php
                                endwhile;
                            ?>
                            </ul>

                        <?php endif; ?>

                        <?php

                        the_posts_navigation( array(
                            'next_text'         => __( '<span class="button">Newer Posts</span>', 'custompress' ),
                            'prev_text'         => __( '<span class="button">Older Posts</span>', 'custompress' ),
                        ) );
                        ?>
                    </div>
                    <?php
				else :

					get_template_part( 'template-parts/content', 'none' );

                endif; ?>   
            

				</main><!-- #main -->
			</div><!-- #primary -->
			<?php if ( ( get_theme_mod( 'archive_post_layout' ) =='sidebar-right' ) || ! get_theme_mod( 'archive_post_layout' ) ) : ?>
                <?php get_sidebar(); ?>
			<?php endif; ?>
			<?php if ( get_theme_mod( 'ads_sidebar_layout' ) == 'right'  ) : ?>
                       <?php get_sidebar('ads'); ?>
			<?php endif; ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- #content -->
<?php
get_footer();
