<?php 

/**
 * Account Page Controller
 * @category  Controller
 */
class AccountController extends SecureController{
	/**
     * Index Action
     * @return View
     */
	function index(){
		$db = $this->GetModel();
		$db->where ("ID", USER_ID);
		$user = $db->getOne('UserTbl' , '*');
		render_json($user);
	}
	
	
	/**
        * Edit Record Action 
        * If Not $_POST Request, Display Edit Record Form View
        * @return View
        */
	function edit(){
		$db = $this->GetModel();
		if(is_post_request()){
			
			
			$modeldata=transform_request_data($_POST);
			 
			
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['UserName'])){
				$db->where('UserName',$modeldata['UserName'])->where('ID',USER_ID,'!=');
				if($db->has('UserTbl')){
					render_error($modeldata['UserName']." Already exist!");
				}
			} 
			
			$db->where('ID' , USER_ID);
			$bool = $db->update('UserTbl',$modeldata);
			if($bool){
				$db->where ('ID', USER_ID);
				$user = $db->getOne('UserTbl' , '*');
				set_session('user_data',$user);

				
				
				render_json(USER_ID);
			}
			else{
				render_error($db->getLastError());
			}
			return null;
		}
		else{
			$fields=array('ID','Name','Surname','UserName','Role','AccountStatus','Created','Modified');
			$db->where('ID' , USER_ID);
			$data = $db->getOne('UserTbl',$fields);
			
			if(!empty($data)){
				render_json($data);
			}
			else{
				if($db->getLastError()){
					render_error($db->getLastError());
				}
				else{
					render_error("Record not found",404);
				}
			}
		}
	}

	
	/**
     * Change Email Action
     * @return View
     */
	function change_email(){
		if(is_post_request()){
			
			$form_collection = $_POST;
			$email=trim($form_collection['Email']);
			
			
			$db = $this->GetModel();
			
			$db->where ("ID", USER_ID);
			$result = $db->update('UserTbl', array('Email' => $email ));
			if($result){
				
				set_flash_msg("Email address changed successfully",'success');
				redirect_to_page("#/account");
			}
			else{
				$this->view->form_error="Email Not Changed ";
				$this->view->render("account/change_email.php" ,null,"default_layout.php");
			}
		}
		else{
			$this->view->render("account/change_email.php" ,null,"default_layout.php");
		}
	}
}
