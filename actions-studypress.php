<?php

if ( !defined( 'ABSPATH' ) ) exit;


function sp_setExcerpt($idPost,$excerpt){
    $idPost = (int) $idPost;
    $access = new AccessData();
    $access->query($access->prepare("UPDATE " . StudyPressDB::getTableNamePostWP() . " SET post_excerpt ='%s'  WHERE ID = '" . $idPost ."'",$excerpt));
}


function isIdCategoryWpExist($id){
    $id = (int) $id;
    $cat = get_term_by('id', $id, 'category');
    return $cat;
}


add_action( 'init', 'studypress_load_plugin_textdomain' );

function studypress_load_plugin_textdomain()
{

    load_plugin_textdomain( SpTranslate::getDomain(), false, SpTranslate::getPath() );
}

add_action( 'delete_term_taxonomy', function( $tt_id )
{

    $access = new AccessData();
    $access->delete(StudyPressDB::getTableName_CourseCategory(),
        array(StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE => $tt_id));
});


add_action( 'delete_user',function( $userId ) {
    $access = new AccessData();
    $access->delete(StudyPressDB::getTableName_CourseUsers(),
        array(StudyPressDB::COL_ID_USERS_USERS_N_COURSE => $userId));
});


add_shortcode('studypress_lesson','studypress_shortcode_lesson');


function studypress_shortcode_lesson($atts,$content)
{
    global $sp_lecteur;
    $atts = shortcode_atts(array(
        'id' => null
    ),$atts);
    extract($atts);

    if($sp_lecteur < 1)
    {
        require_once __ROOT_PLUGIN__ . "controllers/player-lesson.controller.php";
        $sp_lecteur++;
    }


}


add_shortcode('studypress_quiz','studypress_shortcode_quiz');


function studypress_shortcode_quiz($atts,$content)
{
    global $sp_lecteur;
    $atts = shortcode_atts(array(
        'id' => null
    ),$atts);
    extract($atts);


    if($sp_lecteur < 1)
    {
        require_once __ROOT_PLUGIN__ . "controllers/player-quiz.controller.php";
        $sp_lecteur++;
    }



}

add_action( 'wp_loaded', function() {
    global $tr;

    $labels = array(
        'name' => $tr->__( 'Course' ),
        'singular_name' => $tr->__( 'Course' ),
        'add_new' => $tr->__( 'Add course' ),
        'all_items' => $tr->__( 'All courses' ),
        'add_new_item' => $tr->__( 'Add course' ),
        'edit_item' => $tr->__( 'Edit course' ),
        'new_item' => $tr->__( 'New course' ),
        'view_item' => $tr->__( 'View course' ),
        'search_items' => $tr->__( 'Search courses' ),
        'not_found' => $tr->__( 'No courses found' ),
        'not_found_in_trash' => $tr->__( 'No courses found in trash' ),
        'parent_item_colon' => $tr->__( 'Parent course' )

    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_in_menu'       => false,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => true,
        'hierarchical' => false,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',

        ),

    );
    register_post_type(
        'course',
        $args
    );
});


add_shortcode('studypress_child','studypress_shortcode_child');


function studypress_shortcode_child($atts,$content)
{
    $atts = shortcode_atts(array(
        'id' => null
    ),$atts);
    extract($atts);

    $args = array(
        'numberposts' => -1,
        'order'=> 'DESC',
        'post_parent' => $id,
    );

    
    require_once __ROOT_PLUGIN__ . "Views/course-page.php";


}