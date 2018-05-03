<?php
require_once('UserType.php');

class UserFactory
{
    public function getUserType($role)
    {
        $role_type = 'User_' . ucwords($role);
        // Generate UserType object based on given role
        if($role_type=="User_Teacher")
            return new User_Teacher();    
        else if($role_type=="User_Parent")
            return new User_Parent();
        return null;
    }
}
?>