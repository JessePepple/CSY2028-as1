<?php
require_once('head.php');
require_once('helpers.php');
$page_title = 'New Category';

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit;
}

if (!$_SESSION['is_admin'])
{
    include_once('header.php');
    echo('<p>You need to be an admin to view this page</p>');
    include_once('footer.php');
    exit;
}

if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("Location: manageAdmins.php");
    exit;
}

$user = get_user($_GET['id'], $db);

if(!$user)
{
    header("Location: manageAdmins.php");
    exit;
}

/**
 * Changes a user password
 * @param int $user_id
 * @param string $new_password
 * @param $db
 */
function change_password(int $user_id, string $new_password, $db)
{
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $query  = $db->prepare($sql);
    if(!$query->execute([$new_password, $user_id])) return false;

    return true;

}
/** 
 * Edits user data in the database
 * @param int $user_id
 * @param string $name user name
 * @param string $email user email address
 * @param mixed $db
 * @param string $password user new password
 * @return array
 **/
function edit_user(int $user_id, string $name, string $email, $db, string $password = '')
{
    $success = false;
    $error = [];

    $user = get_user($user_id, $db);

    if( empty($name) || empty($email))
    {
        $error[] = 'You need to fill the required fields';
    }

  
    if(mail_exists($email, $db, $user['email']))
    {
        $error[] = 'The email address already exists';
    }

    // if error is empty we can register this user
    if(empty($error))
    {
        if(!empty($password))
        {
         
            $new_password = password_hash($password, PASSWORD_DEFAULT);
            change_password($user['id'], $new_password, $db);
          
           
        }
       
        if(empty($error))
        {
            $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";

            $query = $db->prepare($sql);
    
            $result = $query->execute([$name, $email, $user_id]);
    
            if(!$result)
            {
                $error[] = 'An internal error occurred';
            }
            else
            {
                $success = true;
            }
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

    $register = edit_user($user['id'], $name, $email, $db, $password);

    if(!$register['success'])
    {
        $form_errors = $register['error'];
    }

    else
    {
        header("Location: manageAdmins.php?edit=true");
        exit;
    }
}
$page_title = 'Create Admin';
include_once('header.php');
?>

<h1>Edit admin account</h1>
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
    <input type="text" name="name" id="name" placeholder="Full name" required="required" value="<?= form_value('name', $user['name']) ?>" />

    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Email address" required="required" value="<?= form_value('email', $user['email']) ?>" />

    <label for="password">New Password (Optional)</label>
    <input type="password" name="password" placeholder="Password" id="new_password" />

    <input type="submit" value="Edit" />
</form>

<?php include_once('footer.php'); ?>
    