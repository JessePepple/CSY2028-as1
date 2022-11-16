<?php
require_once('head.php');
require_once('helpers.php');
$page_title = 'New Category';

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit;
}
if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("Location: adminCategories.php");
    exit;
}
if (!$_SESSION['is_admin'])
{
    include_once('header.php');
    echo('<p>You need to be an admin to view this page</p>');
    include_once('footer.php');
    exit;
}

$category = get_category($_GET['id'], $db);

if(!$category)
{
    header("Location: adminCategories.php");
    exit;
}
/**
 * Edits a category
 * @param string $name The category name
 * @param int $id The category id
 * @param $db The database connection
 */
function edit_category(string $name, int $id, $db)
{
    if(empty($name)) return false;

    $sql = "UPDATE category SET name = ? WHERE id = ?";
    $query = $db->prepare($sql);

    return $query->execute([$name, $id]);
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $name = $_POST['name'] ?? '';

    if(edit_category($name, $category['id'], $db))
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

<h1>Edit Category</h1>

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
    <input type="text" id="title" name="name" placeholder="Category name" required="required" value="<?= form_value('name', $category['name']) ?>" />

    <input type="submit" value="Submit" />

</form>

<?php include_once('footer.php'); ?>
    