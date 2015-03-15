<?php

if ( !defined( 'ABSPATH' ) ) exit;


global $tr;

function slide_presentation_quiz(Quiz $quiz,$name){
    global $tr;
    $manageCourse = new CourseManager();
    $c = $manageCourse->getById($quiz->getCourseId());
    return
        "<div class='sp-presentation-content'>
            <div>
                <h4><strong>". $tr->__("Author")."</strong>: ".$name."</h4>
                <h4><strong>".$tr->__("Course")."</strong>: ".$c->getName()."</h4>
                <h4><strong>".$tr->__("Duration")."</strong>: ".$quiz->getDuration()." min</h4>
            </div>
            <h2>".$quiz->getName()."</h2>

        </div>";



}

if($id !== null){
    $currentUser = new StudyPressUserWP();

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
        $managerQuiz = new QuizManager();

        $quiz = $managerQuiz->getById($v->sanitized['id']);
        if($quiz){

            $v = ($currentUser->isLoggedIn())?sha1($currentUser->id()):"";

            $path_json = "Public/Quiz/". $quiz->getId().$v.".json";;

            $json_file =__ROOT_PLUGIN__ . $path_json;


            $sp_user =  new StudyPressUserWP($quiz->getAuthorId());





            $sp_userName = ($sp_user->firstName() . ' ' . $sp_user->lastName());


            $sp_userLink = StudyPressUserWP::getUserPostsLink( $quiz->getAuthorId() );


            $items =array();
            $owl['items'][] = array(

                'name' => $tr->__('Presentation'),
                'content' => slide_presentation_quiz($quiz,$sp_userName),
            );






            $resultContent = "";

            $result = $managerQuiz->getResultOfQuizByUser($id,$currentUser->id()) ;
            if($result && $result->isValide() )
            {


                $class = ((int) $result->getNote()>=50)?"green":"red";
                $resultContent = "<div class='sp-result'><div class='sp-postit'><p>Vous avez obtenu:</p><strong class='" . $class ."'>" . $result->getNote() . "% </strong></div></div>";


                $i = 0;
                foreach ($result->getQuestions() as $question)
                {

                    $content = $question->getContentSlideWithErrors();
                    $name = "Question N°" . ($i+1);

                    $owl['items'][] = array(

                        'name' => $name,
                        'content' => $content,
                    );

                    $i++;

                }


            }

            else
            {

                $i = 0;
                foreach ($quiz->getQuestions() as $question)
                {

                    $content = $question->getContentSlide();
                    $name = "Question N°" . ($i+1);

                    $owl['items'][] = array(

                        'name' => $name,
                        'content' => $content,
                    );

                    $i++;

                }


                $resultContent ="<div class='sp-result'><div class='loading hide'></div><button type='button' id='sp-validate'>Valider</button> </div>";
            }







            $owl['items'][] = array(

                'name' => "Validation",
                'content' => $resultContent,
            );


            $owl['items'][] = array(

                'name' => "",
                'content' => "",
            );


            $owl['title'] = $quiz->getName();
            $owl['authorName'] = $sp_userName;
            $owl['authorImg'] = StudyPressUserWP::getAvatar($quiz->getAuthorId(),30);
            $owl['authorLink'] = StudyPressUserWP::getUserPostsLink($quiz->getAuthorId());



            $fp = fopen($json_file, 'w');
            fwrite($fp, json_encode($owl));
            fclose($fp);




            require_once __ROOT_PLUGIN__ . "Views/player/player-quiz.php";
        }
        else
            $tr->_e("The value of the identifier of the shortcode is incorrect");

    }



}

else
    $tr->_e("Please indicate the identifier of the shortcode");