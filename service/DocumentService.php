<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/15/2018
 * Time: 1:53 PM
 */

class DocumentService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param Document $doc
     * @param Register $register
     * @return Document
     * @throws Exception
     */
    public function create(Document $doc, Register $register)
    {
        $query = "INSERT INTO document (knowledge_base, folder, name, description, content, status)" .
            " VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $doc->getKnowledgeBase());
        $stmt->bindParam(2, $doc->getFolder());
        $stmt->bindParam(3, $doc->getName());
        $stmt->bindParam(4, $doc->getDescription());
        $stmt->bindParam(5, $doc->getContent());
        $stmt->bindParam(6, $doc->getStatus());

        if($stmt->execute()){
            $doc->setId($this->conn->lastInsertId());
            $register->setKnowledgeBase($doc->getKnowledgeBase());
            $register->setFolder($doc->getFolder());
            $register->setDocument($doc->getId());
            $this->addRegister($register);
            return $this->getById($doc->getId());
        } else {
            throw new Exception("Unable to create document");
        }
    }

    /**
     * @param Document $doc
     * @param Register $register
     * @return bool
     * @throws Exception
     */
    public function update(Document $doc, Register $register)
    {
        $query = "UPDATE document SET knowledge_base = ?, folder = ?, name = ?, description = ?, content = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $doc->getKnowledgeBase());
        $stmt->bindParam(2, $doc->getFolder());
        $stmt->bindParam(3, $doc->getName());
        $stmt->bindParam(4, $doc->getDescription());
        $stmt->bindParam(5, $doc->getContent());
        $stmt->bindParam(6, $doc->getStatus());
        $stmt->bindParam(7, $doc->getId());

        if($stmt->execute()){
            $register->setKnowledgeBase($doc->getKnowledgeBase());
            $register->setFolder($doc->getFolder());
            $register->setDocument($doc->getId());
            return $this->addRegister($register);
        } else {
            throw new Exception("Unable to update document");
        }
    }

    /**
     * @param Document $doc
     * @param Register $register
     * @throws Exception
     */
    public function delete(Document $doc)
    {
        $this->deleteRegisters($doc->getId());
        $this->deleteTags($doc->getId());
        $query = "DELETE FROM document WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if(!$stmt->execute(array($doc->getId()))){
            throw new Exception("Unable to delete document");
        }
    }

    /**
     * @param $id
     * @return Document
     * @throws Exception
     */
    public function getById($id)
    {
        $query = "SELECT * FROM document WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($id));
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $doc = new Document();
            $doc->setId($row['id']);
            $doc->setKnowledgeBase($row['knowledge_base']);
            $doc->setFolder($row['folder']);
            $doc->setName($row['name']);
            $doc->setDescription($row['description']);
            $doc->setContent($row['content']);
            $doc->setStatus($row['status']);
            $doc->setRegister($this->getRegister($doc->getId()));
            $doc->setTags($this->getTags($doc->getId()));

            return $doc;
        } else {
            throw new Exception("Unable to get document by id");
        }
    }

    private function getRegister($document_id){
        $query = "SELECT * FROM register WHERE document = ? ORDER BY date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($document_id));

        if($stmt->rowCount() > 0){

            $register_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $register = array(
                    "user" => $row['user'],
                    "date" => $row['date'],
                    "description" => $row['description']
                );

                array_push($register_arr, $register);
            }
            return $register_arr;
        }
        return array();
    }

    private function getTags($document_id){
        $query = "SELECT t.name as name FROM document_tag dt RIGHT JOIN tag t ON dt.tag = t.id WHERE document = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($document_id));

        if($stmt->rowCount() > 0){
            $tag_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $tag =  $row['name'];
                array_push($tag_arr, $tag);
            }
            return $tag_arr;
        }
        return array();
    }

    public function getRoute($folder_id){
        $route = $this->routeBuilder(array(), $folder_id);
        return $route;
    }

    private function routeBuilder(Array $array, $folder_id){
        $query = "SELECT id, name, parent_folder FROM folder WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($folder_id));

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $folder = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'parent_folder' => $row['parent_folder']
            );
            array_push($array, $folder);
            if(is_null($row['parent_folder'])){
                return $array;
            } else {
                return $this->routeBuilder($array, $row['parent_folder']);
            }
        }
        return array();
    }

    /**
     * @param Register $register
     * @return bool
     * @throws Exception
     */
    private function addRegister(Register $register){
        $registerService = new RegisterService($this->conn);
        return $registerService->addRegister($register);
    }

    private function deleteRegisters($document_id)
    {
        $query = "SELECT * FROM register WHERE document = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($document_id));

        if($stmt->rowCount() > 0){
            $registerService = new RegisterService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $registerService->deleteRegister($row['id']);
            }
        }
    }

    private function deleteTags($document_id)
    {
        $query = "SELECT * FROM document_tag WHERE document = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($document_id));

        if($stmt->rowCount() > 0){
            $tagService = new TagService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $tagService->deleteTag($row['id']);
            }
        }
    }
}