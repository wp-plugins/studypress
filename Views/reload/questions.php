<?php

if(isset($_POST['id_quiz'])) {


    require_once '_AutoLoadClassAjax.php';

    global $tr;


    $managerQuiz = new QuizManager();

    $v = new validation();
    $v->addSource($_POST);
    $v->AddRules(array(

        'id_quiz' => array(
            'type' => 'numeric',
            'required' => 'true',
            'min' => '1',
            'max' => '999999',
            'trim' => 'true'
        )));

    $v->run();

    if ((sizeof($v->errors)) > 0) {
        header("HTTP/1.0 400 Bad Request");

    } else {
        $quiz = $managerQuiz->getById($v->sanitized['id_quiz']);
        if($quiz) {
            $result = "";
            if (count($quiz->getQuestions())) {


                foreach ($quiz->getQuestions() as $question) : ?>

                    <li id="li-sotable" class="ui-state-default btn btn-default"
                        data-id="<?php echo  $question->getId() ?>">
                                <span class="float-left">
                                    <span class="glyphicon glyphicon-resize-vertical " aria-hidden="true"></span>
                                    <?php echo  $question->getNiceContent() ?>
                                </span>
                        <a href=""  ><span class="glyphicon glyphicon-remove float-right" id="red" aria-hidden="true" data-id="<?php echo  $question->getId() ?>" title="<?php $tr->_e("Delete"); ?>" ></span></a>
                        <a href="" data-toggle="modal" data-target="#myModal"
                           data-id="<?php echo  $question->getId() ?>"><span class="glyphicon glyphicon-pencil float-right" data-id="<?php echo  $question->getId() ?>" aria-hidden="true" title="<?php $tr->_e("Edit"); ?>"></span></a>
                    </li>
                <?php
                endforeach;
            } else {
                echo "<i>" . $tr->__('No questions...') . "</i>";
            }


        }
    }
}