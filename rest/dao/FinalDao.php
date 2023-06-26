<?php
require_once "BaseDao.php";

class FinalDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

    /** TODO
    * Implement DAO method used login user
    */
    public function login(){
        return $this->query("SELECT * FROM users WHERE email = :email", ['email' => $email]);

    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor(){

    }

    /** TODO
    * Implement DAO method to return list of all share classes from share_classes table
    */
    public function share_classes(){
        $stmt = $this->conn->prepare("SELECT * FROM share_classes" );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /** TODO
    * Implement DAO method to return list of all share class categories from share_class_categories table
    */
    public function share_class_categories(){
        $stmt = $this->conn->prepare("SELECT * FROM share_classes_categories" );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
}
?>
