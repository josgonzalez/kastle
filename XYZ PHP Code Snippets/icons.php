<!--Styling for dropdown text on icons-->
<style>
.tooltip {
    position: relative;
    display: inline-block;
    margin: 0em 1em 1em 0em;
}

.tooltip .tooltiptext {
    font-size: 12px;
    visibility: hidden;
    width: 150px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
    width: 170px;
    top: 100%;
    left: 50%; 
    margin-left: -60px;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}

.icons img{
background-color: #A7CFEE;
border-radius: 50%;
}
</style>

<?php
//get current post data
$post = get_post();
//get owner, access level, update frequency, and extra icons of post
$owner = get_the_terms($post->ID, 'owner');
$access_level = get_the_terms($post->ID, 'user_access');
$update_frequency = get_the_terms($post->ID, 'update_freq');
$extra_icons = get_the_terms($post->ID, 'extra_icons');

//function to create icon that takes in the image url and text for dropdown
function get_icon($icon_url, $text) {
    echo '<div class="tooltip icons">
    <img style="border-radius:50%" src="' . $icon_url . '" width="30" height="30">
    <span class="tooltiptext">' . $text . '</span>
    </div>';
}

//function that takes access level value and creates icon
function getAccessLevelIcon($access_level_value) {
    if ($access_level_value === "all users") {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-All-Users.png', 'Viewable to all users');
    }
    else if ($access_level_value === "school leaders") {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-SST-and-SLs.png', 'Viewable to SST and School Leaders Only');
    }
    else if ($access_level_value === "sst") {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-SST-Only.png', 'Viewable to SST Only');
    }
    else if ($access_level_value === "senior leaders") {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/07/Dashboard-Icon-Senior-Leadership.png', 'Viewable to Senior Leaders Only');
    }
}

//funciton that take update frequency value and creates icon
function getUpdateFrequencyIcon($update_frequency_value) {
    if ($update_frequency_value === 'Hourly') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Updated-Hourly.png', 'Updated Hourly');
    }
    else if ($update_frequency_value === 'Daily') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Updated-Daily.png', 'Updated Daily');
    }
    else if ($update_frequency_value === 'Monthly') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Updated-Seasonally.png', 'Updated Monthly');
    }
    else if ($update_frequency_value === 'Seasonally') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Updated-Seasonally.png', 'Updated Seasonally');
    }
    else if ($update_frequency_value === 'Annually') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Updated-Seasonally.png', 'Updated Annually');
    }
    else if ($update_frequency_value === 'Semiannually') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Updated-Seasonally.png', 'Updated Semiannually');
    }
}

//function that takes in owner value and creates icon
function getOwnerIcon($owner_value) {
    if ($owner_value === 'Nicole' || $owner_value === 'Nicole Jeong') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Nicole.png', 'Owned By Nicole');
    }
    else if ($owner_value === 'Kim' || $owner_value === 'Kimberly Eng') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Kim.png', 'Owned By Kim');
    }

    else if ($owner_value === 'Jose Corona') {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/07/Dashboard-Icon-Jose.png', 'Owned By Jose');
    }

    else {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/07/Dashboard-Icon-Other-Owner.png', 'Owned by someone outside of Data Team');
    }
}

//fucntions that takes array of extra icon values and creates icons
function getExtraIcons($extra_icons) {
    if (count($extra_icons) === 2) {
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Actions.png', 'Click on parts of dashboard to filter visualization');
        get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-School-Filters.png', 'Includes filters limiting data to only your school');
    }
    else if (count($extra_icons) === 1) {
        if ($extra_icons[0]->name === 'clickable') {
            get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-Actions.png', 'Click on parts of dashboard to filter visualization');
        }
        else if ($extra_icons[0]->name === 'school filters') {
            get_icon('https://kastle.kippla.org/wp-content/uploads/2018/05/Dashboard-Icon-School-Filters.png', 'Includes filters limiting data to only your school');
        }
    }
}

//call functions and create icons
getUpdateFrequencyIcon($update_frequency[0]->name);
getAccessLevelIcon($access_level[0]->name);
getOwnerIcon($owner[0]->name);
getExtraIcons($extra_icons);