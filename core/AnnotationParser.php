<?php
namespace App\Core;

class AnnotationParser
{
    /** @var \ReflectionClass $reflectionClass */
    protected $reflectionClass;

    /**
     * Construct gets class name and creates a ReflectionClass object.
     * 
     * @param string class name.
     */
    public function __construct($class)
    {
        $this->reflectionClass = new \ReflectionClass($class);
    }


    /**
     * Parse annotation fields of models properties.
     * 
     * Gets properties, iterates on them and collects data for the future SQL query.
     * 
     * @return array An array of fields.
     */
    public function getAnnotationFields()
    {
        $fields = array();
        $properties = $this->reflectionClass->getProperties();

        foreach($properties as $property) {
            $comment = $property->getDocComment();

            if(!$comment)
                continue;

            $comment = explode(" ", $comment);

            if(!($comment[1] === "@var"))
                continue;
            
            array_shift($comment);
            array_shift($comment);
            array_pop($comment);

            $fields[$property->getName()] = implode(" ", $comment);
        }

        return $fields;
    }
}