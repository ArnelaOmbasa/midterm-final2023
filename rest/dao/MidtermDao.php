<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor($first_name, $last_name, $email,$company){
        $sql = "INSERT INTO invesors (first_name, last_name, email,company)
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$first_name, $last_name, $email,$company]);


    }

    /** TODO
    * Implement DAO method to validate email format and check if email exists
    */
   
        public function investor_email($email){
            {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                   echo 'Invalid format';
                } else {
                    $query = "select * from investors where email = :email";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute(['email' => $email]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        return 'true';
                    } else {
                        return 'null';
                    }
                }
            }
       
    
        

    }

    /** TODO
    * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
    */

    public function investors($id){
        $query = "select sc.description, sc.equity_main_currency, sc.price, sc.authorized_assets, i.first_name, i.last_name, i.email, i.company, sum(ct.diluted_shares) as total_diluted_shares
        from investors i
        join cap_table ct on i.id = ct.investor_id
        join share_classes sc on ct.share_class_id = sc.id
        where sc.id = :id
        group by i.id;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addInvestor($investorData){
        $sql = "INSERT INTO investors (first_name, last_name, email, company) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$investorData['first_name'], $investorData['last_name'], $investorData['email'], $investorData['company']]);
         return $this->conn->lastInsertId();
    }
    

    public function addToCapTable($capTableData, $investorId){
        $sql = "INSERT INTO cap_table (share_class_id, share_class_category_id, investor_id, diluted_shares) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$capTableData['share_class_id'], $capTableData['share_class_category_id'], $investorId, $capTableData['diluted_shares']]);
    }

    public function validateEmail($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        $sql = "SELECT * FROM investors WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getInvestors($share_class_id){
        $sql = "SELECT i.*, c.description, c.equity_main_currency, c.price, c.authorized_assets FROM cap_table c
        INNER JOIN investors i ON c.investor_id = i.id WHERE c.share_class_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$share_class_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

}


?>
