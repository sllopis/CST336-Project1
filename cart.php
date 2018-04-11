<?php
session_start();
include "dbConnection.php";
$conn = getDatabaseConnection("Library");

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
		echo "<td><h3><mark>$title</mark></h3></td>";
		echo "<td class='desc'>$desc</td>";
		echo "<td><mark>$authorName</mark></td>";
		echo "<form method='post' id='updateForm$id'><input type='hidden' value='$id' name='changeQtyId'>" .
			 "<td><input type='text' name='quantity' value='$qty'></td>" .
			 "<td><button type='submit' form='updateForm$id'>Update</button></td></form>";
		echo "<form method='post' id='removeForm$id'><input type='hidden' value='$id' name='removeId'>" .
			 "<td><button type='submit' form='removeForm$id'>Remove</button></td></form>";
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

if (isset($_POST["clearCart"])) {
    $_SESSION["cart"] = array();
}

?>

<!DOCTYPE html>
<html>
<head>
	<title> Shopping Cart </title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1><a href="index.php"> CSUMB Library </a></h1>
	<h2> Shopping Cart </h2>
    <?php
    if (count($_SESSION["cart"]) > 0) {
    ?>
    <form method="post">
        <input type="submit" name="clearCart" value="Clear cart">
    </form>
    <br><br>
	<?php displayCart(); ?>
    <?php } else { ?>
    <h3> Your shopping cart is empty </h3>
    <?php } ?>
</body>
</html>
