<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/15/2018
 * Time: 6:12 PM
 */

class Register
{
    protected $id;
    protected $knowledge_base;
    protected $folder = null;
    protected $document = null;
    protected $user;
    protected $date;
    protected $description;

    public function __construct($user, $date, $description)
    {
        $this->user = $user;
        $this->date = $date;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getKnowledgeBase()
    {
        return $this->knowledge_base;
    }

    /**
     * @param mixed $knowledge_base
     */
    public function setKnowledgeBase($knowledge_base)
    {
        $this->knowledge_base = $knowledge_base;
    }

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param mixed $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param mixed $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


}