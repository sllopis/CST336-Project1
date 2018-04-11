
<?php
    
    function displayInfo() {
    include 'dbConnection.php';
    $conn = getDatabaseConnection("Library");

    $productId = $_GET['bookId'];

    $sql = "SELECT * FROM `book`
            INNER JOIN author  on book.authorID = author.authorID         
            INNER JOIN category on book.categoryID = category.catID
            WHERE bookId = :pId";    
    
    $np = array();
    $np[":pId"] = $productId;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($np);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    //title, description, author, category
        
        // echo $record['bookName'] . "<br>";
        
        echo "<img src='" . $record['bookImage'] . "' width = '200' /></p><br>";
        echo "<p>Title: " . $record["bookName"] . "</p><br />";
        echo "<p>Book Description: " . $record["bookDescription"] . "</p><br />";
        echo "<p>Author: " . $record["authorName"] . "</p><br />";       //how to get author name?
        echo "<p>Category: " . $record["catName"] . "</p><br />";
        echo "<p>Category Description: " . $record["catDescription"] . "</p><br />";
        
    
    // if(sizeof($records) == 0) {
    //     echo "No purchase information found, please direct back and choose another item!";
    // }
    }
 ?>

<!DOCTYPE html>
<html>
    <head>
        <title> </title>
        <link href ="css/styles.css" rel ="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    </head>
    <body>
        <h1><a href="index.php"> CSUMB Library </a></h1>
        <?php displayInfo(); ?>
    </body>
</html>
