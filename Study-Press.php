<?php
/*
Plugin Name: StudyPress
Description: StudyPress is an elearning authoring tool. With this plugin you can easily create a learning content and publish it as slides in your wordpress pages and posts. If you use BuddyPress, you can also share the course in your BuddyPress activity page.
Version: 0.11
Author: Tadlaoui mohammed | Bensmaine yasser | Bouacha oussama



*/

add_action('admin_menu', 'notre_plugin');

   $studi_courses = $wpdb->prefix . 'studi_course';
   $studi_slides = $wpdb->prefix . 'studi_slides';
   $studi_category = $wpdb->prefix . 'studi_category';
   $studi_categ_cours = $wpdb->prefix . 'studi_categ_cours';
 //  $studi_quiz = $wpdb->prefix . 'studi_quiz';
  // $studi_categ_quiz = $wpdb->prefix . 'studi_categ_quiz';
 //  $studi_question = $wpdb->prefix . 'studi_question';
  // $studi_reponses = $wpdb->prefix . 'studi_reponse';

function notre_plugin() {
   global $studi_courses;
   global $studi_slides;
   global $studi_category;
   global $studi_categ_cours;
  // global $studi_quiz;
  // global $studi_categ_quiz;
  // global $studi_question;
  // global $studi_reponses;


  global $wpdb;
  $table_name = $studi_category;
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                     $sql2 = "CREATE TABLE $table_name (
                    `cat_id` INT UNSIGNED AUTO_INCREMENT ,
                    `cat_name` VARCHAR(100) UNIQUE ,
                    `cat_des` longtext ,
                    `cat_parent` INT UNSIGNED,
                    `courses_nbr` INT,
                     PRIMARY KEY (cat_id),
                     FOREIGN KEY ( `cat_parent` ) REFERENCES $studi_category (`cat_id`)
)";
                     $wpdb->query($sql2);
                   }


  $table_name = $studi_courses;
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                    $sql = "CREATE TABLE $table_name (
                    `course_id` INT UNSIGNED AUTO_INCREMENT ,
                    `nom` VARCHAR(100) UNIQUE ,
                    `duration` INT NOT NULL,
                    `author` VARCHAR(30),
					`cours_des` longtext ,
					 `cours_picture` text,
                    `shortcode` VARCHAR(40),
                     PRIMARY KEY (course_id))";
                    $wpdb->query($sql);}
                     
  $table_name = $studi_slides;
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                     $sql1 = "CREATE TABLE $table_name (
                    `slides_id` INT UNSIGNED AUTO_INCREMENT ,
                    `course_id` INT UNSIGNED,
                    `slides_name` VARCHAR(100) ,
                    `slides_content` longtext ,
                    `slides_order` INT NOT NULL,                    
                     PRIMARY KEY (slides_id),
                     FOREIGN KEY ( `course_id` ) REFERENCES $studi_courses (`course_id`))";
                     $wpdb->query($sql1);}
					 
  $table_name = $studi_categ_cours;
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                     $sql3 = "CREATE TABLE $table_name (
                    `course_id` INT UNSIGNED,
                    `cat_id` INT UNSIGNED,                 
                     PRIMARY KEY (course_id,cat_id),
                     FOREIGN KEY ( `course_id` ) REFERENCES $studi_courses (`course_id`),
                     FOREIGN KEY ( `cat_id` ) REFERENCES $studi_category (`cat_id`))";
                     $wpdb->query($sql3);}
					 
 /* $table_name = $studi_quiz;
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                    $sql4 = "CREATE TABLE $table_name (
                    `quiz_id` INT UNSIGNED AUTO_INCREMENT ,
                    `name` VARCHAR(100) UNIQUE ,
                    `duration` INT NOT NULL,
                    `author` VARCHAR(30),
                    `quiz_des` longtext ,
					 `quiz_picture` text,
                    `shortcode` VARCHAR(40),
                    `nbre_quest` INT,					
                     PRIMARY KEY (quiz_id))";
                    $wpdb->query($sql4);}

  $table_name = $studi_categ_quiz;
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                     $sql5 = "CREATE TABLE $table_name (
                    `quiz_id` INT UNSIGNED,
                    `cat_id` INT UNSIGNED,                 
                     PRIMARY KEY (quiz_id,cat_id),
                     FOREIGN KEY ( `quiz_id` ) REFERENCES $studi_quiz (`quiz_id`),
                     FOREIGN KEY ( `cat_id` ) REFERENCES $studi_category (`cat_id`))";
                     $wpdb->query($sql5);}
					 
  $table_name = $studi_question;
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                     $sql6 = "CREATE TABLE $table_name (
                    `quest_id` INT UNSIGNED AUTO_INCREMENT,
                    `quiz_id` INT UNSIGNED,
                    `quest_text` longtext ,
                    `quest_order` INT NOT NULL,
                    `reponse_nbre` INT, 					
                    `quest_picture` longtext ,					
                     PRIMARY KEY (quest_id),
                     FOREIGN KEY ( `quiz_id` ) REFERENCES $studi_quiz (`quiz_id`))";
                     $wpdb->query($sql6);}
					 
  $table_name = $studi_reponses;
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                     $sql7 = "CREATE TABLE $table_name (
                    `reponse_id` INT UNSIGNED AUTO_INCREMENT,
                    `quest_id` INT UNSIGNED,
                    `reponse_text` longtext ,
                    `type` VARCHAR(10),	
                    `reponse_order` INT NOT NULL,                    					
                     PRIMARY KEY (reponse_id),
                     FOREIGN KEY ( `quest_id` ) REFERENCES $studi_question (`quest_id`))";
                     $wpdb->query($sql7); }*/
  add_menu_page('Cours', 'StudyPress', 'manage_options', 'id_Cours', 'sub1',plugins_url( 'studypress/images/logologo.png' ) , '30');
  add_submenu_page('id_Cours', 'All courses','All courses', 'manage_options', 'id_Cours','');
  add_submenu_page('id_Cours', 'Create a course','Create a course', 'manage_options', 'id_sub1','sub2');
  add_submenu_page('id_Cours', 'Categories','Categories', 'manage_options', 'id_sub2','sub3');
   //add_submenu_page('id_Cours', 'All Quizs','All Quizs', 'manage_options', 'id_sub3','sub4');
  //add_submenu_page('id_Cours', 'Create a quiz','Create a quiz', 'manage_options', 'id_sub4','sub5');

 }
//include_once("Quiz.php");
//include_once("quiz_shortcode.php");
include_once("cours_shortcode.php");
include_once("Course.php");
include_once("Categories.php");
?>