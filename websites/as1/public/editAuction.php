<?php
require_once('head.php');
require_once('helpers.php');

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id']))
{
    header("Location: index.php");
    exit;
}
$auction_id = $_GET['id'];
$auction = get_auction($auction_id, $db);

if(!$auction)
{
    header("Location: index.php");
    exit;
}

class Auctions {
    /**
     * Edits an auction
     * @param array $data The data to save in the database
     * @param int $auction_id The auction id
     * @param $db The database connection
     * @return bool
     */
    public function edit(array $data, int $auction_id, $db)
    {
        $sql = "UPDATE auction SET title = ?, description = ?, image = ?, categoryId = ?, endDate = ? WHERE id = ?;";
        $query = $db->prepare($sql);

        $query = $query->execute([$data['title'], $data['description'], $data['image'], $data['category'], $data['end_date'], $auction_id]);

        return $query;
    }

    /**
     * Uploads an image for an auction
     * @param $file the file to upload
     */
    function upload_image($file)
    {
        $dir = 'images/auctions/';
        $filename = $dir . basename($file['name']);

        if(!getimagesize($file['tmp_name']) || $file['size'] > 5000000) 
        { return false; }

        $name_arr = explode('.', $filename);

        $ext = end($name_arr);

        $file_new_name = $dir . uniqid() . '.' . $ext;

        if(!move_uploaded_file($file['tmp_name'], $file_new_name)) return false;

        return $file_new_name;
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $errors = [];

    $auction_data = [
        'title' => $_POST['title'] ?? null,
        'description' => $_POST['description'] ?? null,
        'image' => $_FILES['image']['size'] != 0 ? $_FILES['image'] : $auction['image'],
        'category' => $_POST['category'] ?? null,
        'end_date' => $_POST['end_date'] ?? null,
    ];


    foreach($auction_data as $key => $value)
    {
        if(empty($value))
        {
            $errors[] = "The $key field is required";
        }
    }

    if(empty($errors))
    {
        $auction_class = new Auctions();

        if($_FILES['image']['size'] != 0)
        {
        // upload image
        $image = $auction_class->upload_image($_FILES['image']);

        if(!$image) $errors[] = 'Please upload a valid image that\'s less than 5MB';

        $auction_data['image'] = $image ?? $auction_data['image'];

        }

        
    
        if(!$auction_class->edit($auction_data, $auction_id, $db) || !empty($errors))
        {
            $errors[] = 'An internal error occured';
            $form_errors = $errors;
        }
        else
        {
            header("Location: categories.php?id=" . $auction_data['category'] . "");
        }
    }
    else
    {
        $form_errors = $errors;
    }

}
$categories = get_categories($db);
$cat_id = $auction['categoryId'];

include_once('header.php');
?>

<style>
select, textarea, p.cap {
    flex-grow: 1;
    width: 20vw;
    margin-bottom: 1em;
    margin-right: 2vw;
    margin-left: 2vw;
    padding: 0;
}
</style>
<h1>Edit Auction</h1>

<?php if(isset($form_errors)): ?>
    <div style="color:red; font-weight: bold; padding: 15px;">
    <?php 
        foreach($form_errors as $err):

            echo $err . '<br/>';

        endforeach;
    ?>
    </div>
<?php endif ?>

<form action="" method="post" enctype="multipart/form-data">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" placeholder="Auction title here" required="required" value="<?= form_value('title', $auction['title']) ?>" />
    
    <label for="description">Description </label>
    <textarea name="description" placeholder="Auction description here" required="required" id="description"><?= form_value('description', $auction['description']) ?></textarea>
    
    <label for="image">Image</label>
    <input type="file" name="image" id="image" />
    <p class="cap"></p>
    <p class="cap"><?= $auction['image'] ?></p>
    

    <label for="category">Category</label>
    <select name="category" id="category">
        <?php
        if($categories):
            foreach($categories as $row):
                ?>
                <option value="<?= $row['id'] ?>"
                <?= (form_value('category', $cat_id) == $row['id']) ? 'selected' : '' ?>>
                <?= $row['name'] ?>
                </option>
                <?php
            endforeach;
        endif;
        ?>
    </select>
    
    
    <label for="end_date"> Auction end date</label>
    <input type="date" name="end_date" value="<?= form_value('end_date', $auction['endDate']) ?>" id="end_date" />

    <input type="submit" value="Submit" />

</form>

<?php include_once('footer.php'); ?>