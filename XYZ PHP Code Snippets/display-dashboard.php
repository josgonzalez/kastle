<?php 
//display the custom field called 'dashboard_link'
echo get_post_meta(get_the_ID(), 'dashboard_link', true); 
?>