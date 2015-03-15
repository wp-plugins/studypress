<?php

if(isset($_POST['type']) )
{
    require_once '_AutoLoadClassAjax.php';

    $tr = SpTranslate::getInstance();

    $managerCourse = new CourseManager();

    if(($_POST['type']==="get-course-ajax")) {


        if (isset($_POST['courseId']) && !empty($_POST['courseId'])) {



            $v = new validation();
            $v->addSource($_POST);
            $v->addRule('courseId', 'numeric', true, 1, 99999, true);
            $v->run();


            if ((sizeof($v->errors)) > 0) {
                header("HTTP/1.0 400 Bad Request");
                echo $v->getMessageErrors();
            } else {

                if ($course = $managerCourse->getById($v->sanitized['courseId'])) {
                    $result['result'] = "true";
                    $result['name'] = $course->getName();
                    $result['description'] = $course->getDescription();
                    $result['description'] = $course->getDescription();
                    $result['categories'] = $course->getCategories();
                    $result['authors'] = $course->getAuthors();

                    echo json_encode($result);

                } else {
                    $result['result'] = "false";

                    echo json_encode($result);
                }


            }
        } else {
            $result['result'] = "false";

            echo json_encode($result);
        }
    }






    if(($_POST['type']==="update-course-ajax"))
    {
        if(isset($_POST['courseId']) && isset($_POST['name']) && isset($_POST['desc']) && isset($_POST['categories']) && isset($_POST['authors']))
        {
            $v1 = new validation();
            $v2 = new validation();
            $v3 = new validation();


            $v1->addSource($_POST);
            $v2->addSource($_POST['categories']);
            $v3->addSource($_POST['authors']);


            $v1->AddRules(array(
                    'courseId' => array(
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
                        'max' => '200',
                        'trim' => true
                    ),
                    'desc' => array(
                        'type' => 'string',
                        "required" => true,
                        'min' => '0',
                        'max' => '999999',
                        'trim' => true
                    )
                )
            );

            foreach ($_POST['categories'] as $key => $value) {
                if (preg_match('/^[0-9]{1,}$/', $key)) $v2->addRule($key . "",'numeric',true,1,99999, true);
            }

            if(!count($_POST['categories'])) $v2->errors['categories'] = $tr->__("Please select at least one category");

            foreach ($_POST['authors'] as $key => $value) {
                if (preg_match('/^[0-9]{1,}$/', $key)) $v3->addRule($key . "",'numeric',true,1,99999, true);
            }

            if(!count($_POST['authors'])) $v3->errors['categories'] = $tr->__("Please select at least one author");


            $v1->run();
            $v2->run();
            $v3->run();



            if ((sizeof($v1->errors)) > 0  || (sizeof($v2->errors)) > 0  ||  (sizeof($v3->errors)) > 0) {
                header("HTTP/1.0 400 Bad Request");
                echo $v1->getMessageErrors();
                echo $v2->getMessageErrors();
                echo $v3->getMessageErrors();
            } else {
                if ($course = $managerCourse->getById($v1->sanitized['courseId'])) {
                    $course->setName($v1->sanitized['name']);
                    $course->setDescription($v1->sanitized['desc']);
                    $course->setCategories($v2->sanitized);
                    $course->setAuthors($v3->sanitized);

                    $managerCourse->update($course->getId(),$course);

                    echo "true";
                }
            }




        }
        else{
            $tr->_e("Please select at least one author");
            echo "<br/>";
            $tr->_e("Please select at least one category");
            echo "<br/>";
            $tr->_e("Please enter a valid name");
        }
    }
}
else {


    if ( !defined( 'ABSPATH' ) ) exit;


    global $tr;


    $managerCourse = new CourseManager();



    $error_course_add = "";


    $error_course_remove = "";


    if (isset($_POST['add'])) {


        if (isset($_POST['course']) && !empty($_POST['course'])) {

            $v1 = new validation();
            $v2 = new validation();


            $v1->addSource($_POST['course']);



            $v1->addRule('name', 'string', true, 1, 200, true);


            $v1->addRule('desc', 'string', true, 0, 999999, true);



            foreach ($_POST['course'] as $key => $value) {
                if (preg_match('/^[0-9]{1,}$/', $key)) $v1->addRule($key . "", 'numeric', true, 1, 200, true);
            }


            if (isset($_POST['course']['users'])) {
                $v2->addSource($_POST['course']['users']);


                foreach ($_POST['course']['users'] as $key => $value) {
                    if (preg_match('/^[0-9]{1,}$/', $key)) $v2->addRule($key . "", 'numeric', true, 1, 200, true);

                }


            }


            $v1->run();
            $v2->run();


            if (((sizeof($v1->errors)) > 0) || (sizeof($v2->errors))) {
                $error_course_add = $v1->getMessageErrors() . "<br/>";
                $error_course_add .= $v2->getMessageErrors();
            } else {
                $cats = [];
                foreach ($v1->sanitized as $key => $value) {
                    if ((preg_match('/^[0-9]{1,}$/', $key)) && (isIdCategoryWpExist($value)))
                        $cats[] = $value;
                }

                $users = [];
                foreach ($v2->sanitized as $key => $authorId) {

                    if ((preg_match('/^[0-9]{1,}$/', $key)) && (StudyPressUserWP::exist($authorId)))
                        $users[] = $authorId;
                }
                if ($cats) {
                    if ($users) {


                        $managerCourse->add(new
                        Course(array(
                                'name' => $v1->sanitized['name'],
                                'description' => $v1->sanitized['desc'],
                                'categories' => $cats,
                                'authors' => $users
                            )));



                    } else {
                        $error_course_add = $tr->__("Please select at least one author");
                    }
                } else {
                    $error_course_add = $tr->__("Please select at least one category");
                }
            }


        } else {
            $error_course_add = $tr->__("Please select a category");
        }
    }



    if (isset($_POST['remove'])) {
        if ((isset($_POST['id'])) && (!empty($_POST['id']))) {

            $v1 = new validation();
            $rules = [];

            $v1->addSource($_POST['id']);
            for ($i = 0; $i < count($_POST['id']); ++$i) {


                $rules[] = array(
                    'type' => 'numeric', "required" => true, 'min' => '0', 'max' => '10000', 'trim' => true
                );

            }

            $v1->AddRules($rules);


            $v1->run();

            foreach($v1->sanitized as $id)
            {

                if($managerCourse->hasActivities($id))
                {
                    $v1->errors['HasLesson'] = $tr->__('The course you want to remove is attached to one or more lessons. Please, first delete these lessons');
                    break;
                }

            }
            if ((sizeof($v1->errors)) > 0)
                $error_course_remove = $v1->getMessageErrors();
            else {
                foreach ($v1->sanitized as $id) {
                    $managerCourse->delete($id);

                }

            }

        } else {
            $error_course_remove = $tr->__("Please select the fields to delete");
        }

    }


    require_once __ROOT_PLUGIN__ . "Views/admin/course.view.php";
}