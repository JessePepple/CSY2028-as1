<?php

$categories = get_categories($db);
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $page_title ?? 'ibuy Auctions' ?></title>
		<link rel="stylesheet" href="ibuy.css" />
		<style>
			.dropdown {position: relative;}
			.dropdown ul {
				list-style: none;
				position: absolute;
				left: 0;
				top: 80%;
				background: #fff;
				box-shadow: 5px 5px 5px rgba(0,0,0,.3);
				width: 100% !important;
				display: none;
			}
			.dropdown ul li a{
				font-size: 1em !important;
			}
			.dropdown:hover ul {
				display: block;
			}

			
		</style>
	</head>

	<body>
		<header>
			<h1><span class="i">i</span><span class="b">b</span><span class="u">u</span><span class="y">y</span></h1>

			<form action="search.php">
				<input type="text" name="search" placeholder="Search for anything" value="<?= form_value('search') ?>" />
				<input type="submit" name="submit" value="Search" />
			</form>
		</header>

		<nav>
			<ul>

				<?php

				// display categories
				if($categories):
					foreach($categories as $row):
						?>
						<li><a class="categoryLink" href="/categories.php?id=<?= $row['id'] ?>"> <?= $row['name'] ?> </a></li>
						<?php
					endforeach;
				endif;
				?>
					<li class="dropdown"><a class="categoryLink" href="javascript:void(0)">More</a>
					<ul class="drop">
						<?php if(is_admin()): ?>
							<li><a href="/adminCategories.php" class="leave">Manage Categories</a></li>
							<li><a href="/manageAdmins.php" class="leave">Manage Admins</a></li>
						<?php endif ?>
						<?php if(!is_logged()): ?>
						<li><a class="leave" href="/login.php">Login</a></li>
						<li><a class="leave" href="/register.php">Register</a></li>
						<?php else: ?>
							<li><a class="leave" href="/logout.php">Logout</a></li>
						<?php endif ?>
					</ul>
				</li>
			</ul>
		</nav>
		<img src="banners/1.jpg" alt="Banner" />
        <main>