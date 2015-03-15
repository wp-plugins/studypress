<?php

global $tr;

if(isset($_POST['type']) && ($_POST['type'] === "post")) {

    if (isset($_POST['id'])) {


        require_once '_AutoLoadClassAjax.php';


        $managerQuiz = new QuizManager();



        $v = new validation();
        $v->addSource($_POST);
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
            header("HTTP/1.0 400 Bad Request");

        } else {

            $quiz = $managerQuiz->getById($v->sanitized['id']);
            if($quiz){


                $btn_post = "<button type='button' id='post-quiz' data-id='"  . $quiz->getId(). "' class='btn btn-primary'>" . $tr->__("Publish") . "</button>";

                $btn_remove = "<button type='button' id='post-quiz' data-id='"  . $quiz->getId(). "' class='btn btn-danger'>" . $tr->__("Remove") . "</button>";


                if($quiz->getPostId() === 0) {




                    $managerQuiz->post($quiz);


                    $result['result'] = "true";
                    $result['value'] = $btn_remove;

                    echo json_encode($result);
                }

                else
                {
                    $managerQuiz->unpost($quiz);


                    $result['result'] = "true";
                    $result['value'] = $btn_post;

                    echo json_encode($result);

                }

            }

        }
    }
}