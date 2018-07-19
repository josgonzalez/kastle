<?php
/**
 * Search & Filter Pro 
 *
 * Sample Results Template
 * 
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 * 
 * Note: these templates are not full page templates, rather 
 * just an encaspulation of the your results loop which should
 * be inserted in to other pages by using a shortcode - think 
 * of it as a template part
 * 
 * This template is an absolute base example showing you what
 * you can do, for more customisation see the WordPress docs 
 * and using template tags - 
 * 
 * http://codex.wordpress.org/Template_Tags
 *
 */

//Results page that is used for the front page feed and and search results page

//first check if the query returns any results
$page = get_post();

//template for search results page
if ($page->ID != 109) {
    if ( $query->have_posts() ) {
	?>
	Found <?php echo $query->found_posts; ?> Results 

	<br />
	<br />

	<div class="searchPagi">
	<span style="font-weight: bold">
	<?php previous_posts_link( '<' ); ?>
	</span>
	<?php 
	//searchPagi only if theres is more than one page
	if ($query->max_num_pages > 1) {
		//display all page indexes if there is less than 5 pages
		if ($query ->max_num_pages <= 5){
			for($i = 1; $i <= $query->max_num_pages; $i++) {
				//check index of current page to style the index and remove link
				if ($i === $query->query['paged']) {
					echo "<span class=\"searchCurrentPage\">" . $i . "</span>"; 
				}
				//display index as hyperlink if not currently on page
				else {
					echo "<a href=/search/?sf_paged=" . $i . "> $i </a>";
				}
			}
		}
		//if there are more than 5 pages than only display next 4
		else {
			$current_page = $query->query['paged'];
			
			//display next 4 if there are 4 available
			if ($current_page + 4 <= $query->max_num_pages) {
				for($i = $current_page; $i <= $current_page + 4; $i++) {
					if ($i === $query->query['paged']) {
						echo "<span class=\"searchCurrentPage\">" . $i . "</span>";
					}
					else {
						echo "<a href=/search/?sf_paged=" . $i . "> $i </a>";
					}
				}
			}
			//if there are less than 4 next pages then display previous page indexes until there are 5 indexes in total
			else {
				for($i = $query->max_num_pages - 4; $i <= $query->max_num_pages; $i++) {
					if ($i == $query->query['paged']) {
						echo "<span class=\"searchCurrentPage\">" . $i . "</span>";				
					}
					else {
						echo "<a href=/search/?sf_paged=" . $i . "> $i </a>";
					}
				}
			}
		}
	}
	?>
	<span style="font-weight: bold">
	<?php next_posts_link( '>',  $query->max_num_pages); ?>
	</span>
	</div>
    
    <ul style="list-style-type: none">
	<?php
    //iterate through results
	while ($query->have_posts())
	{
        $query->the_post();
        $searchExcerpt = get_the_excerpt(get_the_ID());
        if (strlen($searchExcerpt) > 175) {
            $searchExcerpt = substr($searchExcerpt, 0, 174) . '...';
        }
         ?>
        <li>
            <div class="searchResult">
                <?php 
                    //display searchThumb if there is one
                    if ( has_post_thumbnail() ) {
                ?>
                <img class="searchThumb" src="<?php echo get_the_post_thumbnail_url()?>" >
                <?php
                    }
                ?>		
                <div>
                    <h2 class="searchTitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p class="searchCate"><small>Category: <?php the_category(', ', '', get_the_ID()); ?></small></p>
                    <p class="searchExcerpt"><?php echo $searchExcerpt ?></p>
                </div>
            </div>
            <hr class="searchHr">
        </li>
		<?php
	}
    ?>
    </ul>
	
	<!--Same searchPagi as top-->
	<div class="searchPagi">
	<span style="font-weight: bold">
	<?php previous_posts_link( '<' ); ?>
	</span>
	<?php 
	if ($query->max_num_pages > 1) {
		if ($query ->max_num_pages <= 5){
			for($i = 1; $i <= $query->max_num_pages; $i++) {
				if ($i === $query->query['paged']) {
					echo "<span class=\"searchCurrentPage\">" . $i . "</span>";
				}
				else {
					echo "<a href=/search/?sf_paged=" . $i . "> $i </a>";
				}
			}
		}
		else {
			$current_page = $query->query['paged'];
			if ($current_page + 4 <= $query->max_num_pages) {
				for($i = $current_page; $i <= $current_page + 4; $i++) {
					if ($i === $query->query['paged']) {
						echo "<span class=\"searchCurrentPage\">" . $i . "</span>";
					}
					else {
						echo "<a href=/search/?sf_paged=" . $i . "> $i </a>";
					}
				}
			}
			else {
				for($i = $query->max_num_pages - 4; $i <= $query->max_num_pages; $i++) {
					if ($i == $query->query['paged']) {
						echo "<span class=\"searchCurrentPage\">" . $i . "</span>";				
					}
					else {
						echo "<a href=/search/?sf_paged=" . $i . "> $i </a>";
					}
				}
			}
			
		
		}
		
	}
	?>
	<span style="font-weight: bold">
	<?php next_posts_link( '>',  $query->max_num_pages); ?>
	</span>
	</div>

	<?php
}
// if there are on results than display the following message
else
{
	echo "No Results Found";
	?>
<?php
}
}
//template for front page
else {
    $current_user = wp_get_current_user();
    $firstname = $current_user->user_firstname;
    $lastname = $current_user->user_lastname;
    $role = um_user('role');
	$site = get_cimyFieldValue($current_user->ID, 'Site');

    if ($role === 'administrator') {
        $role = 'Administrator';
    } 
    if ($role === 'um_member') {
        $role = 'Member';
    } 
    if ($role === 'um_sst') {
        $role = 'SST';
    } 
    if ($role === 'um_school-leaders') {
        $role = 'School Leader';
    } 
	if ($role === 'um_senior-leadership') {
        $role = 'Senior Leadership';
    } 
?>

<!--Welcome message on front page-->
<div class="feedHeader">
    <h2> Welcome, <?php echo $firstname . " " . $lastname; ?></h2>
    <p> <?php echo $role . ", " . cimy_uef_sanitize_content($site); ?> </p>
</div>

<!--Button to filter results-->
<div class="tag-buttons">
    <a class="tag-button tag-button-announcements" href=<?php echo esc_url(add_query_arg('_sft_front_page_tag', 'announcements')) ?>>Announcements</a>
    <a class="tag-button tag-button-resources" href=<?php echo esc_url(add_query_arg('_sft_front_page_tag', 'resources')) ?>>Resources</a>
    <a class="tag-button tag-button-newdash" href=<?php echo esc_url(add_query_arg('_sft_front_page_tag', 'new-dashboard')) ?>>New Dashboards</a>
    <a class="tag-button tag-button-updateddash" href=<?php echo esc_url(add_query_arg('_sft_front_page_tag', 'updated-dashboard')) ?>>Updated Dashboards</a>
</div>

<ul style="list-style-type: none">
<?php
    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
        $post = get_post(get_the_ID());
        $front_page_tag = get_the_terms($post->ID, 'front_page_tag');

        //only show pages with front page tags
        if ($front_page_tag) {

            //template for pages
            if ($post->post_type == 'page') {
                $excerpt = $post->post_excerpt;
                //if excerpt is over 175 characters long then cut if off
                if (strlen($excerpt) > 175) {
                    $excerpt = substr($excerpt, 0, 174) . '...';
                }
?>
            <li>
                <div class="feed">
                    <img class="feedThumbnail" src="<?php echo get_the_post_thumbnail_url($post)?>" >		
                    <div>
                        <h2 class="feedTitle">
                            <a href=<?php echo get_the_permalink($post); ?>> <?php echo get_the_title($post);?> </a>
<?php
                            if ($front_page_tag[0]->name == 'New Dashboard') {
?>
                                <span class="feedNewDashTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                            }
                            if ($front_page_tag[0]->name == 'Updated Dashboard') {
?>
                                <span class="feedUpDashTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                            }
                            if ($front_page_tag[0]->name == 'News') {
?>
                                <span class="feedNewsTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                            }
                            if ($front_page_tag[0]->name == 'Updates') {
?>
                                <span class="feedUpdatesTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                            }
                            if ($front_page_tag[0]->name == 'Announcements') {
?>
                                <span class="feedAnnouncementsTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                            }
                            if ($front_page_tag[0]->name == 'Resources') {
?>
                                <span class="feedResourcesTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                            }
?>                            

                        </h2>
                        <p class="feedDate"><small><?php echo get_the_date('F j, Y', $post); ?></small></p>
                        <p class="feedCategories"><small>Categories: <?php echo get_the_category_list(', ', '', $post->ID); ?></small></p>
                        <p class="feedExcerpt"><?php echo $excerpt; ?></p>
                    </div>
                </div>
            </li>
            <hr class="feedHr">
<?php
        }
            //template for posts
            else {
                $content = $post->post_content;
                //if post content is over 175 characters then cut it off
                if (strlen($content) > 175) {
                    $content = substr($content, 0, 174) . '...';
                }
    ?>  
            <li>
                <div class="feed">
                    <img class="feedThumbnail" src="<?php echo get_the_post_thumbnail_url($post)?>" >		
                    <div>
                        <h2 class="feedTitle">
                            <a href=<?php echo get_the_permalink($post); ?>> <?php echo get_the_title($post);?> </a>
<?php
                                if ($front_page_tag[0]->name == 'New Dashboard') {
?>
                                    <span class="feedNewDashTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                                }
                                if ($front_page_tag[0]->name == 'Updated Dashboard') {
?>
                                    <span class="feedUpDashTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                                }
                                if ($front_page_tag[0]->name == 'News') {
?>
                                    <span class="feedNewsTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                                }
                                if ($front_page_tag[0]->name == 'Updates') {
?>
                                    <span class="feedUpdatesTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                                }
                                if ($front_page_tag[0]->name == 'Announcements') {
?>
                                    <span class="feedAnnouncementsTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                                }
                                if ($front_page_tag[0]->name == 'Resources') {
?>
                                    <span class="feedResourcesTag"><?php echo $front_page_tag[0]->name; ?></span>
<?php
                                }
?>                            
                                    
                        </h2>
                        <p class="feedDate"><small><?php echo get_the_date('F j, Y', $post); ?></small></p>
                        <p class="feedExcerpt"><?php echo $content; ?></p>
                    </div>
                </div>
            </li>
            <hr class="feedHr">            
<?php
        }

    }
    endwhile; 
    endif; wp_reset_postdata(); 
?>
</ul>
<div class="feedPagi">
<?php
    $current_page = $query->query['paged'];
    if ($current_page != 1) {
        echo "<a href=" . esc_url(add_query_arg('sf_paged', $current_page - 1)) . "> < </a>";
        #echo "<a href=/?sf_paged=" . ($current_page - 1) . "> < </a>";
    } 

    if ($query->max_num_pages > 1) {
        //display all page indexes if there is less than 5 pages
        if ($query->max_num_pages <= 5){
            for($i = 1; $i <= $query->max_num_pages; $i++) {
                //check index of current page to style the index and remove link
                if ($i === $query->query['paged']) {
                    echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
                }
                //display index as hyperlink if not currently on page
                else {
                    echo "<a href=" . esc_url(add_query_arg('sf_paged', $i)) . "> $i </a>";
                    #echo "<a href=?sf_paged=" . $i . "> $i </a>";
                }
            }
        }
        else {
            $current_page = $query->query['paged'];
            if ($current_page == 1 || $current_page == 2) {
                for($i = 1; $i <= 5; $i++) {
                    if ($i === $query->query['paged']) {
                        echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
                    }
                    else {
                        echo "<a href=" . esc_url(add_query_arg('sf_paged', $i)) . "> $i </a>";
                        #echo "<a href=?sf_paged=" . $i . "> $i </a>";
                    }
                }
            }
            else if ($current_page - 2 > 0 && $current_page + 2 <= $query->max_num_pages) {
                for($i = $current_page - 2; $i <= $current_page + 2; $i++) {
                    if ($i === $query->query['paged']) {
                        echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
                    }
                    else {
                        echo "<a href=" . esc_url(add_query_arg('sf_paged', $i)) . "> $i </a>";
                        #echo "<a href=?sf_paged=" . $i . "> $i </a>";
                    }
                }
            }
            else if ($current_page == $query->max_num_pages - 1) {
                for($i = $current_page - 3; $i <= $query->max_num_pages; $i++) {
                    if ($i === $query->query['paged']) {
                        echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
                    }
                    else {
                        echo "<a href=" . esc_url(add_query_arg('sf_paged', $i)) . "> $i </a>";
                        #echo "<a href=?sf_paged=" . $i . "> $i </a>";
                    }
                }
            }
            else if ($current_page == $query->max_num_pages) {
                for($i = $current_page - 4; $i <= $query->max_num_pages; $i++) {
                    if ($i === $query->query['paged']) {
                        echo "<span style=\"font-size: 120%; color:black;\">" . $i . "</span>"; 
                    }
                    else {
                        echo "<a href=" . esc_url(add_query_arg('sf_paged', $i)) . "> $i </a>";
                        #echo "<a href=?sf_paged=" . $i . "> $i </a>";
                    }
                }
            }
        }

        if ($current_page != $query->max_num_pages) {
            echo "<a href=" . esc_url(add_query_arg('sf_paged', $current_page + 1)) . "> > </a>";
            #echo "<a href=?sf_paged=" . ($current_page + 1) . "> > </a>";
        } 
    }
?>
</div>

<?php
}
?>
