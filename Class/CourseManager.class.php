<?php


class CourseManager
{
    private $_access;




    public function __construct()
    {

        $this->_access = new AccessData;

    }



    public function add(Course $course)
    {
        $a = array(

            StudyPressDB::COL_NAME_COURSE => $course->getName(),
            StudyPressDB::COL_DESCRIPTION_COURSE => $course->getDescription(),
        );

       $this->_access->insert(StudyPressDB::getTableNameCourse(), $a);


        $idCourse = $this->_access->getLastInsertId();


        $course->setId($idCourse);




        $this->addCategories($idCourse,$course->getCategories());



        $this->addUsers($idCourse,$course->getAuthors());



        $this->post($course);


        return $idCourse;



    }


    public function deleteAllCategories($id){

        $this->_access->delete(
            StudyPressDB::getTableName_CourseCategory(),
            array(StudyPressDB::COL_ID_COURSE_CAT_N_COURSE => $id)
        );

    }

    public function addCategories($idCourse,array $categories)
    {
        foreach ($categories as $category) {
            $a = array(

                StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE => $category,
                StudyPressDB::COL_ID_COURSE_CAT_N_COURSE => $idCourse,
            );

            $this->_access->insert(StudyPressDB::getTableName_CourseCategory(), $a);
        }
    }




    public function getCategoriesId($idCourse)
    {
        $idCourse = (int) $idCourse;

        $result = $this->_access->getResults($this->_access->prepare(
                "SELECT " . StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE ." FROM " . StudyPressDB::getTableName_CourseCategory() ." WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d'",$idCourse)
        );




        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row[StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE];
        }


        return $ids;
    }


    public function deleteAllUsers($id){

        $this->_access->delete(
            StudyPressDB::getTableName_CourseUsers(),
            array(StudyPressDB::COL_ID_COURSE_CAT_N_COURSE => $id)
        );

    }

    public function addUsers($idCourse,array $users)
    {
        foreach ($users as $user) {
            $a = array(

                StudyPressDB::COL_ID_USERS_USERS_N_COURSE => $user,
                StudyPressDB::COL_ID_COURSE_CAT_N_COURSE => $idCourse,
            );

            $this->_access->insert(StudyPressDB::getTableName_CourseUsers(), $a);
        }
    }


    public function getUsersId($idCourse)
    {
        $idCourse = (int) $idCourse;

        $result = $this->_access->getResults($this->_access->prepare(
                "SELECT " . StudyPressDB::COL_ID_USERS_USERS_N_COURSE ." FROM " . StudyPressDB::getTableName_CourseUsers() ." WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d'",$idCourse)
        );




        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row[StudyPressDB::COL_ID_USERS_USERS_N_COURSE];
        }

        return $ids;
    }





    public function update($id, Course $course)
    {
        $this->_access->update(
            StudyPressDB::getTableNameCourse(),
            array(

                StudyPressDB::COL_NAME_COURSE => $course->getName(),
                StudyPressDB::COL_AVANCEMENT_COURSE => $course->getAvancement(),
                StudyPressDB::COL_ID_POST_COURSE => $course->getPostId(),
                StudyPressDB::COL_DESCRIPTION_COURSE => $course->getDescription(),
            ),
            array(StudyPressDB::COL_ID_COURSE => $id)
        );



        $this->deleteAllCategories($id);

        $this->addCategories($id,$course->getCategories());



        $this->deleteAllUsers($id);

        $this->addUsers($id,$course->getAuthors());



        $childrens = PostWP::getChildrenPost($course->getPostId());




        foreach ($childrens as $c)  {

            PostWP::updatePost(array(
                "ID" => $c['ID'],
                'post_category' => $course->getCategories()
            ));

        }


    }


    public function delete($id)
    {
        $id = (int)$id;

        $course = $this->getById($id);


        $this->deleteAllCategories($id);


        $this->deleteAllUsers($id);


        $this->unpost($course);




        $this->_access->delete(
            StudyPressDB::getTableNameCourse(),
            array(StudyPressDB::COL_ID_COURSE => $id)
        );
    }


    public function isError()
    {
        return ($this->_access->getLastError() == '') ? false : true;
    }


    public function getMessageError(){
        return $this->_access->getLastError();
    }


    public function getAll()
    {

        $courses = array();
        $result = $this->_access->getResults("SELECT * FROM " .  StudyPressDB::getTableNameCourse());

        foreach ($result as $row) {

            $course = self::returnedCourse($row);


            $course->setNbreLessons($this->getNumberOf('lesson',$course->getId()));
            $course->setNbrequizs($this->getNumberOf('quiz',$course->getId()));


            $course->setCategories($this->getCategoriesId($course->getId()));



            $course->setAuthors($this->getUsersId($course->getId()));


            array_push($courses, $course);

        }
        return $courses;


    }





    public function getNumberOf($type,$id)
    {


        return (int) $this->_access->getVar($this->_access->prepare("SELECT COUNT(*) FROM ". StudyPressDB::getTableNameActivity() . " WHERE ". StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d' AND " . StudyPressDB::COL_TYPE_ACTIVITY ." = '$type'",$id));
    }






    public static function returnedCourse($row)
    {
        return (
        empty($row)
            ? null :
            new Course(array(
                'id'          => (int) $row[StudyPressDB::COL_ID_COURSE],
                'name'        =>       $row[StudyPressDB::COL_NAME_COURSE],
                'description' =>       $row[StudyPressDB::COL_DESCRIPTION_COURSE],
                'avancement'  =>       $row[StudyPressDB::COL_AVANCEMENT_COURSE],
                'postId'      => (int) $row[StudyPressDB::COL_ID_POST_COURSE]
            ))
        );
    }


    public function getById($id)
    {
        $result = $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameCourse() . " WHERE " . StudyPressDB::COL_ID_COURSE . " = '%d'", $id));


        $course =  self::returnedCourse($result);





        $course->setCategories($this->getCategoriesId($course->getId()));


        $course->setAuthors($this->getUsersId($course->getId()));


        return $course;

    }

    public function getCoursesByAuthor($authorId)
    {

        $courses = array();
        $result = $this->_access->getResults($this->_access->prepare("SELECT ". StudyPressDB::COL_ID_COURSE_USERS_N_COURSE." FROM " .  StudyPressDB::getTableName_CourseUsers()." WHERE " . StudyPressDB::COL_ID_USERS_USERS_N_COURSE ." = '%d'",$authorId));

        foreach ($result as $row) {

            $id = $row[StudyPressDB::COL_ID_COURSE_USERS_N_COURSE];

            $course =$this->getById($id);


            array_push($courses, $course);



        }
        return $courses;
    }








    public function hasActivities($courseId)
    {
        $nbr =   $this->_access->getVar("SELECT COUNT(*) FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '" . $courseId . "'" );

        $nbr = (int) $nbr;

        return ($nbr !== 0)?true:false;
    }






    public function post(Course $course){


        $post = array(
            'post_content' => '',
            'post_name' => $course->getName(),
            'post_title' => $course->getName(),
            'post_status' => 'publish',
            'post_type' => 'course',
            'post_category' => $course->getCategories()

        );





        $post_id = PostWP::post($post);


        $post = array(
            'ID'           => $post_id,
            'post_content' => "[studypress_child id=". $post_id ."]"
        );

        PostWP::updatePost( $post );


    








        $course->setPostId($post_id);

        $this->update($course->getId(), $course);

    }


    public function unpost(Course $course){
        PostWP::unPost( $course->getPostId() );


    }

}
?>