<?php
require_once('head.php');

$page_title = 'Auction View';
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
    if(!isset($data['user_id']) || !isset($data['reviewer_id']) || empty($data['review_text'])) 
    return false;

    $sql = "INSERT INTO review(user_id, reviewer_id, review_text) VALUES(?, ?, ?)";
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

// when review form is submitted
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_form']) && isset($_SESSION['id']))
{
    $review_data = [
        'user_id' => $auction['user_id'],
        'reviewer_id' => $_SESSION['id'],
        'review_text' => $_POST['reviewtext']
    ];

    if(!create_review($review_data, $db))
    {
        $error_msg = 'The review text field is required';
    }
    else
    {
        $success_msg = 'Your review has been posted successfully';
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
        <?php
        $user_reviews = get_reviews($auction['user_id'], $db);
        if($user_reviews): ?>
        <ul>
            <?php
            foreach($user_reviews as $row):
                ?>
                <li><strong><a href="userReviews.php?id=<?= $row['reviewer_id'] ?>"><?= $row['name'] ?></a> said </strong> <?= $row['review_text'] ?> <em><?= $row['date_posted'] ?></em></li>

                <?php
            endforeach;
            ?>

        </ul>
        <?php endif ?>


        <?php if(isset($_SESSION['id'])): ?>
        <form method="post" action="">
            <label>Add your review</label> <textarea name="reviewtext"></textarea>
            <input type="hidden" name="review_form">
            <input type="submit" name="submit" value="Add Review" />
        </form>
        <?php endif ?>

    </section>
</article>

<?php include_once('footer.php'); ?>