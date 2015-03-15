<?php

require_once __ROOT_PLUGIN__ ."Views/includeCSS.php";

global $tr;


$confirm = "onclick='return confirm(\"". $tr->__("Do you want to delete this slide?") ."\")'";

?>
<style>
    .loading{
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.9;
        z-index: 1060;
        background: url('<?php echo  __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%,#FFF;
    }
    .float-left{
        float: left;
    }
    .float-right{
        float: right;
        margin: 0 0 0 8px;

    }
    .ui-state-default{
        overflow: hidden;
        position: relative;
    }

    #li-sotable a {
        outline: none;
    }
    #li-sotable,
    #li-non-sortable{
        display: block;
        width: 100%;
        cursor: auto;
        text-align: left;
        margin: 5px 0;

    }

    #li-sotable{
        cursor: move;
        background: #EEE;
    }


    #li-non-sortable:hover,
    #li-non-sortable:active{
        background: #FFFFFF;

    }


    #sortable-slide{
        min-height: 200px;
    }
    .ui-sortable-placeholder {
        margin: 5px 0;
        border: 2px dashed #CCCCCC;
        height: 34px;
        border-radius: 2px;
        width: 100%;
        background: #EFEFEF;
    }
    .sp-cat{
        width: 100%;
        max-height : 200px;
        overflow: auto;
        padding: 0 10px;
    }
</style>

<h1><?php $tr->_e("Edit the lesson"); ?></h1>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h3><?php echo  $lesson->getName() ?></h3>

            <form action="" method="post" enctype="multipart/form-data" >
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="alert alert-danger" role="alert"
                        <?php echo  ($error_lesson_update=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_lesson_update ?> </div>
                    <div class="form-group">
                        <label for="name"><?php $tr->_e("The name of the lesson"); ?>* :  </label>
                        <input type="text" autocomplete="off" class="form-control" id="name" name="lesson[name]" required="required"
                               value="<?php echo  $lesson->getName() ?>"/>
                    </div>

                    <div class="form-group">
                        <label for="duree"><?php $tr->_e("Duration (Min)"); ?></label>
                        <input type="number" class="form-control" id="duree" name="lesson[duree]"
                               value="<?php echo  $lesson->getDuration() ?>"/>
                    </div>

                    <div class="form-group">
                        <label for="description"><?php $tr->_e("Description"); ?> </label>
                        <textarea  class="form-control" id="description" name="lesson[description]"><?php echo   trim($lesson->getDescription()) ?></textarea>
                    </div>


                    <div class="form-group">
                        <label for="picture"><?php $tr->_e("Associate an image"); ?></label>
                        <div>
                            <a href="#" class="button select-picture"><?php $tr->_e("Browse"); ?></a>
                            <input type="text" id="picture" value="<?php echo  wp_get_attachment_url( $lesson->getPictureUrl() )?>"  size="45" tabindex="1" autocomplete="off" disabled/>
                            <input type="hidden" name="lesson[pictureurl]" value="<?php echo  $lesson->getPictureUrl()?>"/>

                        </div>




                    </div>
                    <div class="form-group">
                        <label for="courseId"><?php $tr->_e("Associate to a course"); ?>*</label>
                        <select name="lesson[courseId]" id="courseId" class="form-control">
                            <?php
                            $userCourse = new StudyPressUserWP($lesson->getAuthorId());
                            $courses = $managerCourse->getCoursesByAuthor($userCourse->id());
                            foreach ($courses as $course) {
                                $selected = ($course->getId()===$lesson->getCourseId())?"selected":"";
                                echo "<option value='".$course->getId()."' " .$selected.">".$course->getName()."</option>";
                            }

                            ?>

                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php $tr->_e("Notes"); ?></div>
                            <div class="panel-heading">
                                <div class="form-group">
                                    <input type="text" autocomplete="off" class="form-control" id="note" name="note" placeholder="Note..." />
                                </div>
                                <div class="form-group">
                                    <button id="add-new-note" type="button" class="btn btn-success"><?php $tr->_e("Add"); ?></button>
                                </div>
                            </div>
                            <div class="panel-body">

                                <ul id="sortable-note">

                                    <?php

                                    foreach ($lesson->getNote() as $note) : ?>
                                        <li id='li-non-sortable' class='ui-state-default btn btn-default sp-note'> <span class='float-left' title="<?php echo  str_replace('"',' ',$note)?>"><?php echo  substr($note,0,35)?>...</span><a href=''><span class='glyphicon glyphicon-remove float-right delete-note' id="red" aria-hidden='true' title='Supprimer'></span></a><input type='hidden' name='lesson[note][]' value="<?php echo  str_replace('"',' ',$note)?>" /></li>
                                    <?php endforeach;  ?>


                                </ul>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php $tr->_e("Glossary"); ?></div>
                            <div class="panel-heading">
                                <div class="form-group form-inline">
                                    <input type="text" class="form-control" autocomplete="off" id="glossary" name="glossary-name" placeholder="<?php $tr->_e("Term"); ?>" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" autocomplete="off" id="glossary" name="glossary-desc" placeholder="<?php $tr->_e("Description"); ?>" />
                                </div>
                                <div class="form-group">
                                    <button type="button" id="add-new-glossary" class="btn btn-success"><?php _e("Add"); ?></button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <ul id="sortable-glossary">
                                    <?php
                                    $g = $lesson->getGlossary();
                                    for ($i=0;$i<count($g->name);$i++) : ?>
                                        <li id='li-non-sortable' class='ui-state-default btn btn-default sp-glossary'>
                                            <span class='float-left' title="<?php echo  str_replace('"',' ', $g->name[$i]. " : ".$g->desc[$i])?>"><?php echo  substr("<b>" . $g->name[$i]. "</b>" .": ".$g->desc[$i],0,35)?>...</span>
                                            <a href=''><span class='glyphicon glyphicon-remove float-right delete-glossary' id="red" aria-hidden='true' title='<?php $tr->_e("Delete"); ?>'></span></a>
                                            <input type='hidden' name='lesson[glossary][name][]' value="<?php echo  str_replace('"',' ',$g->name[$i])?>" />
                                            <input type='hidden' name='lesson[glossary][desc][]' value="<?php echo  str_replace('"',' ',$g->desc[$i])?>" />
                                        </li>
                                    <?php endfor;  ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="hidden" name="lesson[id]" value="<?php echo  $_GET['id'] ?>" />
                    <button type="submit" name="update" class="btn btn-primary center-block"><?php $tr->_e("Save changes"); ?></button>
                </div>
        </div>
        </form>

    </div>




    <div class="col-md-4">
        <h3><?php $tr->_e("The slides"); ?></h3>
        <form method="post" action="">
            <div class="panel panel-default">

                <div class="panel-body">
                    <div class="alert alert-danger" role="alert" <?php echo  ($error_lesson_add_slide=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_lesson_add_slide ?> </div>

                <ul id="sortable-slide">

                </ul>

            </div>
            <div class="panel-footer">
                <button type="button" name="add-new-slide" id="add-new-slide" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><?php $tr->_e("Create a new slide"); ?></button>
                <button type="button" name="update-position" class="btn btn-default" id="update-order" data-loading-text="<?php $tr->_e("Loading..."); ?>" disabled><?php $tr->_e("Save"); ?></button>
            </div>


    </div>
    </form>

</div>
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="loading hide"></div>

                <div class="alert alert-danger alert-dismissible hide" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p></p>
                </div>

                <div class="form-group">
                    <label for="name"><?php $tr->_e("Name"); ?></label>
                    <input type="text" class="form-control" id="name" name="name" required="required" />
                </div>


                <div class="form-group">
                    <label for="name"><?php $tr->_e("Content"); ?></label>
                    <?php wp_editor("", $id ='content-slide') ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php $tr->_e("Close"); ?></button>
                <button type="button" data-loading-text="<?php $tr->_e("Loading..."); ?>" class="btn btn-primary"><?php $tr->_e("Save"); ?></button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo  __ROOT_PLUGIN__2 . "js/jquery-ui.min.js" ?>"></script>
<script src="<?php echo  __ROOT_PLUGIN__2 . "js/bootstrap.min.js" ?>"></script>




<script>
(function($) {
    $(document).ready(function(){
        function addslashes(str) {
            return str.replace(/\"/g," ");
        }

        reload_slides();

        var type_modal_sp = "add";
        var modal = $('#myModal');
        var id_slide;
        var alert = modal.find(".alert");


        $( "#sortable-slide" ).sortable({
            placeholder: "ui-sortable-placeholder"
        });
        $( "#sortable-slide" ).disableSelection();
        $( "#sortable-slide" ).on( "sortupdate", function( event, ui ) {
            $("#update-order").prop("disabled", false);

        } );


        function reinitialiserModal(){

            alert.find("p").html("");
            alert.addClass("hide");
            modal.find('input[name=name]').val("");
            tinymce.activeEditor.setContent("");
        }

        $("#add-new-slide").on("click",function(){

            type_modal_sp ="add";
            modal.find('.modal-title').text("<?php $tr->_e("Create a new slide"); ?>");

            reinitialiserModal();


        });



        function trimStr(str) {
            return str.replace(/^\s+|\s+$/gm,'');
        }





        $('#myModal .btn-primary').on("click",function(){

            $('.loading').removeClass("hide");

            alert.find("p").html("");
            alert.addClass("hide");

            var btn = $(this).attr('disabled','disabled');


            var name = modal.find('input[name=name]').val();
            var content = tinymce.activeEditor.getContent();
            var id_lesson = $('input[name="lesson[id]"]').val();
            $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/slide.controller.php",
                {
                    type : type_modal_sp+"-slide",
                    name: name,
                    content: content,
                    id_lesson : id_lesson,
                    id_slide : id_slide
                }

                ,function(data){

                    if(trimStr(data) === "true") {
                        console.log(data);
                        reload_slides();
                        modal.modal('hide');



                    }
                    else{

                        alert.removeClass("hide");
                        alert.find("p").append(data);
                    }

                }).error(function(data){

                    alert.removeClass("hide");
                    alert.find("p").append(data.responseText);


                }).always(function(){
                    btn.removeAttr('disabled');
                    $('.loading').addClass("hide");
                });


        });

        $("#update-order").on("click", function ()
        {
            var $btn = $(this);
            $btn.attr('disabled','disabled');
            var order =[];
            $("#sortable-slide li" ).each(function( index,element ) {
                order[index]  = $(element).data("id");
                console.log(index);
            });
            $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/slide.controller.php",
                {
                    type: "order-slide",
                    order: order

                }

                , function (data) {
                    console.log(data);
                    if(trimStr(data) === "true") {
                        reload_slides();
                    }
                }
            ).error(function(){

                }).always(function(){
                    $btn.removeAttr('disabled');


                });

            return false;

        });


        $('#sortable-slide').on("click",".glyphicon-remove",function(){
            if(confirm("<?php echo  "Do you want to delete this slide?" ?>")) {
                var id_slide = $(this).data("id");
                var id_lesson = $('input[name="lesson[id]"]').val();

                $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/slide.controller.php",
                    {
                        type: "remove-slide",
                        id_slide: id_slide,
                        id_lesson: id_lesson
                    }

                    , function (data) {
                        console.log(data);
                        if(trimStr(data) === "true") {
                            reload_slides();
                        }
                    }
                ).error(function(){

                    })
            }
            return false;

        });






        $('#sortable-slide').on("click",".glyphicon-pencil",function(){
            type_modal_sp = "update";
            modal.find('.modal-title').text("<?php _e("Edit slide"); ?>");
            reinitialiserModal();
            id_slide = $(this).data("id");
            var id_lesson = $('input[name="lesson[id]"]').val();
            getContentSlide(id_slide,id_lesson);

        });


        function getContentSlide(id_slide,id_lesson){
            $(".loading").removeClass('hide');

            $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/slide.controller.php",
                {
                    type: "get-slide",
                    id_slide: id_slide,
                    id_lesson: id_lesson
                }

                , function (data) {

                    if(trimStr(data.result) === "true") {
                        modal.find('input[name=name]').val(data.nameSlide);
                        tinymce.activeEditor.setContent(data.contentSlide);

                    }
                }
                ,'json').error(function(){

                }).always(function(){
                    $(".loading").addClass('hide');
                });
        }


        function reload_slides(){
            var ul =$("#sortable-slide");
            ul.css('background',"url('<?php echo  __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%");
            ul.html("");

            var id_lesson = $('input[name="lesson[id]"]').val();

            $.post("<?php echo  __ROOT_PLUGIN__2 ?>Views/reload/slides.php",
                {
                    id_lesson: id_lesson
                }

                ,function(data){

                    ul.html(data);

                }


            ).error(function(data){



                }).always(function(){
                    ul.css('background',"#FFF");
                    $("#update-order").prop("disabled", true);
                });
        };


        $("#add-new-note").on("click",function(){
            var note = $("input[name=note]").val();
            var id_lesson = $('input[name="lesson[id]"]').val();

            if(trimStr(note) != "")
            {
                $("#sortable-note").append("<li id='li-non-sortable' class='ui-state-default btn btn-default sp-note'> <span class='float-left' title=\""+addslashes(note)+"\">"+note.substring(0,35)+"...</span><a href=''><span class='glyphicon glyphicon-remove float-right delete-note' id='red'  aria-hidden='true'></span></a>" +
                "<input type='hidden' name='lesson[note][]' value=\""+addslashes(note)+"\"/></li>");

                $("input[name=note]").val("");
            }

        });

        $("#sortable-note").on("click","li .delete-note",function(e){
            e.preventDefault();
            if(confirm("<?php echo  $tr->__("Do you want to delete this note ?") ?>"))
            {
                $(this).parent().parent().remove();
            }


        });


        $("#add-new-glossary").on("click",function(){
            var name = $("input[name=glossary-name]").val();
            var desc = $("input[name=glossary-desc]").val();

            var id_lesson = $('input[name="lesson[id]"]').val();

            if((trimStr(name) != "") &&(trimStr(desc) != ""))
            {
                $("#sortable-glossary").append("<li id='li-non-sortable' class='ui-state-default btn btn-default sp-glossary'> " +
                "               <span class='float-left' title=\""+addslashes(name+" : "+desc)+"\">"+("<b>"+name+"</b>"+" : "+desc).substr(0,35)+"...</span>" +
                "<a href=''><span class='glyphicon glyphicon-remove float-right delete-glossary' id='red'  aria-hidden='true'></span></a>" +
                "<input type='hidden' name='lesson[glossary][name][]' value=\""+addslashes(name)+"\" />" +
                "<input type='hidden' name='lesson[glossary][desc][]' value=\""+addslashes(desc)+"\" /> </li>");

                $("input[name=glossary-name]").val("");
                $("input[name=glossary-desc]").val("");
            }

        });

        $("#sortable-glossary").on("click","li .delete-glossary",function(e){
            e.preventDefault();
            if(confirm("<?php echo  $tr->__("Do you want to delete this glossary ?") ?>"))
            {
                $(this).parent().parent().remove();
            }


        });

        $('.select-picture').click(function(e){
            var $el=$(this).parent();
            e.preventDefault();
            console.log('test');
            var uploader=wp.media({
                title : '<?php echo   $tr->__('Upload an image') ?>',
                button : {
                    text: '<?php echo  $tr->__('Select an image') ?>'
                },
                library :{
                    type : 'image'
                },
                multiple: false
            })
                .on('select',function(){
                    var selection=uploader.state().get('selection');
                    var attachment=selection.first().toJSON();
                    $("input[name='lesson[pictureurl]']").val(attachment.id);
                    $(document.getElementById('picture'),$el).val(attachment.url);

                })
                .open();
        })
    })







})(jQuery);
</script>
