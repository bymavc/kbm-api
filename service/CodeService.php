<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/16/2018
 * Time: 12:07 PM
 */

class CodeService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param Code $code
     * @return Code
     * @throws Exception
     */
    public function create(Code $code)
    {
        $query = "INSERT INTO code (user, code, type, date) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $code->getUser());
        $stmt->bindParam(2, $code->getCode());
        $stmt->bindParam(3, $code->getType());
        $stmt->bindParam(4, $code->getDate());

        if($stmt->execute()){
            return $this->getByCode($code->getCode());
        } else {
            throw new Exception("Unable to create code");
        }
    }

    /**
     * @param Code $code
     * @return Code
     * @throws Exception
     */
    public function update(Code $code)
    {
        $query = "UPDATE code SET user = ?, code = ?, type = ?, date = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $code->getUser());
        $stmt->bindParam(2, $code->getCode());
        $stmt->bindParam(3, $code->getType());
        $stmt->bindParam(4, $code->getDate());
        $stmt->bindParam(5, $code->getId());

        if($stmt->execute()){
            return $this->getByCode($code->getCode());
        } else {
            throw new Exception("Unable to update code");
        }
    }

    /**
     * @param $code
     * @return Code
     * @throws Exception
     */
    public function getByCode($code)
    {
        $query = "SELECT * FROM code WHERE code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($code));
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $code = new Code();
            $code->setId($row['id']);
            $code->setUser($row['user']);
            $code->setCode($row['code']);
            $code->setDate($row['date']);
            $code->setType($row['type']);

            return $code;
        } else {
            throw new Exception("Code does not exists");
        }
    }

    public function getByUserAndType($user, $type)
    {
        $query = "SELECT * FROM code WHERE user = ? AND type = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $type);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $code = new Code();
            $code->setId($row['id']);
            $code->setUser($row['user']);
            $code->setCode($row['code']);
            $code->setDate($row['date']);
            $code->setType($row['type']);

            return $code;
        } else {
            throw new Exception("Code does not exists");
        }
    }

    /**
     * @param $id
     * @throws Exception
     */
    public function releaseCode($id)
    {
        $query = "DELETE FROM code WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if(!$stmt->execute(array($id))){
            throw new Exception("Unable to release code");
        }
    }

    public function generateCode($length)
    {
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $string_length = strlen($string);
        $code = '';
        while(strlen($code) < $length){
            $code .= $string[rand(0, $string_length - 1)];
        }
        return $code;
    }
}