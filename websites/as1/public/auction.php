<?php
require_once('head.php');

$page_title = 'Home';
include_once('helpers.php');


if(!isset($_GET['id']) || empty($_GET['id']))
{
    exit('You are missing the ID');
}

$id = $_GET['id'];

$auction = get_auction_view($id, $db);

if(!$auction)
{
    exit('We couldn\'t find this auction');
}

$author = get_user($auction['user_id'], $db);

include_once('header.php');
?>

<h1>Product Page</h1>
<article class="product">

    <img src="product.png" alt="product name">
    <section class="details">
        <h2><?= $auction['title'] ?></h2>
        <h3><?= $auction['name'] ?></h3>
        <p>Auction created by <a href="#"><?= $author['name'] ?></a></p>
        <p class="price">Current bid: £123.45</p>
        <time>Time left: <?= end_date($auction['endDate']) ?></time>

        <?php if(isset($_SESSION['id'])): ?>
        <form action="" class="bid" method="post" name="bid_form">
            <input type="text" name="bid" placeholder="Enter bid amount" />
            <input type="submit" value="Place bid" />
        </form>
        <?php endif ?>

    </section>
    <section class="description">
    <p>
        <?= $auction['description'] ?>
    </p>


    </section>

    <section class="reviews">
        <h2>Reviews of <?= $author['name'] ?> </h2>
        <ul>
            <li><strong>Ali said </strong> great ibuyer! Product as advertised and delivery was quick <em>29/09/2019</em></li>
            <li><strong>Dave said </strong> disappointing, product was slightly damaged and arrived slowly.<em>22/07/2019</em></li>
            <li><strong>Susan said </strong> great value but the delivery was slow <em>22/07/2019</em></li>

        </ul>

        <?php if(isset($_SESSION['id'])): ?>
        <form method="post" action="" name="review_form">
            <label>Add your review</label> <textarea name="reviewtext"></textarea>

            <input type="submit" name="submit" value="Add Review" />
        </form>
        <?php endif ?>

    </section>
</article>

<?php include_once('footer.php'); ?>