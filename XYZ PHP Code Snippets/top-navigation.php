<?php
//get user role
$role = um_user('role');
//get current page user in on
$post = get_post();
?>

<div style="display: grid; grid-template-columns: auto;">
    <div>
<?php
        //display search bar according to user role
        $role = um_user('role'); 
        if ($post->ID != 1216) {
            if ($role === "administrator" || $role === "um_senior-leadership") {
                echo do_shortcode('[searchandfilter id="1754"]'); 
            }
            else if ($role === "um_sst") {
                echo do_shortcode('[searchandfilter id="2901"]'); 
            }
              
            else if ($role === "um_school-leaders") {
                echo do_shortcode('[searchandfilter id="2352"]'); 
            }
              
            else if ($role === "um_member") {
                echo do_shortcode('[searchandfilter id="2353"]'); 
            }
        }
?>
    </div>
    <!--display logout, favorites, faq, and search button-->
    <div>
        <a href="http://kastle.kippla.org/wp-login.php?action=logout" class=logout-button>Logout</a>
        <a href="https://kastle.kippla.org/favorites/" class=favorites-button>Favorites</a>
        <a href="https://kastle.kippla.org/faqs/" class="faq">FAQ</a>
        <a href="https://kastle.kippla.org/search/" class=searchbutton>Dashboards</a>
    </div>
</div>