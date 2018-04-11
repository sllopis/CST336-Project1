<?php
session_start();

function getBook($id) {
	global $conn;
	$sql = "SELECT * FROM book
		WHERE bookID = :id";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array(":id" => $id));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addBook() {
    $book = getBook($_POST["bookId"]);
    if (!isset($_SESSION["cart"]))
        $_SESSION["cart"] = array();
	foreach ($_SESSION["cart"] as $key => $b) {
		if ($b["bookID"] == $book["bookID"]) {
			$_SESSION["cart"][$key]["quantity"]++;
			return;
		}
	}
	$book["quantity"] = 1;
	$_SESSION["cart"][] = $book;
}

if (isset($_POST["addBook"]))
    addBook();
//addBook($book);
//header("Location: index.php");
?>
