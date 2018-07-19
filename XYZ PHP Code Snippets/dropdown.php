<?php
    //get page excerpt
    $excerpt = get_the_excerpt($post_id);
    //get custom field called 'dashboard_tips'
    $tips = get_post_meta(get_the_ID(), 'dashboard_tips', true);
    //get custom field call 'context'
    $context = get_post_meta(get_the_ID(), 'context', true);

    //170 characters per line
    ?>
    <!--Styling for dropdown animation-->
    <style>
        .dropdown {
            transition-property: all;
            transition-duration: .5s;
            overflow-y: hidden;
            max-height: 0;
        }
        .opened {
            transition-property: all;
            transition-duration: .5s;
            max-height: max-content;
        }
        .collapse {
            display: none;
        }
    </style>

    <?php 
    //function that creates a dropdown that takes a name for the header and a value for what is droped down
    function dropdown($name, $value) {
        echo '
        <script>
        function toggle' . $name . '() {
            document.getElementById("'. $name .'dropdown").classList.toggle("opened");
            document.getElementById("'. $name .'downarrow").classList.toggle("collapse");
            document.getElementById("'. $name .'rightarrow").classList.toggle("collapse");
        }
        </script>

        <button class="toggleButton" onclick=toggle' . $name . '()>' . $name . ' <span id="'. $name .'downarrow" class="'. $name .'downarrow collapse downarrow">&#9662;</span><span id="'. $name .'rightarrow" class="'. $name .'rightarrow rightarrow">&#9656;</span></button>
        <div class="'. $name .'dropdown dropdown" id="'. $name .'dropdown">' . $value . '</div>
        ';    
    }

    //add new dropdowns here
    dropdown('Description', $excerpt);
    //if dashboard has tips then create a dropdown
    if ($tips)
        dropdown('Tips', $tips);
    //if dashboard has context then create a dropdown
    if ($context) 
        dropdown('Context', $context);
    ?>