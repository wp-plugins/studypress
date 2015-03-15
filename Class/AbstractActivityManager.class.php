<?php

abstract class AbstractActivityManager {

    protected  $_access;

    private $type;

    protected function __construct($type){
        $this->_access = new AccessData;
        $this->type = $type;
    }




    protected function add(AbstractActivity $activity)
    {
        $a = array(

            StudyPressDB::COL_NAME_ACTIVITY => $activity->getName(),
            StudyPressDB::COL_DURATION_ACTIVITY => $activity->getDuration(),
            StudyPressDB::COL_AUTEUR_ACTIVITY => $activity->getAuthor(),
            StudyPressDB::COL_DESCRIPTION_ACTIVITY => $activity->getDescription(),
            StudyPressDB::COL_NOTES_ACTIVITY => $activity->getNoteJson(),
            StudyPressDB::COL_GLOSSARY_ACTIVITY => $activity->getGlossaryJson(),
            StudyPressDB::COL_PICTURE_ACTIVITY => $activity->getPictureUrl(),
            StudyPressDB::COL_SHORT_CODE_ACTIVITY => $activity->getShortCode(),
            StudyPressDB::COL_ID_AUTEUR_ACTIVITY => $activity->getAuthorId(),
            StudyPressDB::COL_ID_COURSE_ACTIVITY => $activity->getCourseId(),
            StudyPressDB::COL_TYPE_ACTIVITY => $this->type,
        );

        $this->_access->insert(StudyPressDB::getTableNameActivity(), $a);


        return  $this->_access->getLastInsertId();



    }




    public function update($id, AbstractActivity $activity)
    {
        $this->_access->update(
            StudyPressDB::getTableNameActivity(),
            array(

                StudyPressDB::COL_NAME_ACTIVITY => $activity->getName(),
                StudyPressDB::COL_DURATION_ACTIVITY => $activity->getDuration(),
                StudyPressDB::COL_AUTEUR_ACTIVITY => $activity->getAuthor(),
                StudyPressDB::COL_DESCRIPTION_ACTIVITY => $activity->getDescription(),
                StudyPressDB::COL_NOTES_ACTIVITY => $activity->getNoteJson(),
                StudyPressDB::COL_GLOSSARY_ACTIVITY => $activity->getGlossaryJson(),
                StudyPressDB::COL_PICTURE_ACTIVITY => $activity->getPictureUrl(),
                StudyPressDB::COL_SHORT_CODE_ACTIVITY => $activity->getShortCode(),
                StudyPressDB::COL_ID_AUTEUR_ACTIVITY => $activity->getAuthorId(),
                StudyPressDB::COL_ID_POST_ACTIVITY => $activity->getPostId(),
                StudyPressDB::COL_ID_COURSE_ACTIVITY => $activity->getCourseId()
            ),
            array(StudyPressDB::COL_ID_ACTIVITY => $id)
        );



        if($activity->getPostId() !== 0)
        {
           $this->updatePost($activity);
        }



    }




    public static function returnedArrayActivity($row)
    {
        return array(
            'id'          => (int) $row[StudyPressDB::COL_ID_ACTIVITY],
            'name'        =>       $row[StudyPressDB::COL_NAME_ACTIVITY],
            'author'      =>       $row[StudyPressDB::COL_AUTEUR_ACTIVITY],
            'description' =>       $row[StudyPressDB::COL_DESCRIPTION_ACTIVITY],
            'duration'    =>       $row[StudyPressDB::COL_DURATION_ACTIVITY],
            'note'        =>       $row[StudyPressDB::COL_NOTES_ACTIVITY],
            'glossary'    =>       $row[StudyPressDB::COL_GLOSSARY_ACTIVITY],
            'pictureUrl'  =>       $row[StudyPressDB::COL_PICTURE_ACTIVITY],
            'shortCode'   =>       $row[StudyPressDB::COL_SHORT_CODE_ACTIVITY],
            'authorId'    => (int) $row[StudyPressDB::COL_ID_AUTEUR_ACTIVITY],
            'postId'      => (int) $row[StudyPressDB::COL_ID_POST_ACTIVITY],
            'courseId'    => (int) $row[StudyPressDB::COL_ID_COURSE_ACTIVITY],
         );

    }



    public function isError()
    {
        return ($this->_access->getLastError() == '') ? false : true;
    }


    public function getMessageError()
    {
        return $this->_access->getLastError();
    }


    public function getById($id)
    {
        return $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY . " = '%d' AND " . StudyPressDB::COL_TYPE_ACTIVITY . " = '$this->type' ", $id));
    }



    public function getActivityOfCourse($courseId)
    {

        return $this->_access->getResults($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d' AND " . StudyPressDB::COL_TYPE_ACTIVITY . " = '$this->type' ", $courseId));

    }


    public function getActivityByPostId($postId)
    {


        return $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_POST_ACTIVITY . " = '%d' AND " . StudyPressDB::COL_TYPE_ACTIVITY . " = '$this->type' ", $postId));
    }


    public function getAllWithout()
    {

        return $this->_access->getResults("SELECT * FROM " . StudyPressDB::getTableNameActivity() ." WHERE " . StudyPressDB::COL_TYPE_ACTIVITY . " = '$this->type' " );

    }


    public function post(AbstractActivity $activity){

        $managerCourse = new CourseManager();
        $course = $managerCourse->getById($activity->getCourseId());
        $post = array(

            'post_content' => $activity->getShortCode(),
            'post_name' => $activity->getName(),
            'post_title' => $activity->getName(),
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_author' => $activity->getAuthorId(),
            'post_category' => $course->getCategories(),
            'post_parent' => $course->getPostId(),
            'post_excerpt' => ($activity->getDescription())?$activity->getDescription():$activity->getName()

        );



        $post_id = PostWP::post($post);




        $activity->setPostId($post_id);

        $this->update($activity->getId(), $activity);


        $this->attacherImageToPost($activity);



    }


    public function updatePost(AbstractActivity $activity){

        $managerCourse = new CourseManager();
        $course = $managerCourse->getById($activity->getCourseId());
        $post = array(
             'ID'             => $activity->getPostId(),
            'post_content' => $activity->getShortCode(),
            'post_name' => $activity->getName(),
            'post_title' => $activity->getName(),
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_author' => $activity->getAuthorId(),
            'post_category' => $course->getCategories(),
            'post_parent' => $course->getPostId(),
            'post_excerpt' => ($activity->getDescription())?$activity->getDescription():$activity->getName()

        );

        PostWP::updatePost($post);


        $this->attacherImageToPost($activity);

    }




    public function unPost(AbstractActivity $activity){


        PostWP::unPost( $activity->getPostId());

        $activity->setPostId(0);

        $this->update($activity->getId(), $activity);
    }




    public function attacherImageToPost(AbstractActivity $activity)
    {
        if($activity->getPictureUrl() && $activity->getPostId()){

            PostWP::setPostPicture(  $activity->getPostId(), $activity->getPictureUrl() );
        }
    }



    public function dettacherImageFromPost(AbstractActivity $activity)
    {
        if(PostWP::hasPostPicture($activity->getId()))
        {
            PostWP::deletePostPicture( $activity->getId() );
        }
    }
}