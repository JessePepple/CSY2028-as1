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

/**
 * Creates a review
 * @param array $data
 * @param $db
 */
function create_review(array $data, $db)
{
    if(!isset($data['user_id']) || !isset($data['reviewer_id']) || !isset($data['review_text'])) 
    return false;

    $sql = "INSERT INTO reviews(user_id, reviewer_id, review_text) VALUES(?, ?, ?)";
    $query = $db->prepare($sql);
    
    return $query->execute([$data['user_id'], $data['reviewer_id'], $data['review_text']]);
}

/**
 * Places a bid
 * @param array $data
 * @param $db
 */
function place_bid(array $data, $db)
{
    if(!isset($data['user_id']) || empty($data['amount']) || !isset($data['auction_id']) || !is_numeric($data['amount']))
    return false;

    $sql = "INSERT INTO bids(user_id, auction_id, amount) VALUES(?, ?, ?)";
    $query = $db->prepare($sql);

    return $query->execute([$data['user_id'], $data['auction_id'], $data['amount']]);
}

// when bid form is submitted
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bid_form']) && isset($_SESSION['id']))
{
    $bid_data = [
        'auction_id' => $auction['id'],
        'user_id' => $_SESSION['id'],
        'amount' => $_POST['bid']
    ];

    if(!place_bid($bid_data, $db))
    {
        $error_msg = 'Please use a valid bid amount';
    }
    else
    {
        $success_msg = 'Your bid has been placed successfully';
    }

}
include_once('header.php');
?>

<h1>Product Page</h1>
<?php if(isset($success_msg)): ?>
    <div style="color:green; padding: 10px 20px"><?= $success_msg ?></div>
<?php endif ?>

<?php if(isset($error_msg)): ?>
    <div style="color:red; padding: 10px 20px"><?= $error_msg ?></div>
<?php endif ?>
<article class="product">

    <img src="product.png" alt="product name">
    <section class="details">
        <h2><?= $auction['title'] ?></h2>
        <h3><?= $auction['name'] ?></h3>
        <p>Auction created by <a href="#"><?= $author['name'] ?></a></p>
        <p class="price">Current bid: £<?= get_highest_bid($auction['id'], $db) ?></p>
        <time>Time left: <?= end_date($auction['endDate']) ?></time>

        <?php if(isset($_SESSION['id'])): ?>
        <form action="" class="bid" method="post">
            <input type="text" name="bid" placeholder="Enter bid amount" />
            <input type="hidden" name="bid_form" />
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