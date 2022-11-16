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

include_once('header.php');

if(!$categories):
?>
    <p>No categories found</p>
<?php else: ?>

    <h1>Category listing</h1>
    <a href="addCategory.php"><button style="border:0;margin:10px;padding:5px">ADD NEW CATEGORY</button></a>
    <ul class="productList">
    <?php
    foreach($categories as $row):
        ?>
        <li>
            <img src="product.png" alt="<?= $row['name'] ?>">
            <article>
                <a href="categories.php?id=<?= $row['id'] ?>"><h2><?= $row['name'] ?></h2></a>
                <p>
                    <a href="/editCategory.php?id=<?= $row['id'] ?>">Edit</a> &nbsp; &nbsp;
                    <a href="/editCategory.php?id=<?= $row['id'] ?>&delete=true" style="color:red" onclick="return confirm('Are you sure you want to delete ?')">Delete</a>
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
