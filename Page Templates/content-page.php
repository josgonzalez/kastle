<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CustomPress
 */

//default template for pages in wordpress
/*
Added the navigation header to page, the favorites button to certain pages, and removed the title from displaying on certain pages
*/

?>
<?php 
    //get parent pages
    $parent = null;
    $gparent = null;
    if (wp_get_post_parent_id(get_the_ID()) != 0) :
        $parent = get_post(wp_get_post_parent_id(get_the_ID()));
        if (wp_get_post_parent_id($parent->ID) != 0) :
            $gparent = get_post(wp_get_post_parent_id($parent->ID));
        endif;
    endif;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'tp-panel' ); ?>>
	<header class="entry-header">
        <h5 class="dashPageNavHeader">
            <!--Do not show nav header for these pages-->
            <?php if (get_the_title() && get_the_title() != "Home" && get_the_title() != "Search"):?>
                <?php 
                if ($gparent) :
                    echo $gparent->post_title . " > " . $parent->post_title; 
                else :
                    echo $parent->post_title; 
                endif;
                ?>
			<?php endif; ?>
        </h5>
		<h2 class="dashPageHeader">
            <!--Do not show title for these pages-->
			<?php if (get_the_title() && get_the_title() != "Home" && get_the_title() != "Search" && get_the_title() != "Post Search"):?>
				<?php echo get_the_title(); ?>
			<?php endif; ?>
            <!--Do not show favorite button these pages-->
			<?php if (get_the_title() && get_the_title() != "FAQs" && get_the_title() != "Search" && get_the_title() != "Post Search" && 
					  	get_the_title() != "Home" && get_the_title() != "Favorites" && get_the_title() != "Redirect" && get_the_title() != "My Favorites") : ?>
				<span style="float: right; padding-right: 5%;"> <?php the_favorites_button($post_id, $site_id) ?> </span>
			<?php endif; ?>
		</h2>

	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<?php if ( !get_theme_mod( 'hide_featured_image_page' ) ) : ?>
			<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => '' ) ); ?>
		<?php endif; ?>
	<?php endif; ?>

	<div class="entry-content">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'custompress' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'custompress' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>