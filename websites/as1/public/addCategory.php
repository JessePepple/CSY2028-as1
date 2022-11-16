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

/**
 * Creates a new category
 * @param string $name The category name
 * @param $db The database connection
 */
function create_category(string $name, $db)
{
    if(empty($name)) return false;

    $sql = "INSERT INTO category(name) VALUES(?)";
    $query = $db->prepare($sql);

    return $query->execute([$name]);
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $name = $_POST['name'] ?? '';

    if(create_category($name, $db))
    {
        header("Location: adminCategories.php");
        exit;
    }
    else
    {
        $form_errors = ['The category name cannot be empty!'];
    }
}

include_once('header.php');
?>

<h1>Create New Category</h1>

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
    <label for="title">Name</label>
    <input type="text" id="title" name="name" placeholder="Category name" required="required" value="<?= form_value('name') ?>" />

    <input type="submit" value="Submit" />

</form>

<?php include_once('footer.php'); ?>
    