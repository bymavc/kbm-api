<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/14/2018
 * Time: 8:51 PM
 */

class KnowledgeBase
{
    protected $id;
    protected $name;
    protected $description;
    protected $privacy;
    protected $status;
    protected $register;
    protected $root_folder;
    protected $permissions;

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

    /**
     * @return mixed
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param mixed $privacy
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
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

    /**
     * @return mixed
     */
    public function getRootFolder()
    {
        return $this->root_folder;
    }

    /**
     * @param mixed $root_folder
     */
    public function setRootFolder($root_folder)
    {
        $this->root_folder = $root_folder;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param mixed $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }



}