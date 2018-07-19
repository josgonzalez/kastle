<?php

//get user role
$role = um_user('role'); 

//display search results according to user role
if ($role === "administrator" || $role === "um_senior-leadership") {
  echo do_shortcode('[searchandfilter id="2155" show="results"]'); 
}

if ($role === "um_school-leaders") {
  echo do_shortcode('[searchandfilter id="2216"  show="results"]'); 
}

if ($role === "um_member") {
  echo do_shortcode('[searchandfilter id="2217"  show="results"]'); 
}

if ($role === "um_sst") {
  echo do_shortcode('[searchandfilter id="2900"  show="results"]'); 
}