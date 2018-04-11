
<?php

    include 'dbConnection.php';
    
    $conn = getDatabaseConnection("library");
    include 'addCart.php';
    
    function displayAuthor(){
        global $conn;
        
        $sql = "SELECT authorID, authorName FROM `author` ORDER BY authorName";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //print_r($records);
        
        foreach ($records as $record) {
            
            echo "<option " . ($_GET["bookAuthor"] == $record["authorID"] ? "selected" : "") .
            " value='".$record["authorID"]."' >" . $record["authorName"] . "</option>";
            
        }
        
    }
    
    function displayCategories(){
        global $conn;
        
        $sql = "SELECT catId, catName FROM `category` ORDER BY catName";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //print_r($records);
        
        foreach ($records as $record) {
            
            echo "<option " . ($_GET["bookCategory"] == $record["catId"] ? "selected" : "") .
             " value='".$record["catId"]."' >" . $record["catName"] . "</option>";
            
        }
        
    }

    function displaySearchResults(){
        global $conn;
        
        if (isset($_GET['searchButton'])) { //checks whether user has submitted the form
            
            echo "<h3>Products Found: </h3>"; 
            
            //following sql works but it DOES NOT prevent SQL Injection
            //$sql = "SELECT * FROM om_product WHERE 1
            //       AND productName LIKE '%".$_GET['product']."%'";
            
            //Query below prevents SQL Injection
            
            //$namedParameters = array();
            
            // $sql = "SELECT * FROM book "; //dont need WHERE 1?
            $sql = "SELECT * FROM book INNER JOIN author a ON book.authorID = a.authorID INNER JOIN category c ON book.categoryID = c.catID WHERE 1 ";
            $catId = "";
            $authorId = "";
            if(!empty($_GET["bookAuthor"])) {
                // $sql .= "INNER JOIN author a ON book.authorID = a.authorID ";
                $authorId = $_GET["bookAuthor"];
                $sql .= " AND book.authorID = $authorId ";
                
            }
            
            if(!empty($_GET["bookCategory"])) {
                // $sql .= "INNER JOIN category c ON book.categoryID = c.catID "; 
                $catId = $_GET["bookCategory"];
                $sql .= " AND categoryID = $catId";
                
            }
            
            if(!empty($_GET["bookName"])) {    //where 1 OR?
                $bookName = $_GET["bookName"];
                $sql .= " AND bookName LIKE '%$bookName%' ";
            }
                
                //join the categoryID to category table
                
                // SELECT * 
                // FROM book 
                // NATURAL JOIN category
                // ON book.categoryID = catID
                // if($_GET['orderBy'] == "category") {
                //     $sql .= " ORDER BY category ";
                // }
                
                $sql .= " ORDER BY bookName " ;
                
                if($_GET["orderBy"] == "desc") {
                            $sql .= " DESC";
                        }
                // echo "<h1> $sql </h1>"; //for debugging purposes
                
                $stmt = $conn->prepare($sql);
             $stmt->execute();
             $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
             //pull before add
        
        //when displayed, post book name: bookDescription
            foreach ($records as $record) { 
                //add category //add 
                // <a href = "\information.php?bookId=1> </a>"?
                 echo "<a href =\"information.php?bookId=" . $record["bookID"] . "\"> Info</a> ";
                 echo "<form method='post'><input type='hidden' name='bookId' value='" .
                    $record["bookID"] . "'><input type='submit' value='Add to cart' name='addBook'></form>";
                 //echo "<a href='addCart.php?bookId=" . $record["bookID"] . "'>Add to cart </a>";
                 echo  "<a style='color:black; background-color:white;'>". $record["authorName"] . " <strong><br>" . $record["bookName"] . ":</strong>" . "" . $record["bookDescription"] . "</a><br /> <br>";
            
            }
        }
            
            
            
             
        
        
        }
        
//     SELECT * FROM `book` 
// INNER JOIN category c ON book.categoryID = c.catID
// INNER JOIN author a ON book.authorID = a.authorID
// WHERE c.catID = 4 AND a.authorID = 7

    
?>


<!DOCTYPE html>
<html>
    <head>
        <title> CSUMB Library </title>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        
        <h1> CSUMB Library </h1>
        <a href="cart.php"> Cart </a>
        
        <form method="GET">
        
            <label for="bookName">Name: </label>
            <input type="text" name="bookName" id="bookName" placeholder="Search Book Title"
                value="<?= $_GET["bookName"] ?>">
            
            <br>
                
            <label for="bookCat">Category: </label>
            <select id="bookCat" name="bookCategory">
              <option value="" >  Select One </option> 
              <?php displayCategories(); ?> 
              
               <!--We need to get the list from the DB to be displayed.-->
               <!--  Something like getCategory() function may be needed.-->
            </select>
            <br>
            
            
            <label for="bookAuthor">Author: </label> 
            <select id="bookAuthor" name="bookAuthor">
              <option value="" > Select One </option> 
                  <?php displayAuthor(); ?> 
            </select>
             
             <p>Order by: </p>
             <input <?= $_GET["orderBy"] == "asc" ? "checked" : ""; ?> 
                type="radio" name="orderBy" id = "asc" value = "asc"> <label for="asc"> A-Z</label> <br>
             <input <?= $_GET["orderBy"] == "desc" ? "checked" : ""; ?> 
                 type="radio" name="orderBy" id = "desc" value = "desc"> <label for="desc"> Z-A</label> <br>
             <!--<input type="radio" name="orderBy" id = "cat" value = "cat"> <label> Category</label> <br>-->

              
              <!--We need to get the list from the DB to be displayed.-->
              <!--Something like getAuthor() function may be needed.-->
            <!--</select>-->
            <br>
            
            <input type="submit" name="searchButton" value="Search"/> 
        </form>
            <?= displaySearchResults(); ?>
            
            

    </body>
</html>
