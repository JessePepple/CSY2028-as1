<?php
require_once('head.php');
require_once('helpers.php');
$page_title = 'Admin Categories';
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

/**
 * Gets all admin users
 * @param $db
 */
function get_admin_users($db)
{
    $sql = "SELECT * FROM users WHERE is_admin = 1";
    $query = $db->prepare($sql);
    $query->execute();

    $result = $query->fetchAll();

    return $result;
}

$admin_users = get_admin_users($db);

include_once('header.php');

if(isset($_GET['delete']) && $_GET['delete'] == 'true')
{
    echo '<p style="color: green; padding: 20px">User deleted successfully</p>';
}
if(isset($_GET['delete']) && $_GET['delete'] == 'false')
{
    echo '<p style="color: red; padding: 20px">An error ocurred, unable to delete user</p>';
}

if(isset($_GET['edit']) && $_GET['edit'] == 'true')
{
    echo '<p style="color: green; padding: 20px">User updated successfully</p>';
}

if(isset($_GET['create']) && $_GET['create'] == 'true')
{
    echo '<p style="color: green; padding: 20px">User created successfully</p>';
}

if(!$admin_users):
?>
    <p>No admin accounts found</p>
<?php else: ?>

    <h1>Admin List</h1>
    <a href="addAdmin.php"><button style="border:0;margin:10px;padding:5px">ADD NEW ADMIN</button></a>
    <ul class="productList">
    <?php
    foreach($admin_users as $row):
        ?>
        <li>
            <article>
                <h2><?= $row['email'] ?></h2>
                <p>
                    <a href="/editAdmin.php?id=<?= $row['id'] ?>">Edit</a> &nbsp; &nbsp;
                    <a href="/deleteAdmin.php?id=<?= $row['id'] ?>" style="color:red" onclick="return confirm('Are you sure you want to delete ?')">Delete</a>
                </p>
            </article>
        </li>

        <?php
    endforeach;
endif;
?>


<?php
include_once('footer.php');
?>
