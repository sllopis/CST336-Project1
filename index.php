
<?php

    include 'dbConnection.php';
    
    $conn = getDatabaseConnection("tp");

    function displaySearchResults(){
        global $conn;
        
        if (isset($_GET['searchButton'])) { //checks whether user has submitted the form
            
            echo "<h3>Products Found: </h3>"; 
            
            //following sql works but it DOES NOT prevent SQL Injection
            //$sql = "SELECT * FROM om_product WHERE 1
            //       AND productName LIKE '%".$_GET['product']."%'";
            
            //Query below prevents SQL Injection
            
            //$namedParameters = array();
            
            $sql = "SELECT bookId, bookName, bookDescription FROM book "; //dont need WHERE 1?
            
            
            if(isset($_GET['orderBy'])) {
                
                //SELECT * FROM book INNER JOIN category ON book.categoryID = category.catID ORDER BY category.catName
                //join the authorID to author table
                if($_GET['orderBy'] == "author") {
                    $sql .= " INNER JOIN author ON book.authorID = author.authorID ORDER BY author.authorName";
                }
                
                if($_GET['orderBy'] == "book") {
                    $sql .= " WHERE 1 ORDER BY bookName";
                }
                
                //join the categoryID to category table
                
                // SELECT * 
                // FROM book 
                // NATURAL JOIN category
                // ON book.categoryID = catID
                if($_GET['orderBy'] == "category") {
                    $sql .= " ORDER BY category";
                }
                
                
            }
            //echo $sql; //for debugging purposes
            
             $stmt = $conn->prepare($sql);
             $stmt->execute();
             $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        //when displayed, post book name: bookDescription
            foreach ($records as $record) { 
                //add category //add 
                // <a href = "\information.php?bookId=1> </a>"?
                 echo "<a href =\"information.php?bookId=" . $record["bookId"] . "\"> Info </a>";
                 echo  "<strong>" . $record["authorName"] . " " . $record["bookName"] . ":</strong>" . " " . $record["bookDescription"] . "<br /> <br>";
            
            }
        }
        
    }

    
?>


<!DOCTYPE html>
<html>
    <head>
        <title> CSUMB Library </title>
    </head>
    <body>
        
        <h1> CSUMB Library </h1>
        
        <form method="GET">
        
            <label for="bookName">Name: </label>
            <input type="text" name="bookName" id="bookName" placeholder="Search Book Title">
            
            <br>
                
            <label for="bookCat">Category: </label>
            <select id="bookCat" name="bookCategory">
              <option value="" >  Select One </option> 
              
               <!--We need to get the list from the DB to be displayed.-->
               <!--  Something like getCategory() function may be needed.-->
            </select>
            <br>
            
            
            <label for="bookAuthor">Author: </label> 
            <select id="bookAuthor" name="bookAuthor">
              <option value="" > Select One </option> 
             </select>
             <p>Sort by: </p>
             <input type="radio" name="orderBy" id = "author" value = "author"> <label>Author</label> <br>
             <input type="radio" name="orderBy" id = "book" value = "book"> <label> Book</label> <br>
             <!--<input type="radio" name="orderBy" id = "cat" value = "cat"> <label> Category</label> <br>-->

              
              <!--We need to get the list from the DB to be displayed.-->
              <!--Something like getAuthor() function may be needed.-->
            <!--</select>-->
            <br>
            
            <input type="submit" name="searchButton" value="Search"/> 
            <?= displaySearchResults(); ?>
            
            
        </form>

    </body>
</html>