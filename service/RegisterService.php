<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/15/2018
 * Time: 8:09 PM
 */

class RegisterService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param Register $register
     * @return bool
     * @throws Exception
     */
    public function addRegister(Register $register)
    {
        $query = "INSERT INTO register (knowledge_base, folder, document, user, date, description)" .
            " VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $register->getKnowledgeBase());
        $stmt->bindParam(2, $register->getFolder());
        $stmt->bindParam(3, $register->getDocument());
        $stmt->bindParam(4, $register->getUser());
        $stmt->bindParam(5, $register->getDate());
        $stmt->bindParam(6, $register->getDescription());

        if(!$stmt->execute()){
            throw new Exception("Unable to add register");
        }
        if(!is_null($register->getFolder())){
            $folderService = new FolderService($this->conn);
            $folder = $folderService->getById($register->getFolder());
            if(!is_null($folder->getParentFolder())){
                $register->setFolder($folder->getParentFolder());
                $this->addRegister($register);
            }
        }
        return true;
    }

    public function deleteRegister($id)
    {
        $query = "DELETE FROM register WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        if(!$stmt->execute(array($id))){
            throw new Exception("Unable to delete register");
        }
    }

    /**
     * @param $user
     * @param $date
     * @param $description
     * @return bool
     * @throws Exception
     */
    public function addUserRegister($user, $date, $description)
    {
        $query = "INSERT INTO user_register (user, date, description) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $date);
        $stmt->bindParam(3, $description);

        if(!$stmt->execute()){
            throw new Exception("Unable to add register");
        }
        return true;
    }
}