<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/15/2018
 * Time: 8:38 PM
 */

class FolderService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param Folder $folder
     * @param Register $register
     * @return bool
     * @throws Exception
     */
    public function create(Folder $folder, Register $register)
    {
        $query = "INSERT INTO folder (knowledge_base, parent_folder, name, status)" .
            " VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $folder->getKnowledgeBase());
        $stmt->bindParam(2, $folder->getParentFolder());
        $stmt->bindParam(3, $folder->getName());
        $stmt->bindParam(4, $folder->getStatus());

        if($stmt->execute()) {
            $folder->setId($this->conn->lastInsertId());
            $register->setKnowledgeBase($folder->getKnowledgeBase());
            $register->setFolder($folder->getId());
            $this->addRegister($register);
            return true;
        } else {
            throw new Exception("Unable to create folder");
        }
    }

    /**
     * @param Folder $folder
     * @param Register $register
     * @return bool
     * @throws Exception
     */
    public function update(Folder $folder, Register $register)
    {
        $query = "UPDATE folder SET knowledge_base = ?, parent_folder = ?, name = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $folder->getKnowledgeBase());
        $stmt->bindParam(2, $folder->getParentFolder());
        $stmt->bindParam(3, $folder->getName());
        $stmt->bindParam(4, $folder->getStatus());
        $stmt->bindParam(5, $folder->getId());

        if($stmt->execute()) {
            $register->setKnowledgeBase($folder->getKnowledgeBase());
            $register->setFolder($folder->getId());
            return $this->addRegister($register);
        } else {
            throw new Exception("Unable to update folder");
        }
    }

    /**
     * @param Folder $folder
     * @param Register $register
     * @throws Exception
     */
    public function delete(Folder $folder)
    {
        $this->deleteRegisters($folder->getId());
        $contents = $folder->getContents();
        if(count($contents) > 0){
            foreach ($contents as $cont){
                if($cont['type'] == "folder"){
                    $fol = $this->getById($cont['id']);
                    $this->delete($fol);
                }elseif($cont['type'] == "document"){
                    $docService = new DocumentService($this->conn);
                    $doc = $docService->getById($cont['id']);
                    $docService->delete($doc);
                }
            }
        }

        $query = "DELETE FROM folder WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if(!$stmt->execute(array($folder->getId()))){
            throw new Exception("Unable to delete folder");
        }
    }

    /**
     * @param $id
     * @return Folder
     * @throws Exception
     */
    public function getById($id)
    {
        $query = "SELECT * FROM folder WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($id));

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $folder = new Folder();
            $folder->setId($row['id']);
            $folder->setKnowledgeBase($row['knowledge_base']);
            $folder->setParentFolder($row['parent_folder']);
            $folder->setName($row['name']);
            $folder->setStatus($row['status']);
            $folder->setRegister($this->getRegister($folder->getId()));
            $folder->setContents($this->getContents($folder->getId()));

            return $folder;
        } else {
            throw new Exception("Unable to get folder by id");
        }
    }

    private function getRegister($folder_id)
    {
        $query = "SELECT * FROM register WHERE folder = ? ORDER BY date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($folder_id));

        if($stmt->rowCount() > 0){
            $register_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $register = array(
                    "user" => $row['user'],
                    "date" => $row['date'],
                    "document" => $row['document'],
                    "description" => $row['description']
                );

                array_push($register_arr, $register);
            }
            return $register_arr;
        }
        return array();
    }

    /**
     * @param $folder_id
     * @return array
     * @throws Exception
     */
    private function getContents($folder_id)
    {
        $folders = $this->getFolders($folder_id);
        $documents = $this->getDocuments($folder_id);
        $contents = array_merge($folders, $documents);
        return $contents;
    }

    /**
     * @param $folder_id
     * @return array
     * @throws Exception
     */
    private function getFolders($folder_id)
    {
        $query = "SELECT id FROM folder WHERE parent_folder = ? AND status = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($folder_id));

        if($stmt->rowCount() > 0){
            $folder_arr = array();
            $userService = new UserService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $fol = $this->getById($row['id']);
                $folder = array(
                    'id' => $fol->getId(),
                    'name' => $fol->getName(),
                    'status' => $fol->getStatus(),
                    'date' => $fol->getRegister()[count($fol->getRegister()) - 1]['date'],
                    'user' => $userService->getById($fol->getRegister()[count($fol->getRegister()) - 1]['user'])->getUsername(),
                    'description' => $fol->getRegister()[count($fol->getRegister()) - 1]['description'],
                    'type' => "folder"
                );
                array_push($folder_arr, $folder);
            }
            return $folder_arr;
        }
        return array();
    }

    /**
     * @param $folder_id
     * @return array
     * @throws Exception
     */
    private function getDocuments($folder_id)
    {
        $query = "SELECT id FROM document WHERE folder = ? AND status = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($folder_id));

        if($stmt->rowCount() > 0){
            $document_arr = array();
            $documentService = new DocumentService($this->conn);
            $userService = new UserService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $doc = $documentService->getById($row['id']);
                $document = array(
                    'id' => $doc->getId(),
                    'name' => $doc->getName(),
                    'status' => $doc->getStatus(),
                    'date' => $doc->getRegister()[count($doc->getRegister()) - 1]['date'],
                    'user' => $userService->getById($doc->getRegister()[count($doc->getRegister())-1]['user'])->getUsername(),
                    'description' => $doc->getRegister()[count($doc->getRegister()) - 1]['description'],
                    'type' => "document"
                );
                array_push($document_arr, $document);
            }
            return $document_arr;
        }
        return array();
    }

    /**
     * @param Register $register
     * @return bool
     * @throws Exception
     */
    private function addRegister(Register $register)
    {
        $registerService = new RegisterService($this->conn);
        return $registerService->addRegister($register);
    }

    private function deleteRegisters($folder_id)
    {
        $query = "SELECT * FROM register WHERE folder = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($folder_id));

        if($stmt->rowCount() > 0){
            $registerService = new RegisterService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $registerService->deleteRegister($row['id']);
            }
        }
    }
}