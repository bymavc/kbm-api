<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/17/2018
 * Time: 2:41 PM
 */

class AuthService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param $username
     * @param $password
     * @return Auth
     * @throws Exception
     */
    public function init($username, $password)
    {
        $userService = new UserService($this->conn);
        if(validateEmail($username)){
            $user = $userService->getByEmail($username);
        } else {
            $user = $userService->getByUsername($username);
        }
        if(password_verify($password, $user->getPassword()) && $user->getStatus() == 1){
            $auth = new Auth();
            $auth->setUser($user->getId());
            $auth->setToken($this->generateToken());
            $auth->setDate(date('Y-m-d h:i:s'));

            return $this->create($auth);
        } else {
            throw new Exception("Unable init session");
        }
    }

    /**
     * @param $token
     * @throws Exception
     */
    public function end($token)
    {
        $query = "DELETE FROM auth WHERE token = ?";
        $stmt = $this->conn->prepare($query);

        if(!$stmt->execute(array($token))){
            throw new Exception("Unable to end session");
        }
    }

    public function endAllSessions($user)
    {
        $query = "DELETE FROM auth WHERE user = ?";
        $stmt = $this->conn->prepare($query);

        if(!$stmt->execute(array($user))){
            throw new Exception("Unable to end sessions");
        }
    }

    /**
     * @param $token
     * @return Auth
     * @throws Exception
     */
    public function getAuth($token)
    {
        $query = "SELECT * FROM auth WHERE token = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($token));

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $auth = new Auth();
            $auth->setId($row['id']);
            $auth->setToken($row['token']);
            $auth->setUser($row['user']);
            $auth->setDate($row['date']);

            return $auth;
        } else {
            throw new Exception("Unable to get session");
        }
    }

    /**
     * @param $auth
     * @return Auth
     * @throws Exception
     */
    private function create($auth)
    {
        $query = "INSERT INTO auth (token, user, date) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $auth->getToken());
        $stmt->bindParam(2, $auth->getUser());
        $stmt->bindParam(3, $auth->getDate());

        if(!$stmt->execute()){
            throw new Exception("Unable to start session");
        }
        return $this->getAuth($auth->getToken());
    }

    private function generateToken()
    {
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $string_length = strlen($string);
        $token = '';
        while(strlen($token) < 30){
            $token .= $string[rand(0, $string_length - 1)];
        }
        $token = hash( 'SHA256', $token);
        if(!$this->checkToken($token)){
            return $token;
        } else {
            return $this->generateToken();
        }
    }

    private function checkToken($token){
        $query = "SELECT * FROM auth WHERE token = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array($token));

        if($stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }
}