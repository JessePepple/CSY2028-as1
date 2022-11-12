<?php
require_once('head.php');
require_once('helpers.php');

$cat_id = $_GET['id'] ?? null;

if(!isset($cat_id))
{
    header('Location: index.php');
    exit;
}



$auctions = get_auctions($cat_id, $db);
$category = get_category($cat_id, $db);
$page_title = $category['name'] ?? 'Category Not Found';

include_once('header.php');

if(!$category):
?>

    <h2>Sorry we couldn't find the page you're looking for</h2>

<?php 
else:
    ?>
    <div>
        <a href="/addAuction.php?cat=<?= $cat_id ?>"><button>Add New Auction</button></a>
    </div>
    <h1>Category listing</h1>
    <ul class="productList">
    <?php
    if($auctions):
        foreach($auctions as $row):
            ?>
            <li>
                <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>">
                <article>
                    <h2><?= $row['title'] ?></h2>
                    <h3><?= $row['name'] ?></h3>
                    <p><?= $row['description'] ?></p>

                    <p class="price">Current bid: £123.45</p>
                    <a href="/auction.php?id=<?= $row['id'] ?>" class="more auctionLink">More &gt;&gt;</a>
                </article>
            </li>

            <?php
        endforeach;
    else:
        ?>
        <h2>No auctions in this category yet</h2>
        <?php
    endif;
endif;
?>


<?php
include_once('footer.php');
?>