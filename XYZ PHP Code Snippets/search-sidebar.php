<?php
//creates side bar for search page

//get user role
$role = um_user('role'); 

//get sidebar according to user role
if ($role === "administrator" || $role === "um_senior-leadership") {
  echo do_shortcode('[searchandfilter id="2155"]'); 
}

if ($role === "um_school-leaders") {
  echo do_shortcode('[searchandfilter id="2216"]'); 
}

if ($role === "um_member") {
  echo do_shortcode('[searchandfilter id="2217"]'); 
}

if ($role === "um_sst") {
  echo do_shortcode('[searchandfilter id="2900"]'); 
}