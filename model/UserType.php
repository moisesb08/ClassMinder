<?php
require_once('User.php');

interface UserType
{
    public function getUser($email, $firstName, $lastName, $password);
}

// teacher user type
class User_Teacher implements UserType
{
    public function getUser($email, $firstName, $lastName, $password)
    {
        $user = new User($email, $firstName, $lastName, $password, 1);
        if($user->save()!=0)
            return $user;
        return null;
    }
}

// parent user type
class User_Parent implements UserType
{
    public function getUser($email, $firstName, $lastName, $password)
    {
        $user = new User($email, $firstName, $lastName, $password, 0);
        if($user->save()!=0)
            return $user;
        return null;
    }
}

?>