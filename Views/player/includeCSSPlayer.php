<?php

wp_register_style( "display_css_normalize", plugins_url("studypress/css/player/normalize.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_normalize');



wp_register_style( "display_css_style", plugins_url("studypress/css/player/style-".$type.".css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_style');

wp_register_style( "display_css_carousel", plugins_url("studypress/css/player/owl.carousel.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_carousel');

wp_register_style( "display_css_tabs", plugins_url("studypress/css/player/tabs.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_tabs');

wp_register_style( "display_css_tabstyle", plugins_url("studypress/css/player/tabstyles.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_tabstyle');



wp_register_style( "display_css_rating", plugins_url("studypress/css/player/rating.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_rating');




?>
