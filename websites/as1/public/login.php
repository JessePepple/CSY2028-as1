<?php
require_once('head.php');
require_once('helpers.php');

/**
 * Logs a user into the system
 * @param string $email
 * @param string $password
 * @param mixed $db
 * @return array
 */
function login(string $email, string $password, $db)
{
    $success = false;
    $errors = [];
    $user_data = '';

    if(empty($email) || empty($password))
    {
        $errors[] = 'Your email and password is required!';
    }

    // check for this email in the db

    $sql = "SELECT * FROM users WHERE email = ?";
    $query = $db->prepare($sql);
    $query->execute([$email]);
    $result = $query->fetchAll();

    if(!$result)
    {
        $errors[] = 'We could not find this email, please try again';
    }

    if(empty($errors))
    {
        // now we compare the hashed password in the db to the supplied password by this user
        $data = $result;
        $hashed_pass = $data[0]['password'];

        if(!password_verify($password, $hashed_pass))
        {
            $errors[] = "You've provided a wrong password, please try again";
        }

        else
        {
            $success = true;
        }
    }

    return ['success' => $success, 'error' => $errors, 'data' => $data[0] ?? []];
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $login = login($email, $password, $db);

    if(!$login['success'])
    {
        $form_errors = $login['error'];
    }

    else
    {
        $user_data = $login['data'];
        // set session data
        $_SESSION['id'] = $user_data['id'];
        $_SESSION['is_admin'] = $user_data['is_admin'];
        $_SESSION['email'] = $user_data['email'];

        header("Location: index.php");
        exit;
    }
}

$page_title = "Login";
include_once('header.php');

?>

<h1>Login</h1>
<?php if(isset($_GET['register'])): ?>
    <div style="color:green; font-weight: bold; padding: 15px;">
    You have successfully registered. Please login
    </div>
<?php endif ?>

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
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Enter your email" required="required" />

    <label for="password">Password</label>
    <input type="password" name="password" placeholder="Your password" id="password" required="required" />

    <input type="submit" name="submit" value="Login" />
</form>

<?php include_once('footer.php'); ?>