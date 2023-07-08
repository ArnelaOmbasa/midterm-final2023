<?php
require_once "BaseDao.php";

class FinalDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

    /** TODO
    * Implement DAO method used login user
    */
    public function login($email){
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchAll();

    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function check_email_exists($email){
        $query="SELECT * FROM investors WHERE email=:email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_total_shares($share_class_id){
        $query="SELECT SUM(diluted_shares) as total_shares FROM cap_table WHERE share_class_id= :share_class_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':share_class_id', $share_class_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_authorized_assets($share_class_id){
        $query = "SELECT authorized_assets FROM share_classes WHERE id = :share_class_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':share_class_id', $share_class_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares){
        try{
        $this->conn->beginTransaction();
       
        $query = "INSERT INTO investors (first_name, last_name, email, company) VALUES (:first_name, :last_name, :email, :company)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':company', $company);
        $stmt->execute();

        $investor_id = $this->conn->lastInsertId();

        $query = "INSERT INTO cap_table (share_class_id, share_class_category_id, investor_id, diluted_shares) VALUES (:share_class_id, :share_class_category_id, :investor_id, :diluted_shares)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':share_class_id', $share_class_id);
        $stmt->bindParam(':share_class_category_id', $share_class_category_id);
        $stmt->bindParam(':investor_id', $investor_id);
        $stmt->bindParam(':diluted_shares', $diluted_shares);
        $stmt->execute();

        return $investor_id;
        $this->conn->commit();}
        catch(PDOException $e){
            $this->conn->rollback();
            echo "Error: " . $e->getMessage();
        }}
    

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
        $stmt = $this->conn->prepare("SELECT * FROM share_class_categories" );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
}
?>
