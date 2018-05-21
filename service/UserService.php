<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/15/2018
 * Time: 12:04 AM
 */

class UserService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /**
     * @param User $user
     * @return User
     * @throws Exception
     */
    public function create(User $user)
    {
        $query = "INSERT INTO user (username, email, password, first_name, last_name, status)" .
            " VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user->getUsername());
        $stmt->bindParam(2, $user->getEmail());
        $stmt->bindParam(3, $user->getPassword());
        $stmt->bindParam(4, $user->getFirstName());
        $stmt->bindParam(5, $user->getLastName());
        $stmt->bindParam(6, $user->getStatus());

        if($stmt->execute()) {
            $user = $this->getById($this->conn->lastInsertId());
            $registerService = new RegisterService($this->conn);
            $registerService->addUserRegister(
                $user->getId(),
                date('Y-m-d h:i:s'),
                'User created'
            );
            return $user;
        } else {
            throw new Exception("Unable to create user");
        }
    }

    /**
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function update(User $user)
    {
        $query = "UPDATE user SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?, status = ?, profile_picture = ?" .
            " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user->getUsername());
        $stmt->bindParam(2, $user->getEmail());
        $stmt->bindParam(3, $user->getPassword());
        $stmt->bindParam(4, $user->getFirstName());
        $stmt->bindParam(5, $user->getLastName());
        $stmt->bindParam(6, $user->getStatus());
        $stmt->bindParam(7, $user->getProfilePicture());
        $stmt->bindParam(8, $user->getId());

        if(!$stmt->execute()) {
            throw new Exception("Unable to update user");
        }
        $registerService = new RegisterService($this->conn);
        return $registerService->addUserRegister($user->getId(), date('Y-m-d h:i:s'), 'User updated');
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function delete(User $user)
    {
        $query = "UPDATE user SET status = 2 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if(!$stmt->execute(array($user->getId()))){
            throw new Exception("Unable to delete user");
        }
        $registerService = new RegisterService($this->conn);
        $registerService->addUserRegister($user->getId(), date('Y-m-d h:i:s'), 'User deleted');
    }

    /**
     * @param $id
     * @return User
     * @throws Exception
     */
    public function getById($id)
    {
        $query = "SELECT * FROM user WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($id));

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->fillUser($row);
        } else {
            throw new Exception("Unable to get user by id");
        }
    }

    /**
     * @param $username
     * @return User
     * @throws Exception
     */
    public function getByUsername($username)
    {
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($username));

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->fillUser($row);
        } else {
            throw new Exception("Unable to get user by username");
        }
    }

    /**
     * @param $email
     * @return User
     * @throws Exception
     */
    public function getByEmail($email)
    {
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($email));

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->fillUser($row);
        } else {
            throw new Exception("Unable to get user by email");
        }
    }

    public function getList(User $user)
    {
        $query = "SELECT * FROM user WHERE id != ? ORDER BY username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($user->getId()));

        if($stmt->rowCount() > 0){
            $users = array();
            $kbService = new KnowledgeBaseService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $u = array(
                    'username' => $row['username'],
                    'profile_picture' => $row['profile_picture'],
                    'knowledge_bases' => $kbService->getListByUser($row['id'])
                );
                array_push($users, $u);
            }
            return $users;
        }
        return array();
    }

    /**
     * @param $username
     * @return bool
     */
    public function checkUsername($username)
    {
        $query = "SELECT id FROM user WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($username));
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }

    /**
     * @param $email
     * @return bool
     */
    public function checkEmail($email)
    {
        $query = "SELECT id FROM user WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($email));
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }

    private function fillUser($row)
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setUsername($row['username']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password']);
        $user->setFirstName($row['first_name']);
        $user->setLastName($row['last_name']);
        $user->setStatus($row['status']);
        $user->setProfilePicture($row['profile_picture']);
        $user->setRegister($this->getRegister($user->getId()));

        return $user;
    }

    private function getRegister($user)
    {
        $query = "SELECT * FROM user_register WHERE user = ? ORDER BY date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($user));
        if($stmt->rowCount() > 0){

            $register_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $register = array(
                    "date" => $row['date'],
                    "description" => $row['description']
                );

                array_push($register_arr, $register);
            }
            return $register_arr;
        }
        return array();
    }

    public function find($pattern)
    {
        $pattern = '%' . $pattern . '%';
        $query = "SELECT * FROM user WHERE username LIKE ? OR email LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pattern);
        $stmt->bindParam(2, $pattern);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $user_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $user = array(
                    "id" => $row['id'],
                    "username" => $row['username'],
                    "email" => $row['email'],
                    "first_name" => $row['first_name'],
                    "last_name" => $row['last_name'],
                    "profile_picture" => $row['profile_picture']
                );
                array_push($user_arr, $user);
            }
            return $user_arr;
        }
        return array();
    }

    /**
     * @param $user
     * @return array
     * @throws Exception
     */
    public function getActions($user){
        $query = "SELECT r.id, r.knowledge_base as knowledge_base_id, kb.name as knowledge_base_name, r.folder as folder_id, " .
            "f.name as folder_name, f.parent_folder as parent_folder, r.document as document_id, d.name as document_name, r.user, r.date, r.description " .
            "FROM knowledge_base kb RIGHT JOIN (folder f RIGHT JOIN (document d RIGHT JOIN register r ON d.id = r.document) " .
            "ON f.id = r.folder) ON kb.id = r.knowledge_base WHERE user = ? GROUP BY date ORDER BY id DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($user));

        if($stmt->rowCount() > 0){
            $activities_arr = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $register = array(
                    "knowledge_base_id" => $row['knowledge_base_id'],
                    "knowledge_base_name" => $row['knowledge_base_name'],
                    "folder_id" => $row['folder_id'],
                    "folder_name" => $row['folder_name'],
                    "parent_folder" => $row['parent_folder'],
                    "document_id" => $row['document_id'],
                    "document_name" => $row['document_name'],
                    "date" => $row['date'],
                    "description" => $row['description']
                );
                array_push($activities_arr, $register);
            }
            return $activities_arr;
        }
        return array();
    }

    public function getRoles($user){
        $query = "SELECT kb.id, kb.name, kb.description, kb.privacy, p.role FROM permission p LEFT JOIN knowledge_base kb ON p.knowledge_base = kb.id WHERE p.user = ? AND kb.status = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($user));

        if($stmt->rowCount() > 0){
            $roles_arr = array();
            $kbService = new KnowledgeBaseService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $role = array(
                    "knowledge_base_id" => $row['id'],
                    "knowledge_base_name" => $row['name'],
                    "knowledge_base_description" => $row['description'],
                    "privacy" => $row['privacy'],
                    "role" => $row['role'],
                    "tags" => $kbService->getTags($row['id'])
                );

                array_push($roles_arr, $role);
            }
            return $roles_arr;
        }
        return array();
    }

    public function getKnowledgeBases($user){
        $query = "SELECT kb.id, kb.name, kb.description, kb.privacy, p.role FROM permission p LEFT JOIN knowledge_base kb ON p.knowledge_base = kb.id WHERE p.user = ? AND kb.status = 1 AND p.role = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($user));

        if($stmt->rowCount() > 0){
            $roles_arr = array();
            $kbService = new KnowledgeBaseService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $role = array(
                    "knowledge_base_id" => $row['id'],
                    "knowledge_base_name" => $row['name'],
                    "knowledge_base_description" => $row['description'],
                    "privacy" => $row['privacy'],
                    "role" => $row['role'],
                    "tags" => $kbService->getTags($row['id'])
                );
                array_push($roles_arr, $role);
            }
            return $roles_arr;
        }
        return array();
    }

    public function getCollaborations($user){
        $query = "SELECT kb.id, kb.name, kb.description, kb.privacy, p.role FROM permission p LEFT JOIN knowledge_base kb ON p.knowledge_base = kb.id WHERE p.user = ? AND kb.status = 1 AND p.role != 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($user));

        if($stmt->rowCount() > 0){
            $roles_arr = array();
            $kbService = new KnowledgeBaseService($this->conn);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $role = array(
                    "knowledge_base_id" => $row['id'],
                    "knowledge_base_name" => $row['name'],
                    "knowledge_base_description" => $row['description'],
                    "privacy" => $row['privacy'],
                    "role" => $row['role'],
                    "tags" => $kbService->getTags($row['id'])
                );
                array_push($roles_arr, $role);
            }
            return $roles_arr;
        }
        return array();
    }
}