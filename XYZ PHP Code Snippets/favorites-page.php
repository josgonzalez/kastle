<?php
//some code and help for creating query code from https://gist.github.com/kylephillips/9fe12f195c671c989af3
$favorites = get_user_favorites();
if ( $favorites ) : // This is important: if an empty array is passed into the WP_Query parameters, all posts will be returned
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; // If you want to include pagination
$favorites_query = new WP_Query(array(
	'post_type' => 'page', // If you have multiple post types, pass an array
	'posts_per_page' => 10, // Number of items per page
	'ignore_sticky_posts' => true,
	'post__in' => $favorites, // post only in the favorites array
	'paged' => $paged // If you want to include pagination, and have a specific posts_per_page set
));
?>

<div style="text-align: center">
<span style="font-weight: bold">
<?php previous_posts_link( '<' ); ?>
</span>
<?php 
//pagination only if theres is more than one page
if ($favorites_query->max_num_pages > 1) {
    //display all page indexes if there is less than 5 pages
    if ($favorites_query ->max_num_pages <= 5){
        for($i = 1; $i <= $favorites_query->max_num_pages; $i++) {
            //check index of current page to style the index and remove link
            if ($i === $favorites_query->query['paged']) {
                echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
            }
            //display index as hyperlink if not currently on page
            else {
                echo "<a href=/favorites/page/" . $i . "> $i </a>";
            }
        }
    }
    //if there are more than 5 pages than only display next 4
    else {
        $current_page = $favorites_query->query['paged'];
        
        //display next 4 if there are 4 available
        if ($current_page + 4 <= $favorites_query->max_num_pages) {
            for($i = $current_page; $i <= $current_page + 4; $i++) {
                if ($i === $favorites_query->query['paged']) {
                    echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
                }
                else {
                    echo "<a href=/favorites/page/" . $i . "> $i </a>";
                }
            }
        }
        //if there are less than 4 next pages then display previous page indexes until there are 5 indexes in total
        else {
            for($i = $favorites_query->max_num_pages - 4; $i <= $favorites_query->max_num_pages; $i++) {
                if ($i == $favorites_query->query['paged']) {
                    echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 				
                }
                else {
                    echo "<a href=/favorites/page/" . $i . "> $i </a>";
                }
            }
        }
    }
}
?>
<span style="font-weight: bold">
<?php next_posts_link( '>',  $favorites_query->max_num_pages); ?>
</span>
</div>

<ul style="list-style-type: none">
<?php
if ( $favorites_query->have_posts() ) : while ( $favorites_query->have_posts() ) : $favorites_query->the_post();
    // Treat this like any other WP loop
    $excerpt = get_the_excerpt(get_the_ID());
    //if exerpt if more than 150 characters then cut it off 
    if (strlen($excerpt) > 150) {
        $excerpt = substr($excerpt, 0, 149) . '...';
    }
?>  <li>
        <!--What is displayed for each favorite-->
        <div class="favorite-item">
            <img class="favorite-thumbnail" src="<?php echo get_the_post_thumbnail_url()?>">
            <div>
                <h2 class="favorite-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2> 
                <p class="favorite-date"><small><?php the_date(); ?></small></p>
                <p class="favorite-categories"><small>Categories: <?php the_category(', ', '', get_the_ID()); ?></small></p>
                <p class="favorite-excerpt"><?php echo $excerpt; ?></p>
            </div>
        </div>
        <hr class="favorite-hr">
    </li>
<?php
endwhile; 
endif; wp_reset_postdata();
?>
</ul>

<div style="text-align: center">
<span style="font-weight: bold">
<?php previous_posts_link( '<' ); ?>
</span>
<?php 
//pagination only if theres is more than one page
if ($favorites_query->max_num_pages > 1) {
    //display all page indexes if there is less than 5 pages
    if ($favorites_query ->max_num_pages <= 5){
        for($i = 1; $i <= $favorites_query->max_num_pages; $i++) {
            //check index of current page to style the index and remove link
            if ($i === $favorites_query->query['paged']) {
                echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
            }
            //display index as hyperlink if not currently on page
            else {
                echo "<a href=/favorites/page/" . $i . "> $i </a>";
            }
        }
    }
    //if there are more than 5 pages than only display next 4
    else {
        $current_page = $favorites_query->query['paged'];
        
        //display next 4 if there are 4 available
        if ($current_page + 4 <= $favorites_query->max_num_pages) {
            for($i = $current_page; $i <= $current_page + 4; $i++) {
                if ($i === $favorites_query->query['paged']) {
                    echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
                }
                else {
                    echo "<a href=/favorites/page/" . $i . "> $i </a>";
                }
            }
        }
        //if there are less than 4 next pages then display previous page indexes until there are 5 indexes in total
        else {
            for($i = $favorites_query->max_num_pages - 4; $i <= $favorites_query->max_num_pages; $i++) {
                if ($i == $favorites_query->query['paged']) {
                    echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 				
                }
                else {
                    echo "<a href=/favorites/page/" . $i . "> $i </a>";
                }
            }
        }
    }
}
?>
<span style="font-weight: bold">
<?php next_posts_link( '>',  $favorites_query->max_num_pages); ?>
</span>
</div>

<?php
else :
	// No Favorites
endif;
?>