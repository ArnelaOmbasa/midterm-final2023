<?php
require_once __DIR__."/../dao/FinalDao.php";

class FinalService {
    protected $dao;

    public function __construct(){
        $this->dao = new FinalDao();
    }

    /** TODO
    * Implement service method to login user
    */
    public function login($data){

        return $this->dao->login($data);

    }

    /** TODO
    * Implement service method to add new investor to investor table and cap-table
    */
    public function investor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares){
        //if email is unique
        if($this->dao->check_email_exists($email)){
            // Email already exists, return error message
            return ['status' => 'error', 'message' => 'Email address already exists'];
        }
    
        // Check if the sum of diluted shares exceeds the authorized assets
        $total_shares = $this->dao->get_total_shares($share_class_id)['total_shares'];
        $authorized_assets = $this->dao->get_authorized_assets($share_class_id);
    
        if($total_shares + $diluted_shares > $authorized_assets) {
            // The sum of diluted shares exceeds the authorized assets, return error message
            return ['status' => 'error', 'message' => 'Sum of diluted shares exceeds authorized assets for this share class'];
        }
    
        // If all checks pass, insert the new investor and cap table record
        $investor_id = $this->dao->investor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares);
    
        // Return success message
        return ['status' => 'success', 'message' => 'Investor has been created successfully', 'investor_id' => $investor_id];
    }

    /** TODO
    * Implement service method to return list of all share classes from share_classes table
    */
    public function share_classes(){
        
        return $this->dao->share_classes();

    }

    /** TODO
    * Implement service method to return list of all share class categories from share_class_categories table
    */
    public function share_class_categories(){
        return $this->dao->share_class_categories();

    }
}
?>
