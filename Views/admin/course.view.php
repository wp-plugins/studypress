<?php

global $tr;

require_once  __ROOT_PLUGIN__ ."Views/includeCSS.php";



$confirm = "onclick='return confirm(\"". $tr->__("Do you want to delete this / these Course(s)?") ."\")'";



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
        background: url('<?php echo __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%,#FFF;
    }

    .sp-cat{
        width: 100%;
        height : 140px;
        overflow: auto;
        padding: 0 10px;
        box-shadow: inset 0 0 3px #777;
        border-radius: 5px;
    }

</style>

<h1>
    <?php $tr->_e("Course"); ?>
    <button type="button" id="addNewCourse" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><?php $tr->_e("Add New"); ?></button>

</h1>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">
            <h3><?php $tr->_e('All courses'); ?></h3>
            <div class="alert alert-danger" role="alert" <?php echo ($error_course_remove=='')?'style=\'display:none\'':'' ?>"> <?php echo $error_course_remove ?> </div>
            <form action="" method="post" id="sp-reload">

            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?php $tr->_e('Name'); ?></th>
                    <th><?php $tr->_e('Description'); ?></th>
                    <th><?php $tr->_e('Categories'); ?></th>
                    <th><?php $tr->_e('Authors'); ?></th>
                    <th><?php $tr->_e('Lessons'); ?></th>
                    <th><?php $tr->_e('Quiz'); ?></th>
                </tr>
                </thead>
                <tbody>
                
                <?php
                $__courses = $managerCourse->getAll();
                if(empty($__courses))
                {
                    echo "<tr><td colspan='7'>". $tr->__('No Courses') ."</td></tr>";
                }
                else {
                foreach ($__courses as $row) {
                    ?>
                    <tr>
                        <td><input type='checkbox' name="id[]" value='<?php echo $row->getId() ?>'/></td>
                        <td> <a class="update" href="" data-toggle="modal" data-target="#myModal" data-id="<?php echo $row->getId() ?>"><?php echo $row->getName() ?> </a></td>
                        <td> <?php echo $row->getDescription() ?></td>
                        <td> <?php echo $row->getStringCategories() ?></td>
                        <td> <?php echo $row->getStringAuthors() ?></td>
                        <td> <?php echo $row->getNbreLessons() ?></td>
                        <td> <?php echo $row->getNbrequizs() ?></td>
                    </tr>

                    <?php
                }


                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <button type="submit" name="remove" <?php echo $confirm ?> class="btn btn-danger"><?php $tr->_e('Delete'); ?></button>
                    </td>
                </tr>
                </tfoot>
                <?php
                }
                ?>

            </table>
            </form>

        </div>

    </div>


<form action="" method="post">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php $tr->_e("Add a new course"); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="loading hide"></div>

                <div class="alert alert-danger" role="alert" <?php echo ($error_course_add=='')?'style=\'display:none\'':'' ?>"> <?php echo $error_course_add ?> </div>

                <div class="alert alert-danger alert-dismissible hide" role="alert">
                    <p></p>
                </div>

                <div class="form-group">
                    <input type="hidden" name="id" value=""/>
                    <label for="name"><?php $tr->_e("Name"); ?></label>
                    <input type="text" class="form-control" id="name" name="course[name]" required="required" />
                </div>

                <div class="form-group">
                    <label for="desc"><?php $tr->_e("Description"); ?></label>
                    <textarea class="form-control" rows="3" id="desc" name="course[desc]"></textarea>

                </div>


                <div class="form-group">
                    <label for=""><?php $tr->_e("Categories"); ?>*</label>
                    <?php
                    $all_cat = get_categories(
                        array(
                            'type'                     => 'post',
                            'child_of'                 => 0,
                            'parent'                   => '',
                            'orderby'                  => 'name',
                            'order'                    => 'ASC',
                            'hide_empty'               => 0,
                            'hierarchical'             => 1,
                            'exclude'                  => '',
                            'include'                  => '',
                            'number'                   => '',
                            'taxonomy'                 => 'category',
                            'pad_counts'               => false

                        )
                    );
                    echo "<div class='sp-cat'>";
                    $droite = "";
                    $gauche = "";
                    $content ="";
                    $i = 0;
                    foreach ($all_cat as $c) {

                        $content  = "<div class='checkbox'>";
                        $content .= "<label>";
                        $content .= "<input type='checkbox' value='".$c->cat_ID."' ";
                        $content .= "name='course[]' /> ";
                        $content .= $c->name;
                        $content .= "</label>" ." </div> ";

                        if($i % 2) $gauche .= $content;
                        else $droite .= $content;
                        $i++;
                    }
                    echo "<div class'row'>";
                    echo "<div class='col-md-6'>" . $droite . "</div>";
                    echo "<div class='col-md-6'>" . $gauche . "</div>";
                    echo "</div>";
                    echo "</div>";

                    ?>
                </div>


                <div class="form-group">
                    <label for=""><?php $tr->_e("Authors"); ?>*</label>
                    <?php
                    $args = array(
                        'blog_id'      => $GLOBALS['blog_id'],
                        'role'         => '',
                        'meta_key'     => '',
                        'meta_value'   => '',
                        'meta_compare' => '',
                        'meta_query'   => array(),
                        'include'      => array(),
                        'exclude'      => array(),
                        'orderby'      => 'login',
                        'order'        => 'ASC',
                        'offset'       => '',
                        'search'       => '',
                        'number'       => '',
                        'count_total'  => false,
                        'fields'       => 'all',
                        'who'          => 'authors'
                    );
                    $blogusers = get_users($args);

                    echo "<div class='sp-cat'>";
                    $droite = "";
                    $gauche = "";
                    $content ="";
                    $i = 0;
                    foreach ( $blogusers as $user ) {
                        $content  =  "<div class='checkbox'>";
                        $content .= "<label>";
                        $content .= "<input type='checkbox' value='".$user->ID."' ";
                        $content .= "name='course[users][]' /> ";
                        $content .= $user->display_name ;
                        $content .= "</label>" ." </div> \n";
                        if($i % 2) $gauche .= $content;
                        else $droite .= $content;
                        $i++;
                    }
                    echo "<div class'row'>";
                    echo "<div class='col-md-6'>" . $droite . "</div>";
                    echo "<div class='col-md-6'>" . $gauche . "</div>";
                    echo "</div>";
                    echo "</div>";
                    ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php $tr->_e("Close")?></button>
                <button type="submit" id="validate" name="add" class="btn btn-primary"><?php $tr->_e("Validate"); ?></button>
            </div>
        </div>
    </div>
</div>
</form>






<script src="<?php echo __ROOT_PLUGIN__2 . "js/bootstrap.min.js" ?>"></script>


<script>

    (function($) {
        $(document).ready(function() {


            <?php
            if($error_course_add !== "")
                echo "$('#myModal').modal('show') ";

            ?>

            var type_modal_sp = "add";
            var trSelected;
            var modal = $("#myModal");
            var alert = modal.find(".alert");


            function reinitialiserModal() {
                //Reinitialiser
                alert.find("p").html("");
                alert.addClass("hide");
                modal.find("input[name='course[name]']").val("");
                modal.find("textarea[name='course[desc]']").val("");
                modal.find('input:checkbox').removeAttr('checked');
            }


            function trimStr(str) {
                return str.replace(/^\s+|\s+$/gm, '');
            }


            $("#sp-reload").on("click",".update", function (e) {
                type_modal_sp = "update";
                e.preventDefault();
                reinitialiserModal();
                trSelected = $(this);
                console.log(trSelected);
                var id = $(this).data('id');

                getContentCourse(id);


            });


            function getContentCourse(courseId) {
                $(".loading").removeClass('hide');

                $.post("<?php echo __ROOT_PLUGIN__2 ?>controllers/course.controller.php",
                    {
                        type: "get-course-ajax",
                        courseId: courseId
                    }

                    , function (data) {

                        if (trimStr(data.result) === "true") {
                            modal.find("input[name='course[name]']").val(data.name);
                            modal.find("textarea[name='course[desc]']").val(data.description);
                            for (i = 0; i < data.categories.length; i++) {

                                modal.find("input:checkbox[name='course[]'][value=" + data.categories[i] + "]").prop("checked", "true");
                            }

                            for (i = 0; i < data.authors.length; i++) {

                                modal.find("input:checkbox[name='course[users][]'][value=" + data.authors[i] + "]").prop("checked", "true");
                            }


                        }
                        else {
                            alert.find("p").html("Une erreur s'est produite !");
                            alert.removeClass("hide");
                        }
                    }, 'json').error(function () {

                        alert.find("p").html("Une erreur s'est produite !");
                        alert.removeClass("hide");

                    }).always(function () {
                        $(".loading").addClass('hide');
                    });
            }


            $("#addNewCourse").on("click", function () {
                reinitialiserModal();
                type_modal_sp = "add";
            });


            $("#validate").on("click", function (e) {
                if (type_modal_sp === "add") {
                    return true;
                }
                else {
                    $(".loading").removeClass('hide');

                    e.preventDefault();
                    var id = trSelected.data('id');
                    var name = modal.find("input[name='course[name]']").val();
                    var desc = modal.find("textarea[name='course[desc]']").val();


                    var categories = $("input:checkbox[name='course[]']:checked").map(function () {
                        return this.value;
                    }).get();


                    var authors = $("input:checkbox[name='course[users][]']:checked").map(function () {
                        return this.value;
                    }).get();

                    $.post("<?php echo __ROOT_PLUGIN__2 ?>controllers/course.controller.php",
                        {
                            type: "update-course-ajax",
                            courseId: id,
                            name: name,
                            desc: desc,
                            categories: categories,
                            authors: authors

                        }
                        , function (data) {

                            if (trimStr(data) === "true") {
                                modal.modal('hide');
                                $.ajax({
                                    url: "<?php echo __ROOT_PLUGIN__2 ?>Views/reload/courses.php",
                                    context: document.body,
                                    success: function (s, x) {
                                        $("#sp-reload").html(s);
                                    }
                                });

                            }
                            else {
                                alert.find("p").html(data);
                                alert.removeClass("hide");
                            }

                        }
                    ).error(function (data) {

                            alert.find("p").html(data.responseText);
                            alert.removeClass("hide");

                        }).always(function () {
                            $(".loading").addClass('hide');
                        });


                }
            })
        })

    })(jQuery);
</script>
