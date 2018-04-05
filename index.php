<?php



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
              
              <!--We need to get the list from the DB to be displayed.-->
              <!--Something like getAuthor() function may be needed.-->
            </select>
            <br>
            
            <input type="submit" name="searchButton" value="Search/> 
            
            
        </form>

    </body>
</html>