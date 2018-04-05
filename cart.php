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
	echo "<tr><th>Cover</th><th>Title</th><th>Description</th><th>Author</th><th>Quantity</th></tr>";
	foreach ($_SESSION["cart"] as $book) {
		$id = $book["bookID"];
		$img = $book["bookImage"];
		$title = $book["bookName"];
		$desc = $book["bookDescription"];
		$qty = $book["quantity"];
		$author = getAuthor($book);
		$authorName = $author["authorName"];

		echo "<tr>";
		echo "<td><img src='$img'></td>";
		echo "<td><h3>$title</h3></td>";
		echo "<td>$desc</td>";
		echo "<td>$authorName</td>";
		echo "<td><form method='post'><input type='hidden' value='$id' name='changeQtyId'>" .
			"<input type='text' name='quantity' value='$qty'><submit value='Update'></form></td>";
		echo "<td><form method='post'><input type='hidden' value='$id' name='removeId'>" .
			"<submit value='Remove'></form></td>";
		echo "</tr>";
	}
	echo "</table>";
}

if (isset($_POST["removeId"]))
	foreach ($_SESSION["cart"] as $key => $book)
		if ($book["bookID"] == $_POST["removeId"])
			unset($_SESSION["cart"][$key]);

if (isset($_POST["changeQtyId"]))
	foreach ($_SESSION["cart"] as $key => $book)
		if ($book["bookID"] == $_POST["changeQtyId"])
			$_SESSION["cart"][$key]["quantity"] = $_POST["quantity"];

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
