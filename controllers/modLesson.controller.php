<?php

if ( !defined( 'ABSPATH' ) ) exit;





wp_enqueue_media();


global $tr;

$managerLesson = new LessonManager();
$managerCourse = new CourseManager();


$lesson = null;

$error_lesson_update = "";



$error_lesson_add_slide = "";



if(isset($_GET['id']) && !empty($_GET['id']))
{

    $v = new validation();
    $v->addSource($_GET);
    $v->addRule('id','numeric',true,1,9999999,true);
    $v->run();


    if((!sizeof($v->errors))>0) {

        $lesson = $managerLesson->getById($v->sanitized['id']);


        if ($lesson) {
            if (isset($_POST['update'])) {
                //var_dump($_POST);
                $v = new validation();
                $v->addSource($_POST['lesson']);
                $v->AddRules(
                    array(
                        'id' => array(
                            'type' => 'numeric',
                            "required" => true,
                            'min' => '1',
                            'max' => '999999',
                            'trim' => true
                        ),
                        'name' => array(
                            'type' => 'string',
                            "required" => true,
                            'min' => '1',
                            'max' => '400',
                            'trim' => true
                        ),
                        'description' => array(
                            'type' => 'string',
                            "required" => true,
                            'min' => '0',
                            'max' => '999999',
                            'trim' => true
                        ),
                        'duree' => array(
                            'type' => 'numeric',
                            "required" => true,
                            'min' => '0',
                            'max' => '999999',
                            'trim' => true
                        ),
                        'pictureurl' => array(
                            'type' => 'numeric',
                            "required" => false,
                            'min' => '0',
                            'max' => '999999',
                            'trim' => true
                        ),
                        'courseId' => array(
                            'type' => 'numeric',
                            "required" => false,
                            'min' => '0',
                            'max' => '999999',
                            'trim' => true
                        )
                    ));



                $v->run();



                $notes = (isset($_POST['lesson']['note']))?json_encode($_POST['lesson']['note']):"";
                $glossaires = (isset($_POST['lesson']['glossary']))?json_encode($_POST['lesson']['glossary']):"";






                if ((sizeof($v->errors)) > 0)
                    $error_lesson_update = $v->getMessageErrors();
                else {


                        $currentUser = new StudyPressUserWP();

                        if ($managerCourse->getCoursesByAuthor($currentUser->id())) {
                            $lesson = $managerLesson->getById($v->sanitized['id']);






                            $lesson->setName($v->sanitized['name']);
                            $lesson->setCourseId($v->sanitized['courseId']);
                            $lesson->setDescription($v->sanitized['description']);
                            $lesson->setDuration($v->sanitized['duree']);
                            $lesson->setPictureUrl($v->sanitized['pictureurl']);
                            $lesson->setNote($notes);
                            $lesson->setGlossary($glossaires);


                            $managerLesson->update($v->sanitized['id'], $lesson);


                            $lesson = $managerLesson->getById($v->sanitized['id']);

                        }

                    }


                }
            }
        else{
            wp_die("Access denied !");
        }


            require_once __ROOT_PLUGIN__ . "Views/admin/modLesson.view.php";
        } else

            echo "<h3>" .$tr->__("Page not found") ." !!</h3>";







}













