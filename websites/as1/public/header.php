<?php
require_once('dbconn.php');
session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $page_title ?? 'ibuy Auctions' ?></title>
		<link rel="stylesheet" href="ibuy.css" />
	</head>

	<body>
		<header>
			<h1><span class="i">i</span><span class="b">b</span><span class="u">u</span><span class="y">y</span></h1>

			<form action="#">
				<input type="text" name="search" placeholder="Search for anything" />
				<input type="submit" name="submit" value="Search" />
			</form>
		</header>

		<nav>
			<ul>
				<li><a class="categoryLink" href="#">Home &amp; Garden</a></li>
				<li><a class="categoryLink" href="#">Electronics</a></li>
				<li><a class="categoryLink" href="#">Fashion</a></li>
				<li><a class="categoryLink" href="#">Sport</a></li>
				<li><a class="categoryLink" href="#">Health</a></li>
				<li><a class="categoryLink" href="#">Toys</a></li>
				<li><a class="categoryLink" href="#">Motors</a></li>
				<?php 
				// we display different authentication links for logged and non logged users
				if(isset($_SESSION['user_id'])):
					?>
					<li><a class="categoryLink" href="/logout.php">Logout</a></li>
					<?php
					else: ?>
					<li><a class="categoryLink" href="/login.php">Login</a></li>
					<li><a class="categoryLink" href="/register.php">Register</a></li>
					<?php endif; ?>
			</ul>
		</nav>
		<img src="banners/1.jpg" alt="Banner" />
        <main>