<?php


$access = new AccessData();
$user = new StudyPressUserWP();

$tableCat = StudyPressDB::getPrefix().'studi_category';
$tableCourse = StudyPressDB::getPrefix().'studi_course';
$tableSlides = StudyPressDB::getPrefix().'studi_slides';
$tableCategCourse = StudyPressDB::getPrefix().'studi_categ_cours';



if($access->getVar("SHOW TABLES LIKE '$tableCat'") == $tableCat) {
    if($access->getVar("SHOW TABLES LIKE '$tableCourse'") == $tableCourse) {


        $catWPId = wp_create_category( 'Temporary Category' );


        $managerCourse= new CourseManager();
        $course = new Course(array(
            'name'        => 'Temporary Course',
            'description' => '',
            'authors'     => array($user->id()),
            'categories'  => array( ( $catWPId !== 0 ) ? $catWPId : 1 )
        ));
        $courseId = $managerCourse->add($course);

        $resultLesson = $access->getResults("SELECT * FROM $tableCourse");


        $authors = [];


        foreach ($resultLesson as $lesson) {
            $managerLesson= new LessonManager();
            $lessonId= $managerLesson->add(new Lesson(array(
                'name'       => ($lesson['nom'] != "")?$lesson['nom']:"Course",
                'author'     => $lesson['author'],
                'description'=> $lesson['cours_des'],
                'duration'   => $lesson['duration'],
                'courseId'   => $courseId
            )));

            if($a = get_user_by('login',$lesson['author']))
            {
                $authors[] = $a->ID;
            }
            if( !in_array( $user->id(),$authors ) )
            {
                $authors[] = $user->id();
            }


            if($access->getVar("SHOW TABLES LIKE '$tableSlides'") == $tableSlides) {
                $slidesResult= $access->getResults("SELECT * FROM $tableSlides WHERE course_id = '".$lesson['course_id']."'");

                foreach ($slidesResult as $slide) {
                    $managerSlide= new SlideManager();
                    $managerSlide->add(new Slide(array(
                        'courseId'=> $lessonId,
                        'name'=> ($slide['slides_name']!= "")?$slide['slides_name']:"Slide",
                        'content' => $slide['slides_content'],
                        'order'   => $slide['slides_order']
                    )));
                }
            }

        }

        $course->setAuthors($authors);
        $managerCourse->update($courseId,$course);


        add_action('admin_init', function() {
            $sp_user = new StudyPressUserWP();
            add_user_meta($sp_user->id(), 'sp_menu_add_warning_migrate', true, true);
        });

    }



}

$access->query("DROP TABLE IF EXISTS $tableSlides");
$access->query("DROP TABLE IF EXISTS $tableCategCourse ");
$access->query("DROP TABLE IF EXISTS $tableCat ");
$access->query("DROP TABLE IF EXISTS $tableCourse ");

?>