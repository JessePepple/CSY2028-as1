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
    exit('You are missing the ID');
}
$auction_id = $_GET['id'];
$auction = get_auction($auction_id, $db);

if(!$auction)
{
    exit('We couldn\'t find this auction');
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
        $auction = get_auction($auction_id, $db);
        
        if(!isset($_SESSION['id']) || $auction['user_id'] != $_SESSION['id'])
        return false;
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

        if($file['size'] > 5000000 || (!empty($file['tmp_name']) && !getimagesize($file['tmp_name'])) )  
        { return false; }

        $name_arr = explode('.', $filename);

        $ext = end($name_arr);

        $file_new_name = $dir . uniqid() . '.' . $ext;

        if(!move_uploaded_file($file['tmp_name'], $file_new_name)) return false;

        return $file_new_name;
    }

    /**
     * Deletes an auction
     * @param $id Auction id
     * @param $db The db connection
     */
    public function delete($id, $db)
    {
        $auction = get_auction($id, $db);

        exit('We couldn\'t find this auction');
        
        if(!isset($_SESSION['id']) || $auction['user_id'] != $_SESSION['id'])
        return false;

        // delete image associated with this auction
        unlink($auction['image']);

        $sql = "DELETE FROM auction WHERE id = ?";
        $query = $db->prepare($sql);

        return $query->execute([$id]);
    }
}

$auction_class = new Auctions();

// delete this auction

if(isset($_GET['delete']) && $_GET['delete'] == 'true')
{
    $auction_class->delete($auction_id, $db);

    header('Location: categories.php?cat=1');

    exit;
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
<?php if(isset($_SESSION['id']) && ($auction['user_id'] == $_SESSION['id'])): ?>
<a href="editAuction.php?id=<?= $auction_id ?>&delete=true" onclick="return confirm('Are you sure you want to delete this auction?')"><button style="border:0;margin:10px;padding:5px">DELETE AUCTION</button></a>
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