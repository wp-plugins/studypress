<?php


class Domain {

    private $_id;
    private $_name;
    private $_description;





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



    public function getDescription()
    {
        return stripslashes( $this->_description);
    }


    public function setDescription($description)
    {
        $this->_description = $description;
    }


    public function getId()
    {
        return $this->_id;
    }


    public function setId($id)
    {
        $this->_id = $id;
    }


    public function getName()
    {
        return stripslashes($this->_name);
    }


    public function setName($name)
    {
        $this->_name = $name;
    }
} 