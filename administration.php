<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Administration</title>
    </head>
    <body>
        <?php
        
        if (isset($_POST["adminpw"])) {
            if ($_POST["adminpw"] == "MASTERPASSWORD") {
            echo "<b style=\"color: green;\">Erledigt!</b>";
       
        $pdo = new PDO('mysql:host=localhost;dbname=DATABASE', 'USER', 'PASSWORT');
        
        if(isset($_POST["useradd"]) && $_POST["useradd"] != "") {
            
            $username = $_POST["useradd"];
            $password = $_POST["pwadd"];
            
            
            //SAVE TO MYSql
            $statement = $pdo->prepare("INSERT INTO user (Name, Password, Since) VALUES (?, ?, ?)");
            $statement->execute(array($username, $password, time()));   
        }
        
        if(isset($_POST["mewannapw"]) && $_POST["mewannapw"] != "") {
            
            $userwho = $_POST["mewannapw"];
            $userare = "nobody found";
            
            //read from mysql
            include "inc_mysql.php"; //connect to server
            
            $query = "SElECT * FROM user"; //my query
            $sql = mysql_query($query);
            while($ds = mysql_fetch_array($sql)) {
           
                if ($ds["Name"] == $userwho) {
                    $userare = $ds["Password"];
                }
                /*
                $name = $ds["Name"];
                echo $name; */
            }
            
            mysql_close($dz); //Close connection to server
        }
        
        if(isset($_POST["userdel"]) && $_POST["userdel"] != "") {
            
            $delwho = $_POST["userdel"];
            
            //read from mahsql
            include "inc_mysql.php"; //connect to server
            
            $query = "DELETE FROM benutzer WHERE Name='" . $delwho . "'"; //My querry
            $sql = mysql_query($query);
            
            mysql_close($dz); //close connection to server
            
            //delete all posts from this fool
            foreach (array_reverse(glob("pics/*.*")) as $pic_poc) {  
                if (strpos($pic_poc, "-" . $delwho . '.') !== false) {
                    unlink($pic_poc);
                    unlink("thumbnails/" . $pic_poc);
                }
            } 
        }
        
        if(isset($_POST["rmpost"]) && $_POST["rmpost"] != "") {
            
            $rmwhat = $_POST["rmpost"];
            
            foreach (array_reverse(glob("pics/*.*")) as $pic_poc) {  
                if (strpos($pic_poc, $rmwhat)) {
                    unlink($pic_poc);
                    unlink("thumbnails/" . $pic_poc);
                }
            } 
        }
        
        }else {
            echo "<b style=\"color: red;\">Admin power Error!</b>";
        }
            
        } 
        ?>
        
        <form action="administration.php" method="POST">
             
            <table>
                <tr>
                    <td>Add user</td> <td><input type="text" name="useradd" placeholder="User"></td> <td><input type="text" name="pwadd" placeholder="Password"></td>
                </tr>
                <tr>
                    <td>Show password</td> <td><input type="text" name="mewannapw" placeholder="User"></td> <td><input type="text" placeholder="Output of Password" value="<?php if(isset($userwho)) { echo $userare; }; ?>"></td>
                </tr>
                <tr>
                    <td>Ban user</td> <td colspan="3"><input type="text" name="userdel" placeholder="User" size="45"></td>
                </tr>
                <tr>
                    <td>Delete post</td> <td colspan="3"><input type="text" name="rmpost" placeholder="Post" size="45"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Admin Password</td> <td colspan="3"><input type="password" name="adminpw" size="45" placeholder="Admin Password"></td>
                </tr>
                <tr>
                    <td></td> <td></td> <td align="right"><input type="submit"></td>
                </tr>
            </table>
            
        </form>
        <?php 
            
            echo "<b>User Acc's:</b> <br>";
        
            include "inc_mysql.php";
            
            $query = "SElECT * FROM benutzer"; 
            $sql = mysql_query($query); 
            while($ds = mysql_fetch_array($sql)) {
           
                echo $ds["Name"] . "<br>";
            }
            
            mysql_close($dz);
        ?>
        
    </body>
</html>
