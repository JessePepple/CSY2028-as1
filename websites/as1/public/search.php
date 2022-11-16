<?php
require_once('head.php');
require_once('helpers.php');

/**
 * Get products matching this keyword in the product title and description
 * @param $search The keyword to search
 * @param $db The database connection
 */
function search_auction($search, $db)
{
    $sql = "SELECT a.title, a.id, a.image, a.categoryId, a.user_id, a.description, a.endDate, c.name from auction AS a INNER JOIN category AS c ON a.categoryId = c.id WHERE title LIKE ? OR description LIKE ?";
    $query = $db->prepare($sql);

    $query->execute(['%'.$search.'%', '%'.$search.'%']);

    $result = $query->fetchAll();

    return $result;
}

$keyword = $_GET['search'] ?? '';

$auctions = search_auction($keyword, $db);

include_once('header.php');

if(!$auctions):
?>

    <h2>Sorry we couldn't find anything</h2>

<?php 
else:
    ?>

<h1>Search Results</h1>
<ul class="productList">
<?php
    foreach($auctions as $row):
        ?>
        <li>
            <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>">
            <article>
                <h2><?= $row['title'] ?></h2>
                <h3><?= $row['name'] ?></h3>
                <p><?= $row['description'] ?></p>
                <p><?php if(isset($_SESSION['id']) && ($row['user_id'] == $_SESSION['id'])) 
                {
                    echo '<a href="/editAuction.php?id='. $row['id'] . '">Edit</a>';
                }
                ?></p>

                <p class="price">Current bid: £<?= get_highest_bid($row['id'], $db) ?></p>
                <a href="/auction.php?id=<?= $row['id'] ?>" class="more auctionLink">More &gt;&gt;</a>
            </article>
        </li>

        <?php
    endforeach;
endif;
?>


<?php
include_once('footer.php');
?>