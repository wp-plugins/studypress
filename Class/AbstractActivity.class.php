<?php


abstract class AbstractActivity {

    private $_id;
    private $_name;
    private $_author;
    private $_description;
    private $_duration;
    private $_note;
    private $_glossary;
    private $_pictureUrl;
    private $_shortCode;
    private $_authorId;
    private $_courseId;
    private $_postId ;


    public function __construct(array $d)
    {
        $this->hydrate($d);
    }

    public function hydrate(array $d)
    {
        foreach ($d as $key => $value) {

            $method = 'set' . ucfirst($key);

            if (method_exists($this,$method)) {

                $this->$method($value);
            }
        }
    }



    public function getId() {
        return $this->_id;
    }


    public function setId($id) {
        $this->_id = $id;
        return $this;
    }



    public function getName() {
        return stripslashes($this->_name);
    }


    public function setName($name) {
        $this->_name = $name;
        return $this;
    }


    public function getAuthor() {
        return $this->_author;
    }


    public function setAuthor($author) {
        $this->_author = $author;
        return $this;
    }



    public function getDescription() {
        return stripslashes($this->_description);
    }


    public function setDescription($description) {
        $this->_description = $description;
        return $this;
    }

    public function getNiceDescription(){

        $points = (strlen($this->_description)>80)?"...":"";
        return stripslashes(substr($this->_description,0,80) . $points);
    }


    public function getDuration() {
        return $this->_duration;
    }


    public function setDuration($duration) {
        $this->_duration = $duration;
        return $this;
    }


    public function getNote() {
        if(json_decode($this->_note) !== null)
        {
            $notes = json_decode($this->_note);
            foreach ($notes as $key => $val)
            {
                $notes[$key] = stripslashes($val);
            }

            return $notes;

        }

        return array();
    }

    public function getNoteJson() {
        return $this->_note;
    }


    public function setNote($note) {
        $this->_note = $note;
        return $this;
    }


    public function getGlossary() {


        if(($glossaries = (array) json_decode($this->_glossary)) !== array())
        {


            $n = array();
            foreach ($glossaries['name'] as $k => $g)
            {
                $n['name'][$k] = stripslashes($g);
            }

            foreach ($glossaries['desc'] as $k => $g)
            {
                $n['desc'][$k] = stripslashes($g);
            }

            return  (object) $n;

        }
        return (object) array('name' => array(),'desc' => array());
    }

    public function getGlossaryJson() {
        return $this->_glossary;
    }


    public function setGlossary($glossary) {
        $this->_glossary = $glossary;
        return $this;
    }


    public function getPictureUrl() {
        return $this->_pictureUrl;
    }


    public function setPictureUrl($pictureUrl) {
        $this->_pictureUrl = $pictureUrl;
        return $this;
    }


    public function getShortCode() {
        return $this->_shortCode;
    }


    public function setShortCode($shortCode) {
        $this->_shortCode = $shortCode;
        return $this;
    }


    public function getAuthorId() {
        return $this->_authorId;
    }


    public function setAuthorId($authorId) {
        $this->_authorId = $authorId;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getCourseId()
    {
        return $this->_courseId;
    }


    public function setCourseId($IdCourse)
    {
        $this->_courseId = $IdCourse;
    }


    public function getPostId()
    {
        return $this->_postId;
    }


    public function setPostId($isPosted)
    {
        $this->_postId = $isPosted;
    }

} 