<?php 
    class Course {
        //Attributes. 
        public $id; 
        public $title; 
        public $url; 
        public $description; 

        //Constructors. 
        public function __construct() {
            
        }

        //Getters and Setters.
        function getId() {
            return $this->id;
        }

        function getTitle() {
            return $this->title;
        }

        function getUrl() {
            return $this->url;
        }

        function getDescription() {
            return $this->description;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setTitle($title) {
            $this->title = $title;
        }

        function setUrl($url) {
            $this->url = $url;
        }

        function setDescription($description) {
            $this->description = $description;
        }

    }

?>  