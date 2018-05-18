<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/15/2018
 * Time: 8:16 PM
 */

class KnowledgeBaseService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param KnowledgeBase $kb
     * @param Register $register
     * @return KnowledgeBase
     * @throws Exception
     */
    public function create(KnowledgeBase $kb, Register $register)
    {
        $query = "INSERT INTO knowledge_base (name, description, privacy, status)" .
            " VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $kb->getName());
        $stmt->bindParam(2, $kb->getDescription());
        $stmt->bindParam(3, $kb->getPrivacy());
        $stmt->bindParam(4, $kb->getStatus());

        if($stmt->execute()){
            $kb->setId($this->conn->lastInsertId());
            $register->setKnowledgeBase($kb->getId());
            $this->addRegister($register);

            $folder = new Folder();
            $folder->setName('root-'.$kb->getId());
            $folder->setKnowledgeBase($kb->getId());
            $folder->setStatus(1);

            $folderService = new FolderService($this->conn);
            $folderService->create($folder, $register);

            $this->addPermission($register->getUser(), $kb->getId(), 1);

            return $this->getById($kb->getId());
        }else {
            throw new Exception("Unable to create knowledge base");
        }
    }

    /**
     * @param KnowledgeBase $kb
     * @param Register $register
     * @return bool
     * @throws Exception
     */
    public function update(KnowledgeBase $kb, Register $register)
    {
        $query = "UPDATE knowledge_base SET name = ?, description = ?, privacy = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $kb->getName());
        $stmt->bindParam(2, $kb->getDescription());
        $stmt->bindParam(3, $kb->getPrivacy());
        $stmt->bindParam(4, $kb->getStatus());
        $stmt->bindParam(5, $kb->getId());

        if($stmt->execute()){
            $this->updatePermissions($kb->getId(), $kb->getPermissions());
            $register->setKnowledgeBase($kb->getId());
            return $this->addRegister($register);
        } else {
            throw new Exception("Unable to update knowledge base");
        }
    }

    /**
     * @param KnowledgeBase $kb
     * @param Register $register
     * @throws Exception
     */
    public function delete(KnowledgeBase $kb)
    {
        $this->deletePermissions($kb->getId());
        $folderService = new FolderService($this->conn);
        $folder = $folderService->getById($kb->getRootFolder());
        $folderService->delete($folder);
        $this->deleteRegisters($kb->getId());

        $query = "DELETE FROM knowledge_base WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if(!$stmt->execute(array($kb->getId()))){
            throw new Exception("Unable to delete knowledge base");
        }
    }

    /**
     * @param $id
     * @return KnowledgeBase
     * @throws Exception
     */
    public function getById($id)
    {
        $query = "SELECT k.*, f.id as root_folder FROM knowledge_base k LEFT JOIN folder f ON k.id = f.knowledge_base" .
            " WHERE k.id = ? AND f.parent_folder IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($id));

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $kb = new KnowledgeBase();
            $kb->setId($row['id']);
            $kb->setName($row['name']);
            $kb->setDescription($row['description']);
            $kb->setPrivacy($row['privacy']);
            $kb->setStatus($row['status']);
            $kb->setRootFolder($row['root_folder']);
            $kb->setRegister($this->getRegister($kb->getId()));
            $kb->setPermissions($this->getPermissions($kb->getId()));

            return $kb;
        } else {
            throw new Exception("Unable to get knowledge base by id");
        }
    }

    public function getList($user_id, $privacy){
        $query = "SELECT id, name, description FROM knowledge_base " .
        " WHERE id NOT IN(SELECT knowledge_base FROM permission WHERE user = ?) AND privacy = ? AND status = 1 ORDER BY id DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $privacy);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $kb_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $kb = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "description" => $row['description'],
                    "tags" => $this->getTags($row['id']),
                    "collaborators" => $this->getCollaborators($row['id'])
                );
                array_push($kb_arr, $kb);
            }
            return $kb_arr;
        }
        return array();
    }

    public function getListByUser($user_id){
        $query = "SELECT kb.id as id, kb.name as name FROM knowledge_base kb RIGHT JOIN permission p ON kb.id = p.knowledge_base WHERE p.user = ? ORDER BY kb.name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($user_id));
        if($stmt->rowCount() > 0){
            $kb_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $kb = array(
                    'id' => $row['id'],
                    'name' => $row['name']
                );
                array_push($kb_arr, $kb);
            }
            return $kb_arr;
        }
        return array();
    }

    public function getListByTag($tag){
        $query = "SELECT kb.id as id, kb.name as name, kb.description as description FROM knowledge_base kb " . 
        "RIGHT JOIN (document d RIGHT JOIN (document_tag dt RIGHT JOIN tag t ON dt.tag = t.id) ON d.id = dt.document) " . 
        "ON kb.id = d.knowledge_base WHERE t.name = ? AND kb.privacy = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($tag));
        if($stmt->rowCount() > 0){
            $kb_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $kb = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "description" => $row['description'],
                    "tags" => $this->getTags($row['id']),
                    "collaborators" => $this->getCollaborators($row['id'])
                );
                array_push($kb_arr, $kb);
            }
            return $kb_arr;
        }
        return array();
    }

    public function getTags($kb_id){
        $query = "SELECT DISTINCT t.name FROM tag t RIGHT JOIN" .
            " (document_tag dt RIGHT JOIN document d ON d.id = dt.document) ON t.id = dt.tag" .
            " WHERE d.knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($kb_id));

        if($stmt->rowCount() > 0){
            $tag_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($tag_arr, $row['name']);
            }
            return $tag_arr;
        }
        return array();
    }

    /**
     * @param $user
     * @param $knowledge_base
     * @param $role
     * @throws Exception
     */
    private function addPermission($user, $knowledge_base, $role)
    {
        $query = "INSERT INTO permission (user, knowledge_base, role)" .
            "VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $knowledge_base);
        $stmt->bindParam(3, $role);
        if(!$stmt->execute()){
            throw new Exception("Unable to add permission");
        }
    }

    /**
     * @param $user
     * @param $knowledge_base
     * @throws Exception
     */
    private function dropPermission($user, $knowledge_base)
    {
        $query = "DELETE FROM permission WHERE user = ? AND knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $knowledge_base);
        if(!$stmt->execute()){
            throw new Exception("Unable to change permission");
        }
    }

    /**
     * @param $user
     * @param $knowledge_base
     * @param $role
     * @throws Exception
     */
    private function changePermission($user, $knowledge_base, $role)
    {
        $query = "UPDATE permission SET role = ? WHERE user = ?  AND knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $role);
        $stmt->bindParam(2, $user);
        $stmt->bindParam(3, $knowledge_base);
        if(!$stmt->execute()){
            throw new Exception("Unable to change permission");
        }
    }

    /**
     * @param $id
     * @return array
     */
    private function getPermissions($id)
    {
        $query = "SELECT * FROM permission WHERE knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($id));

        if($stmt->rowCount() > 0){
            $per_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $permission = array(
                    "user" => $row['user'],
                    "role" => $row['role']
                );
                array_push($per_arr, $permission);
            }
            return $per_arr;
        }
        return array();
    }

    private function getRegister($kb_id)
    {
        $query = "SELECT * FROM register WHERE knowledge_base = ? ORDER BY date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($kb_id));

        if($stmt->rowCount() > 0){
            $register_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $register = array(
                    "user" => $row['user'],
                    "date" => $row['date'],
                    "folder" => $row['folder'],
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
     * @param $kb
     * @param $permissions
     * @throws Exception
     */
    private function updatePermissions($kb, $permissions)
    {
        $db_permissions = $this->getPermissions($kb);
        for($i = 0; $i <= count($permissions) - 1; $i++){
            $coincidence = false;
            for($j = 0; $j <= count($db_permissions) - 1; $j++){
                if($permissions[$i]['user'] == $db_permissions[$j]['user']){
                    $coincidence = true;
                    if(!($permissions[$i]['role'] == $db_permissions[$j]['role'])){
                        $this->changePermission($permissions[$i]['user'], $kb, $permissions[$i]['role']);
                    }
                    array_splice($db_permissions, $j, 1);
                    break;
                }
            }
            if(!$coincidence){
                $this->addPermission($permissions[$i]['user'], $kb, $permissions[$i]['role']);
            }
        }
        foreach($db_permissions as $per){
            $this->dropPermission($per['user'], $kb);
        }
    }

    public function getUserPermission($user_id, $kb_id)
    {
        $query = "SELECT * FROM permission WHERE user = ? AND knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $kb_id);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row['role'];
        }
    }

    /**
     * @param $user_id
     * @param $kb_id
     * @param $activity
     * @return bool
     * @throws Exception
     */
    public function checkPermission($user_id, $kb_id, $activity)
    {
        $query = "SELECT * FROM permission WHERE user = ? AND knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $kb_id);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->checker($row['role'], $activity);

        } else {
            throw new Exception("No permissions for this user");
        }
    }

    /**
     * @param $per
     * @param $activity
     * @return bool
     */
    private function checker($per, $activity)
    {
        switch ($per){
            case 1:
                $type = array("own", "work", "read");
                break;
            case 2:
                $type = array("work", "read");
                break;
            default:
                $type = array("read");
                break;
        }
        return (in_array($activity, $type));
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

    private function deleteRegisters($kb_id)
    {
        $query = "SELECT * FROM register WHERE knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($kb_id));

        if($stmt->rowCount() > 0){
            $registerService = new RegisterService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $registerService->deleteRegister($row['id']);
            }
        }
    }

    private function deletePermissions($kb_id)
    {
        $query = "DELETE FROM permission WHERE knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        if(!$stmt->execute(array($kb_id)))
        {
            throw new Exception("Unable to delete permissions");
        }
    }

    private function getCollaborators($kb_id)
    {
        $query = "SELECT u.username as username FROM user u RIGHT JOIN permission p ON u.id = p.user WHERE p.knowledge_base = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($kb_id));
        if($stmt->rowCount() > 0)
        {
            $col_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $col = $row['username'];
                array_push($col_arr, $col);
            }
            return $col_arr;
        }
        return array();
    }
}