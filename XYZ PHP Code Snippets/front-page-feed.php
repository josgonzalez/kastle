<?php
//get user role
$role = um_user('role'); 

//display feed according to roll by displaying the correct search and filter result
if ($role === "administrator" || $role === "um_senior-leadership") {
  echo do_shortcode('[searchandfilter id="2840" show="results"]'); 
}

if ($role === "um_school-leaders") {
  echo do_shortcode('[searchandfilter id="4099"  show="results"]'); 
}

if ($role === "um_member") {
  echo do_shortcode('[searchandfilter id="4100"  show="results"]'); 
}

if ($role === "um_sst") {
  echo do_shortcode('[searchandfilter id="4098"  show="results"]'); 
}