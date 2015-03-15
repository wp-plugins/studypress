<?php

if ( !defined( 'ABSPATH' ) ) exit;


global $tr;


wp_register_style( "display_css_course_page", plugins_url("../css/course-page.css",__FILE__) );
wp_enqueue_style('display_css_course_page');

wp_register_style( "display_css_rating", plugins_url("../css/player/rating.css",__FILE__) );
wp_enqueue_style('display_css_rating');

?>

<style>
    #sp-rate-id
    {
        margin: 0;
        padding: 0;

    }
</style>

<?php


echo "<div class='sp-courses'><ul>";

$get_children_array = PostWP::getChildrenPost($args);

$type = "";
foreach ($get_children_array as $value):

    $managerLesson = new LessonManager();
    $managerQuiz = new QuizManager();
    $managerRate = new RateQualityManager();

    $study = $managerLesson->getLessonByPostId($value['ID']);
    $type = $tr->__("Lesson");
    $image = "lesson";

    if(!$study)
    {
        $study = $managerQuiz->getQuizByPostId($value['ID']);
        $type = $tr->__("Quiz");
        $image = "quiz";
    }

    $med_image_url = wp_get_attachment_image_src( $study->getPictureUrl(), $size='thumbnail');
    if(!$med_image_url[0])
    {
        $med_image_url[0] = __ROOT_PLUGIN__2 . "images/" . $image .".png";
    }
    
    ?>


            <li>
                    <div>


                        <div class="thumb">
                            <img src="<?= $med_image_url[0] ?>" width="150" height="150" />
                        </div>

                        <div class="details">
                            <a href="<?= $value['guid'] ?>"><?= $study->getName() ?></a>
                            <p class="description"><?=$study->getNiceDescription() ?></p>
                            <span>Rater(s) : <?= $managerRate->countRate($study->getId()) ?></span>
                            <div id="sp-rate-id" class="sp-rate-quality" data-average="<?= $managerRate->AVG($study->getId()) ?>"></div>
                        </div>

                        <p class="sp-type"><?= $type ?></p>
                    </div>
            </li>





<?php
endforeach;
echo "</ul></div>";
?>

<script src="<?= __ROOT_PLUGIN__2 . "js/jquery.js" ?>"></script>
<script src="<?= __ROOT_PLUGIN__2 . "js/jquery.rateyo.js" ?>"></script>
<script src="<?= __ROOT_PLUGIN__2 . "js/rating-function.js" ?>"></script>
<script>
    $(function () {



        $(".sp-rate-quality").each(function() {
            var item = $(this);
            item.rateYo({
                starWidth: "15px",
                rating: $(this).data("average"),
                readOnly: true
            });
        });




    });

</script>