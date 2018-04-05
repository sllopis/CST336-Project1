<?php
session_start();
include "dbConnection.php";
$conn = getDatabaseConnection("ottermart");

function getAuthor($book) {
	global $conn;

	$sql = "SELECT *
			FROM author
			WHERE author.authorID = :id";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array(":id" => $book["authorID"]));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function displayCart() {
	echo "<table>";
	echo "<tr><th>Cover</th><th>Title</th><Description</th><th>Author</th></tr>";
	foreach ($_SESSION["cart"] as $book) {
		$img = $book["bookImage"];
		$title = $book["bookTitle"];
		$desc = $book["bookDescription"];
		$author = getAuthor($book);
		$authorName = $author["authorName"];

		echo "<tr>";
		echo "<td><img src='$img'></td>";
		echo "<td><h3>$title</h3></td>";
		echo "<td>$desc</td>";
		echo "<td>$author</td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title> Shopping Cart </title>
</head>
<body>
	<h1> Shopping Cart </h1>
	<?php displayCart(); ?>
</body>
</html>
