<?php
require_once('head.php');

$page_title = 'Home';
include_once('helpers.php');
include_once('header.php');

$auctions = get_auctions_top($db);
?>

<h1>Latest Listings</h1>

<ul class="productList">

<?php foreach($auctions as $row): ?>
    <li>
        <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>">
        <article>
            <h2><?= $row['title'] ?></h2>
            <h3><?= $row['name'] ?></h3>
            <p><?= $row['description'] ?></p>
            <p><?php
            if(isset($_SESSION['id']) && ($row['user_id'] == $_SESSION['id'])) 
            {
                echo '<a href="/editAuction.php?id='. $row['id'] . '">Edit</a>';
            }
            ?></p>


            <p class="price">Current bid: £123.45</p>
            <a href="auction.php?id=<?= $row['id'] ?>" class="more auctionLink">More &gt;&gt;</a>
        </article>
    </li>
<?php endforeach; ?>

</ul>

<?php
include_once('footer.php');