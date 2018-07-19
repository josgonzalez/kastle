<!--Header for widget-->
<h5 class="favoriteWidgetHeader">MY FAVORITES</h5>
<hr class="favoriteWidgetHr">

<?php
$favorites = get_user_favorites();
$count = 0;
$size = sizeof($favorites);
?>

<ul class="favoriteWidgetList" style="list-style-type: none">

<!--
Category ID's for categories that should be shown after page title
Includes the SBAC, MAP, and TNTP categories
-->
<?php $categories = array(1820, 1842, 1857, 785, 733, 736, 767) ?>

<?php
foreach($favorites as $key => $value) {
    $post = get_post($value);
    $link = get_permalink($post);

    $parent = null;
    //Get parent page if it is included in the array of categories created
    if (in_array(wp_get_post_parent_id($post->ID), $categories)):
        $parent = get_post(wp_get_post_parent_id($post->ID));
    endif;

    //If user has more than 5 favorites than only show the recent 5, else display all favorites
    if ($size >= 5) {
        if ($count >= $size - 5 and $count < $size) { 
?>
            <li>
                <div style="display: flex; flex-wrap: nowrap;">
                    <div class="favoriteWidgetBtn"> 
                        <?php the_favorites_button($post->ID); ?>
                    </div>
                    <div>
                        <!--if page has one of the categories in array than print after title, else just print title-->
                        <?php if ($parent) :?>
                            <p class="favoriteWidgetTitle"><a href=<?php echo $link ?>><?php echo $post->post_title . " (" . $parent->post_title . ")"?> 
                        <?php  
                        else : ?>
                            <p class="favoriteWidgetTitle"><a href=<?php echo $link ?>><?php echo $post->post_title; ?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
<?php
        }
    }
    else {
?>
            <li>
                <div style="display: flex; flex-wrap: nowrap; margin-bottom: 1em;">
                    <div class="favoriteWidgetBtn"> 
                        <?php the_favorites_button($post->ID); ?>
                    </div>
                    <div>
                        <?php if ($parent) :?>
                            <p class="favoriteWidgetTitle"><a href=<?php echo $link ?>><?php echo $post->post_title . " (" . $parent->post_title . ")"?> 
                        <?php  
                        else : ?>
                            <p class="favoriteWidgetTitle"><a href=<?php echo $link ?>><?php echo $post->post_title; ?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
<?php
    }
    $count++;
}
?>
    <li class="favoriteWidgetViewAll"><a href="https://kastle.kippla.org/favorites/">View All</a></li>    
</ul>