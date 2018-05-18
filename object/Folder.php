<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/14/2018
 * Time: 8:35 PM
 */

class Folder
{
    protected $id;
    protected $knowledge_base;
    protected $parent_folder;
    protected $name;
    protected $status;
    protected $contents;
    protected $register;

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
    public function getParentFolder()
    {
        return $this->parent_folder;
    }

    /**
     * @param mixed $parent_folder
     */
    public function setParentFolder($parent_folder)
    {
        $this->parent_folder = $parent_folder;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return mixed
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     * @param mixed $register
     */
    public function setRegister($register)
    {
        $this->register = $register;
    }



}