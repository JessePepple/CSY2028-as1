<?php
require_once('head.php');
include_once('helpers.php');

/** 
 * Function to register new user
 * @param string $name user name
 * @param string $email user email address
 * @param string $password user password
 * @param mixed $db
 * @return array
 **/
function register_user(string $name, string $email, string $password, $db)
{
    $success = false;
    $error = [];

    if( empty($name) || empty($email) || empty($password))
    {
        $error[] = 'You need to fill the required fields';
    }

    if(mail_exists($email, $db))
    {
        $error[] = 'The email address already exists';
    }

    // if error is empty we can register this user
    if(empty($error))
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(name, email, password) VALUES(?, ?, ?)";

        $query = $db->prepare($sql);

        $result = $query->execute([$name, $email, $password]);

        if(!$result)
        {
            $error[] = 'An internal error occurred';
        }
        else
        {
            $success = true;
        }
    }

    return ['success' => $success, 'error' => $error];

}

// when we submit the form
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $register = register_user($name, $email, $password, $db);

    if(!$register['success'])
    {
        $form_errors = $register['error'];
    }

    else
    {
        header("Location: login.php");
        exit;
    }
}

include_once('header.php');
?>

<h1>Register</h1>
<?php if(isset($form_errors)): ?>
    <div style="color:red; font-weight: bold; padding: 15px;">
    <?php 
        foreach($form_errors as $err):

            echo $err . '<br/>';

        endforeach;
    ?>
    </div>
<?php endif ?>

<form action="" method="post">
    <label for="name">Full name</label>
    <input type="text" name="name" id="name" placeholder="Your full name" required="required" />

    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Please use a valid email" required="required" />

    <label for="password">Password</label>
    <input type="password" name="password" placeholder="Your password" id="password" required="required" />

    <input type="submit" value="Register" />
</form>

<?php include_once('footer.php'); ?>