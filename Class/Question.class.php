<?php

class Question {

    private $_id;
    private $_quizId;
    private $_content;
    private $_order;
    private $_propositions = array();


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


    public function getContent()
    {
        return stripslashes($this->_content);
    }


    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getNiceContent(){

        $points = strlen($this->getContent())>28?"...":"";
        return substr($this->getContent(),0,28) . $points ;
    }


    public function getId()
    {
        return $this->_id;
    }


    public function setId($id)
    {
        $this->_id = $id;
    }


    public function getOrder()
    {
        return $this->_order;
    }


    public function setOrder($order)
    {
        $this->_order = $order;
    }


    public function getPropositions()
    {
        return $this->_propositions;
    }


    public function setPropositions($propositions)
    {
        $this->_propositions = $propositions;
    }

    public function getPropositionByPosition($position)
    {
        return  $this->_propositions[$position];
    }

    public function getPropositionById($id){
        foreach ($this->_propositions as $p) {
            if((int) $id === $p->getId()) return $p;
        }
        return null;


    }


    public function getQuizId()
    {
        return $this->_quizId;
    }


    public function setQuizId($quizId)
    {
        $this->_quizId = $quizId;
    }


    public function getContentSlide(){
        $c = "<div class='sp-qcm'>";
        $c .= "<p>" . $this->_content . "</p>";
        $c .= "<ul>";
        $i = 0 ;
        foreach ($this->_propositions as $prop) {
            $c .= "<li class=''><input type='checkbox' id='sp-checkbox-".$prop->getId()."' data-id='".$this->_id."' data-prop='".$prop->getId()."' name='true[]'>";
            $c .= "<label for='sp-checkbox-".$prop->getId()."'>".$prop->getContent() . "</label></li>";
        }

        $c .= "</ul></div>";

        return $c;


    }


    public function getContentSlideWithErrors(){
        $c = "<div class='sp-qcm'>";
        $c .= "<p>" . $this->_content . "</p>";
        $c .= "<ul>";
        $i = 0 ;
        foreach ($this->_propositions as $key => $prop) {

            $imgUser = ($prop->getTypeUser() === "true")?"check.png":"empty.png";
            $img = ($prop->getType() === "true")?"true.png":"empty.png";

            $c .= "<li><img style='margin-right:10px' src='".__ROOT_PLUGIN__2."images/".$imgUser."'>";
            $c .= "<label >".$prop->getContent() . "</label>";
            $c .= "<img class='img-correct' style='margin-right:10px' src='".__ROOT_PLUGIN__2."images/".$img."'>";
            $c .= "</li>";
            }

            $c .= "</ul></div>";
            $i++;




        return $c;


    }





} 