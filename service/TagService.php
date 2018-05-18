<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/15/2018
 * Time: 1:35 PM
 */

class TagService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param Tag $tag
     * @return Tag
     * @throws Exception
     */
    public function create(Tag $tag)
    {
        $query = "INSERT INTO tag (name, status) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $tag->getName());
        $stmt->bindParam(2, $tag->getStatus());

        if($stmt->execute()){
            return $this->getById($this->conn->lastInsertId());
        } else {
            throw new Exception("Unable to create tag");
        }
    }

    /**
     * @param Tag $tag
     * @return bool
     * @throws Exception
     */
    public function update(Tag $tag)
    {
        $query = "UPDATE FROM tag SET name = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $tag->getName());
        $stmt->bindParam(2, $tag->getStatus());
        $stmt->bindParam(3, $tag->getId());
        if($stmt->execute()){
            return true;
        } else {
            throw new Exception("Unable to update tag");
        }
    }

    /**
     * @param $id
     * @return Tag
     * @throws Exception
     */
    public function getById($id)
    {
        $query = "SELECT * FROM tag WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($id));
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $tag = new Tag();
            $tag->setId($row['id']);
            $tag->setName($row['name']);
            $tag->setStatus($row['status']);

            return $tag;
        } else {
            throw new Exception("Unable to get tag by id");
        }
    }

    /**
     * @param $name
     * @return Tag
     * @throws Exception
     */
    public function getByName($name)
    {
        $query = "SELECT * FROM tag WHERE name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($name));
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $tag = new Tag();
            $tag->setId($row['id']);
            $tag->setName($row['name']);
            $tag->setStatus($row['status']);

            return $tag;
        } else {
            throw new Exception("Unable to get tag by name");
        }
    }

    public function getAll()
    {
        $query = "SELECT * FROM tag ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $tag_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($tag_arr, $row['name']);
            }
            return $tag_arr;
        }
        return array();
    }

    public function delete($id)
    {
        $query = "DELETE FROM tag WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if($stmt->execute(array($id))){
            return true;
        } else {
            throw new Exception("Unable to delete tag");
        }
    }

    private function checkTag($tag_name){
        $query = "SELECT * FROM tag WHERE name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($tag_name));
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }


    private function addTag($document_id, $tag_id){
        $query = "INSERT INTO document_tag (document, tag) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $document_id);
        $stmt->bindParam(2, $tag_id);

        if(!$stmt->execute()){
            throw new Exception("Unable to add Tag");
        }
    }

    private function removeTag($document_id, $tag_id){
        $query = "DELETE FROM document_tag WHERE document = ? AND tag = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $document_id);
        $stmt->bindParam(2, $tag_id);

        if(!$stmt->execute()){
            throw new Exception("Unable to remove Tag");
        }
    }

    /**
     * @param Document $doc
     * @param $tags
     * @throws Exception
     */
    public function tagManager(Document $doc, $tags){
        $db_tags = $doc->getTags();
        foreach ($tags as $t){
            $coincidence = false;
            if(count($db_tags) > 0){
                for ($j = 0; $j <= count($db_tags); $j++){
                    if($t == $db_tags[$j]){
                        $coincidence = true;
                        array_splice($db_tags, $j, 1);
                        break;
                    }
                }
            }
            if(!$coincidence){
                if($this->checkTag($t)){
                    $tag = $this->getByName($t);
                    $this->addTag($doc->getId(), $tag->getId());
                } else {
                    $tag = new Tag();
                    $tag->setName($t);
                    $tag->setStatus(1);
                    $tag = $this->create($tag);
                    $this->addTag($doc->getId(), $tag->getId());
                }
            }
        }
        foreach ($db_tags as $tag){
            $this->removeTag($doc->getId(), $this->getByName($tag)->getId());
        }
    }

    public function deleteTag($id){
        $query = "DELETE FROM document_tag WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if(!$stmt->execute(array($id))){
            throw new Exception("Unable to remove Tag");
        }
    }
}