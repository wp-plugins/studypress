<?php

global $tr;

require_once  __ROOT_PLUGIN__ ."Views/includeCSS.php";



?>

<input type="hidden" name="quiz[id]" value="<?= $_GET['id'] ?>"/>

<style>

    .modal-body
    {
        background: #FEFEFE;
    }

    .sp-qcm
    {
        background: #FFF;
        box-shadow: 0px 0px 3px #444;
        margin-bottom: 20px;
        padding: 5px;
    }

    .loading{
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.9;
        z-index: 1060;
        background: url('<?= __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%,#FFF;
    }

    .red,
    .green
    {
        font-weight: bold;
        font-size: 1.2em;
    }
    .red
    {
        color: #ac2925;

    }

    .green
    {
        color: #3e8f3e;
    }

    .sp-qcm li label
    {
        font-size: 1.1em;
        vertical-align: middle;
    }

    .sp-qcm ul > li .img-correct
    {
        position: absolute;
        right: 20px;

    }

    .sp-qcm li.false
    {
        box-shadow:inset 0px 0px 3px #E92836;
    }
    .sp-qcm li.true
    {
        box-shadow:inset 0px 0px 3px #009900;
    }


    .sp-qcm > p
    {
        font-size: 1.3em;

    }

    .sp-qcm ul
    {
        list-style-type: none;
    }

    .sp-qcm ul > li
    {
        padding: 10px;
        border-radius: 5px;
        margin-top: 5px;
        background: #EEE;
    }

    .table td:not(:first-child),
    .table th:not(:first-child)
    {
        text-align: center;
    }

</style>

<h1>
    <?php $tr->_e("Quiz Results"); ?>


</h1>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">

            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th><?php $tr->_e('User'); ?></th>
                    <th><?php $tr->_e('Percentage'); ?></th>
                    <th><?php $tr->_e('Ratio'); ?></th>
                    <th><?php $tr->_e('Date'); ?></th>
                    <th><?php $tr->_e('Details'); ?></th>
                </tr>
                </thead>
                <tbody>

                <?php
                $__results = $managerResult->getResultByQuiz($_GET['id']);
                if(empty($__results))
                {
                    echo "<tr><td colspan='7'>". $tr->__('No results') ."</td></tr>";
                }
                else {
                foreach ($__results as $row) {
                    $user = new StudyPressUserWP($row->getUserId());
                    ?>
                    <tr>
                        <td> <?= $user->displayName() ?> </a></td>
                        <td class="<?= ($row->getNote()>50)?"green":"red"?>"> <?= $row->getNote() ?>%</td>
                        <td> <?= $row->getNbrCorrectResponse() ."/". $row->getNbrQuestions() ?></td>
                        <td> <?= $row->getDateResult() ?></td>
                        <td>
                            <a id="get-responses" data-id ="<?= $user->id() ?>" href="#">
                                <span class="glyphicon glyphicon-new-window"  data-toggle="modal" data-target="#myModal" aria-hidden="true" title="Afficher"></span>
                            </a>
                        </td>
                    </tr>

                <?php
                }


                ?>
                </tbody>

                <?php
                }
                ?>

            </table>

    </div>

</div>
</div>


<!-- Modal d'ajout de leçon  -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loading hide"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-user" id="myModalLabel"></h4>
                <h4 class="modal-pourcentage" ></h4>
                <h4 class="modal-quiz" ></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= $tr->__("Close") ?></button>

            </div>
        </div>
    </div>
</div>





<script src="<?= __ROOT_PLUGIN__2 . "js/bootstrap.min.js" ?>"></script>
<script>

    (function($) {
        $(document).ready(function () {


            var modal = $('#myModal');
            var modalBody = modal.find('.modal-body');
            var modalUser = modal.find('.modal-user');
            var modalPourcentage = modal.find('.modal-pourcentage');
            var modalQuiz = modal.find('.modal-quiz');


            function reinitialiserModal() {

                modalBody.html("");
                modalUser.html("");
                modalPourcentage.html("");
                modalQuiz.html("");
            }


            $('.table').on("click", '#get-responses', function (e) {
                e.preventDefault();
                reinitialiserModal();
                id_slide = $(this).data("id");
                var quizId = $('input[name="quiz[id]"]').val();
                var userId = $(this).data("id");

                getResponses(quizId, userId);

            });


            function getResponses(quizId, userId) {
                $(".loading").removeClass('hide');

                $.post("<?= __ROOT_PLUGIN__2 ?>controllers/resultQuiz.controller.php",
                    {
                        type: "get-responses",
                        quizId: quizId,
                        userId: userId
                    }

                    , function (data) {

                        if (trimStr(data.result) === "true") {
                            modalQuiz.html(data.quiz);
                            modalPourcentage.html(data.pourcentage);
                            modalUser.html(data.user);
                            modalBody.html(data.body);

                        }


                    }, 'json').error(function () {

                        modalBody.html("Error !! Try Again").addClass("red");

                    }).always(function () {
                        $(".loading").addClass('hide');
                    });
            }


            function trimStr(str) {
                return str.replace(/^\s+|\s+$/gm, '');
            }


        })
    })(jQuery);


</script>