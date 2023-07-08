<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

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
    }
    


    

    /** TODO
    * Implement DAO method to validate email format and check if email exists
    */
   
    public function investor_email($email){
        $query= "SELECT * FROM investors WHERE email=:email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    

    /** TODO
    * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
    */

    public function investors($share_class_id){
        $query = "select sc.description, sc.equity_main_currency, sc.price, sc.authorized_assets, i.first_name, i.last_name, i.email, i.company, 
        sum(ct.diluted_shares) as total_diluted_shares
        from investors i
        join cap_table ct on i.id = ct.investor_id
        join share_classes sc on ct.share_class_id = sc.id
        where sc.id = :id
        group by i.id;";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $share_class_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

}


?>
