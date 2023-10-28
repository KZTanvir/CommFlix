<?php
class User {	
   
	private $userTable = 'cms_user';	
	private $conn;
	//declaring public variables to prevent dynamic assignment - Tanvir
	public $id = null;
	public $first_name = null;
	public $last_name = null;
	public $email = null;
	public $profile_link = null;
	public $type = null;
	public $deleted = null;
	public $password = null;
	
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function login(){
		if($this->email && $this->password) {
			$sqlQuery = "
				SELECT * FROM ".$this->userTable." 
				WHERE email = ? AND password = ?";			
			$stmt = $this->conn->prepare($sqlQuery);
			$this->password = md5($this->password);
			$stmt->bind_param("ss", $this->email, $this->password);	
			$stmt->execute();
			$result = $stmt->get_result();
			if($result->num_rows > 0){
				$user = $result->fetch_assoc();
				if($user['deleted'] == 1){
				    return 2;
				    exit();
				}
				$_SESSION["userid"] = $user['id'];
				$_SESSION["status"] = $user['deleted'];
				$_SESSION["user_type"] = $user['type'];
				$_SESSION["name"] = $user['first_name']." ".$user['last_name'];
                $_SESSION["theme"] = false;		
				return 1;		
			} else {
				return 0;		
			}			
		} else {
			return 0;
		}
	}
	
	public function loggedIn (){
		if(!empty($_SESSION["userid"])) {
		    $owner = $this->getOwner();
		    
		    if (!$owner || !is_array($owner)) {
                header("location: logout.kz.php");
                exit();
            }
		    
		    if ($owner['deleted'] != $_SESSION['status']) {
                header("location: logout.kz.php");
                exit();
            }
            
		    if($owner['type'] != $_SESSION['user_type'] || ($owner['first_name']." ".$owner['last_name'] != $_SESSION['name'])){
		        $_SESSION['user_type'] = $owner['type'];
		        $_SESSION['name'] = $owner['first_name']." ".$owner['last_name'];
		    }
			return 1;
		} else {
			return 0;
		}
	}
	
    public function totalUser(){
        if ($_SESSION["user_type"] == 3) {
            $sqlQuery = "SELECT * FROM " . $this->userTable . " WHERE type = ?";
        } else if ($_SESSION['user_type'] == 2) {
            $sqlQuery = "SELECT * FROM " . $this->userTable . " WHERE type >= ?";
        } else if ($_SESSION['user_type'] == 1) {
            $sqlQuery = "SELECT * FROM " . $this->userTable;
        }
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        if ($stmt) {
            if ($_SESSION["user_type"] == 3 || $_SESSION['user_type'] == 2) {
                $stmt->bind_param("i", $_SESSION['user_type']);
            }
    
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->num_rows;
        } else {
            return false;
        }
    }   
	

    public function getUsersListing() {
        $whereQuery = '';    
        if ($_SESSION['user_type'] == 3) {
            $whereQuery = "WHERE type != 1";
        }    
    
        $sqlQuery = "
            SELECT id, first_name, last_name, email, type, deleted
            FROM ".$this->userTable."  
            $whereQuery ";
    
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $result = $stmt->get_result();    
    
        $users = array();        
        while ($user = $result->fetch_assoc()) {               
            $rows = array();    
            $status = $user['deleted'] ? 'Inactive' : 'Active';
    
            $type = '';
            switch ($user['type']) {
                case 1:
                    $type = 'Administrator';
                    break;
                case 2:
                    $type = 'Admin';
                    break;
                case 3:
                    $type = 'User';
                    break;
            }
            if ($user['id'] == $_SESSION['userid']) {
                $rows['edit'] = '<a href="add_users.kz.php?id=' . $user['id'] . '" class="edit">Edit</a>';
                $rows['delete'] = '<a class="delete" href="add_users.kz.php?id=' . $user['id'] . '&state=delete" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
            } else if ($_SESSION['user_type'] == 3) {
                $rows['edit'] = "None";
                $rows['delete'] = "None";
            } else if ($_SESSION['user_type'] == 2 && ($user['type'] == 1 || $user['type'] == 2)) {
                $rows['edit'] = "None";
                $rows['delete'] = "None";
            } else {
                $rows['edit'] = '<a href="add_users.kz.php?id=' . $user['id'] . '" class="edit">Edit</a>';
                $rows['delete'] = '<a class="delete" href="add_users.kz.php?id=' . $user['id'] . '&state=delete" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
            }
            
            $rows['id'] = $user['id'];
            $rows['user_name'] = ucfirst($user['first_name'])." ".$user['last_name'];
            $rows['email'] = $user['email'];
            $rows['type'] = $type;           
            $rows['status'] = $status;              
            $users[] = $rows;
        }
    
        return $users;    
    }
	
	public function getUserById($id){		
		if ($id) {
            $sqlQuery = "
                SELECT id, first_name, last_name, email, profile_link, type, deleted
                FROM " . $this->userTable . "
                WHERE id = ? ";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        
            // Check if $user is empty and return dummy data if true
            if (empty($user)) {
                $dummyUser = array(
                    'id' => $id,
                    'first_name' => 'Deleted',
                    'last_name' => 'User',
                    'email' => 'none',
                    'profile_link' => 'images/default.png',
                    'type' => 3,
                    'deleted' => 1
                );
        
                return $dummyUser;
            }
        
            return $user;
        }                
	}
	
    public function getOwner() {
        if(isset($_SESSION['userid'])) {
            $sqlQuery = "
            SELECT id, first_name, last_name, email, profile_link, type, deleted
            FROM ".$this->userTable." 			
            WHERE id = ? ";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bind_param("i", $_SESSION['userid']);	
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows === 0) {
                return false;
            }
    
            $user = $result->fetch_assoc();
            return $user;
        } else {
            return false;
        }
    }

    public function emailExists($email) {
        $sql = "SELECT COUNT(*) FROM {$this->userTable} WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }
    public function userExists($id) {
        $sql = "SELECT COUNT(*) FROM {$this->userTable} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }


    public function insert(){
        if ($this->emailExists($this->email) || !$this->email || !$this->password || $_SESSION['user_type'] == 3) {
            return false;
        }
    
        $sql = "";
        if($_SESSION['user_type'] == 1){
            $sql = "INSERT INTO {$this->userTable} (first_name, last_name, email, profile_link, password, type, deleted) VALUES (?, ?, ?, ?, ?, ?, ?)";
        }else if($_SESSION['user_type'] == 2){
            $this->type = 3;
            $sql = "INSERT INTO {$this->userTable} (first_name, last_name, email, profile_link, password, type, deleted) VALUES (?, ?, ?, ?, ?, ?, ?)";
        }
        $stmt = $this->conn->prepare($sql);
    
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->profile_link = htmlspecialchars(strip_tags($this->profile_link));
        $mailPass = $this->password;
        $this->password = md5($this->password); 
        
        $this->deleted = intval($this->deleted);
    
        $stmt->bind_param("sssssii", $this->first_name, $this->last_name, $this->email, $this->profile_link, $this->password, $this->type, $this->deleted);
    
        if ($stmt->execute()) {
            $User = $this->getUserById($stmt->insert_id);
            $subject = 'User Account Created.';
            $status = '';
            $rank = '';
            if($this->deleted == 1){
                $status = "Disabled/Banned.";
            }else{
                $status = "Active/Enabled.";
            }
            if($this->type == 1){
                $rank = "Administrator.";
            } else if($this->type == 2){
                $rank = "Admin";
            } else {
                $rank = "Normal User";
            }
            $email1 = new EmailSender();
            $body = 'Account Created.<br>Username : '.$this->first_name." ".$this->last_name. "<br>Status: ".$status."<br>Rank: ".$rank."<br>Password: ".$mailPass."<br>Note: Please Change your password!";
            $email1->sendEmail($User["email"], $this->first_name." ".$this->last_name, $subject, $body);
        return $stmt->insert_id; 
        } else {
            return false;
        }
    }
	
    public function update(){
        // Check if the necessary properties are set and user type is not 3
        if ($this->id && $this->email && ($_SESSION['user_type'] != 3 || $_SESSION['userid'] == $this->id)) {
            // Define the SQL query based on user type
            if ($_SESSION['user_type'] == 1) {
                $sql = "UPDATE {$this->userTable} 
                        SET first_name = ?, last_name = ?, email = ?, profile_link = ?, type = ?, deleted = ?
                        WHERE id = ?";
            } else if ($_SESSION['user_type'] == 2) {
                $this->type = 3; // Set user type to 3 for user type 2
                $sql = "UPDATE {$this->userTable} 
                        SET first_name = ?, last_name = ?, email = ?, profile_link = ?, type = ?, deleted = ?
                        WHERE id = ? AND type = 3";
            } else {
                $sql = "UPDATE {$this->userTable}
                        SET first_name = ?, last_name = ?,  profile_link = ?
                        WHERE id = ?";
            }
   
            // Prepare the statement
            $stmt = $this->conn->prepare($sql);
            // Sanitize and bind parameters
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->first_name = htmlspecialchars(strip_tags($this->first_name));
            $this->last_name = htmlspecialchars(strip_tags($this->last_name));
            $this->profile_link = htmlspecialchars(strip_tags($this->profile_link));
            
            if ($_SESSION['user_type'] == 3){
                $stmt->bind_param("sssi", $this->first_name, $this->last_name, $this->profile_link, $this->id);
            } else {
                $this->email = htmlspecialchars(strip_tags($this->email));
                $this->type = htmlspecialchars(strip_tags($this->type));
                $this->deleted = htmlspecialchars(strip_tags($this->deleted));
                $stmt->bind_param("sssssii", $this->first_name, $this->last_name, $this->email, $this->profile_link, $this->type, $this->deleted, $this->id);
            } 
            
            $oldUser = $this->getUserById($this->id);
            
            if ($stmt->execute()) {
                $subject = 'User Account Status.';
                $status = '';
                $rank = '';
                $emailsender = new EmailSender();
                if($_SESSION['user_type']==3){
                    return true;
                }
                if($this->deleted != $oldUser['deleted']){
                    if($this->deleted == 1){
                        $status = "Disabled/Banned.";
                    }else{
                        $status = "Active/Enabled.";
                    }
                    $body = 'Account Status Changed!<br>Username : '.$this->first_name." ".$this->last_name. "<br>Status: " . $status;
                    $emailsender->sendEmail($oldUser["email"], $this->first_name." ".$this->last_name, $subject, $body);
                }
                if($this->type != $oldUser['type']){
                    if($this->type == 1){
                        $rank = "Administrator.";
                    } else if($this->type == 2){
                        $rank = "Admin";
                    } else {
                        $rank = "Normal User";
                    }
                    $body = 'User Rank Changed.<br>Username : '.$this->first_name." ".$this->last_name."<br>Promoted/Demoted To: ".$rank;
                    $emailsender->sendEmail($oldUser['email'], $this->first_name." ".$this->last_name, $subject, $body);
                }
                return true;
            }
        }
        
        return false;
    }

	
   	public function delete(){
        // Check if the necessary properties are set
        $sql = "";
        if ($this->id && $this->userExists($this->id)) {
            if ($_SESSION['user_type'] == 1) {
                // User type 1 can delete any user
                $sql = "DELETE FROM {$this->userTable} WHERE id = ?";
            } elseif ($_SESSION['user_type'] == 2) {
                $sql = "DELETE FROM {$this->userTable} WHERE (id = ? AND type = 3) OR (id = {$_SESSION['userid']} AND type = 2)";
            } elseif ($_SESSION['user_type'] == 3) {
                $this->id = $_SESSION['userid'];
                $sql = "DELETE FROM {$this->userTable} WHERE id = ? AND type = 3";
            }
    
            $stmt = $this->conn->prepare($sql);
    
            // Sanitize and bind parameters
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bind_param("i", $this->id);
    
            // Execute the statement
            if ($stmt->execute()) {
                return true; 
            } else {
                return false;
            }
        }
        
        
        return false;
    }
    
    public function changePassword($ownerId, $confirmationCode, $newPassword){
        if ($ownerId && $confirmationCode && $newPassword) {
            $sql = "SELECT confirmation_code FROM {$this->userTable} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $ownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($confirmationCode === $user['confirmation_code']) {
                    $hashedPassword = md5($newPassword);
    
                    $sqlUpdate = "UPDATE {$this->userTable} 
                                SET password = ?
                                WHERE id = ?";
    
                    $stmtUpdate = $this->conn->prepare($sqlUpdate);
                    $hashedPassword = htmlspecialchars(strip_tags($hashedPassword));
                    $_SESSION['userid'] = htmlspecialchars(strip_tags($ownerId));
                    $stmtUpdate->bind_param("si", $hashedPassword, $ownerId);
    
                    if ($stmtUpdate->execute()) {
                        $sqlClearCode = "UPDATE {$this->userTable} 
                                        SET confirmation_code = NULL
                                        WHERE id = ?";
                        $stmtClearCode = $this->conn->prepare($sqlClearCode);
                        $stmtClearCode->bind_param("i", $ownerId);
                        $stmtClearCode->execute();
    
                        return true;
                    }
                }
            }
        }
    
        return false;
    }
    
    private function generateConfirmationCode($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
    
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
    
        return $code;
    }
    
    public function sendConfirmationEmail($email) {
        $confirmationCode = $this->generateConfirmationCode();
    
        $sqlUpdateCode = "UPDATE {$this->userTable} SET confirmation_code = ? WHERE email = ?";
        $stmtUpdateCode = $this->conn->prepare($sqlUpdateCode);
        $confirmationCode = htmlspecialchars(strip_tags($confirmationCode));
        $email = htmlspecialchars(strip_tags($email));
        $stmtUpdateCode->bind_param("ss", $confirmationCode, $email);
        $stmtUpdateCode->execute();
    
        if ($stmtUpdateCode->affected_rows > 0) {
            $emailSender = new EmailSender();
    
            // Email sending logic
            $subject = 'Password Change Confirmation';
            $body = 'Your confirmation code: ' . $confirmationCode;
    
            $result = $emailSender->sendEmail($email, $_SESSION['name'], $subject, $body);
    
            return $result;
        }
    
        return false;
    }

    public function register(){
        if (!$this->email || !$this->first_name || !$this->last_name || isset($_SESSION['user_type'])) {
            return 0;
        }
        if ($this->emailExists($this->email)) {
            return 2;
        }

        $sql = "INSERT INTO {$this->userTable} (first_name, last_name, email, profile_link, password, type, deleted) VALUES (?, ?, ?, 'images/default.png', ?, ?, ?)";

        
        $stmt = $this->conn->prepare($sql);
        
        $this->password = $this->generateConfirmationCode(20);
        
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $mPass = $this->password;
        $this->password = md5($this->password);
        $this->type = 3; 
        $this->deleted = 1;

        $stmt->bind_param("ssssii", $this->first_name, $this->last_name, $this->email, $this->password, $this->type, $this->deleted);

        
        if ($stmt->execute()) {
            $emailSender = new EmailSender();
            $subject = 'User Registration Successfull';
            $body = 'Username : '.$this->first_name." ".$this->last_name. "<br>Password: " . $mPass."<br>Note: Please wait for the admin to approve to log in. (Status: Disabled)";
            $result = $emailSender->sendEmail($this->email, $this->first_name." ".$this->last_name, $subject, $body);
            
            return 1;
        } else {
            
            return 0;
        }
    }
    
    public function resetPass() {
        $this->password = $this->generateConfirmationCode();
        $openPassword = $this->password;
        $sqlUpdateCode = "UPDATE {$this->userTable} SET password = ? WHERE email = ?";
        $stmtUpdateCode = $this->conn->prepare($sqlUpdateCode);
        $this->password = md5($this->password);
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmtUpdateCode->bind_param("ss", $this->password, $this->email);
        $stmtUpdateCode->execute();
    
        if ($stmtUpdateCode->affected_rows > 0) {
            $emailSender = new EmailSender();
    
            // Email sending logic
            $subject = 'Password Reset Confirmation.';
            $body = 'Email: '.$this->email.'<br>New Password: ' . $openPassword;
    
            $result = $emailSender->sendEmail($this->email, $_SESSION['name'], $subject, $body);
    
            return $result;
        }
    
        return false;
    }


}
?>
