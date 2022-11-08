<?php
include_once('header.php');

// when we submit the form
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if( empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']))
    {
        $error = 'You need to fill the required fields';
    }
    else
    {
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];

        // lets check if email exists
    }

}
?>

<h1>Register</h1>

<form action="" method="post">
    <label for="name">Full name</label>
    <input type="text" name="name" id="name" placeholder="Your full name" required="required" />

    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Please use a valid email" required="required" />

    <label for="password">Password</label>
    <input type="password" name="password" placeholder="Your password" id="password" required="required" />

    <input type="submit" value="Register" />
</form>