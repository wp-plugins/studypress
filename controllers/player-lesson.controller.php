<?php

if ( !defined( 'ABSPATH' ) ) exit;

global $tr;

function slide_presentation_lesson(Lesson $lesson,$name){
    global $tr;
    $manageCourse = new CourseManager();
    $c = $manageCourse->getById($lesson->getCourseId());
    return
        "<div class='sp-presentation-content'>
            <div>
                <h4><strong>". $tr->__("Author")."</strong>: ".$name."</h4>
                <h4><strong>".$tr->__("Course")."</strong>: ".$c->getName()."</h4>
                <h4><strong>".$tr->__("Duration")."</strong>: ".$lesson->getDuration()." min</h4>
            </div>
            <h2>".$lesson->getName()."</h2>

        </div>";



}

if($id !== null){
    $v = new validation();
    $v->addSource(array('id' => $id));
    $v->AddRules(array(

        'id' => array(
            'type' => 'numeric',
            'required' => 'true',
            'min' => '1',
            'max' => '999999',
            'trim' => 'true'
        )
    ));

    $v->run();



    if ((sizeof($v->errors)) > 0) {
        $tr->_e("The value of the identifier of the shortcode is incorrect");

    } else {
        $managerLesson = new LessonManager();

        $lesson = $managerLesson->getById($v->sanitized['id']);
        if($lesson){







            $json_file =__ROOT_PLUGIN__ . "Public/Lesson/". sha1($lesson->getId()).".json";


            $sp_user =  new StudyPressUserWP($lesson->getAuthorId());


            $sp_userName = ($sp_user->firstName() . ' ' . $sp_user->lastName());

            $sp_userLink = StudyPressUserWP::getUserPostsLink( $lesson->getAuthorId() );


            $items =[];
            $owl['items'][] = array(

                'name' => $tr->__('Presentation'),
                'content' => slide_presentation_lesson($lesson,$sp_userName),
            );
            foreach ($lesson->getSlides() as $slide)
            {

                $content = $slide->content();
                $name = $slide->name();

                $owl['items'][] = array(

                    'name' => $name,
                    'content' => $content,
                );

            }

            $owl['items'][] = array(

                'name' => "",
                'content' => "",
            );


            $owl['title'] = $lesson->getName();
            $owl['authorName'] = $sp_userName;
            $owl['authorImg'] = StudyPressUserWP::getAvatar($lesson->getAuthorId(),30);
            $owl['authorLink'] = StudyPressUserWP::getUserPostsLink($lesson->getAuthorId());



            $fp = fopen($json_file, 'w');
            fwrite($fp, json_encode($owl));
            fclose($fp);




            require_once __ROOT_PLUGIN__ . "Views/player/player-lesson.php";
        }
        else
            $tr->_e("The value of the identifier of the shortcode is incorrect");

    }

}

else
    $tr->_e("Please indicate the identifier of the shortcode");