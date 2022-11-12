<?php
require_once('head.php');
require_once('helpers.php');

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit;
}

$categories = get_categories($db);
$cat_id = $_GET['cat'] ?? null;

include_once('header.php');
?>
<style>
select, textarea {
    flex-grow: 1;
    width: 20vw;
    margin-bottom: 1em;
    margin-right: 2vw;
    margin-left: 2vw;
    padding: 0;
}
</style>
<h1>Create New Auction</h1>

<form action="" method="post" enctype="mutipart/form-data">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" placeholder="Auction title here" required="required" value="<?= $_POST['title'] ?? '' ?>" />
    
    <label for="description">Description </label>
    <textarea name="description" placeholder="Auction description here" required="required" id="description"><?= $_POST['description'] ?? '' ?></textarea>
    
    <label for="image">Image</label>
    <input type="file" name="image" id="image" />
    
    <label for="category">Category</label>
    <select name="category" id="category">
        <?php
        if($categories):
            foreach($categories as $row):
                ?>
                <option value="<?= $row['id'] ?>"
                <?= (isset($cat_id) && $row['id'] == $cat_id) ? 'selected' : '' ?>>
                <?= $row['name'] ?>
                </option>
                <?php
            endforeach;
        endif;
        ?>
    </select>
    
    
    <label for="end_date"> Auction end date</label>
    <input type="date" name="end_date" value="<?= $_POST['end_date'] ?? '' ?>" id="end_date" />

    <input type="submit" value="Submit" />

</form>

<?php include_once('footer.php'); ?>