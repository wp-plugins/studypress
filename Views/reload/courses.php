<?php

require_once '_AutoLoadClassAjax.php';

global $tr;

$managerCourse = new CourseManager();


$confirm = "onclick='return confirm(\"". $tr->__("Do you want to delete this / these Course(s)?") ."\")'";


?>


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
            <td><input type='checkbox' name="id[]" value='<?php echo  $row->getId() ?>'/></td>
            <td> <a class="update" href="" data-toggle="modal" data-target="#myModal" data-id="<?php echo  $row->getId() ?>"><?php echo  $row->getName() ?> </a></td>
            <td> <?php echo  $row->getDescription() ?></td>
            <td> <?php echo  $row->getStringCategories() ?></td>
            <td> <?php echo  $row->getStringAuthors() ?></td>
            <td> <?php echo  $row->getNbreLessons() ?></td>
            <td> <?php echo  $row->getNbrequizs() ?></td>
        </tr>

    <?php
    }


    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="7">
            <button type="submit" name="remove" <?php echo  $confirm ?> class="btn btn-danger"><?php $tr->_e('Delete'); ?></button>
        </td>
    </tr>
    </tfoot>
    <?php
    }
    ?>

</table>