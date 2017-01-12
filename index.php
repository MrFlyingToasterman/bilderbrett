<!DOCTYPE html>

<!-- 
	Please notice that you need a database.
	And remember to fill in the correct login data, tablenames and attributes in the programmcode!
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Bilderbrett</title>
        <style>
            body {
                background-color: dimgrey;
            }
            h1 {
                font-style: italic;
                font-family: fantasy;
                color: white;
                text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;
            }
            h6 {
                font-style: italic;
                font-family: fantasy;
                color: white;
                text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;
            }
            img {
                padding: 5px;
                box-shadow: 10px 6px 50px 0px rgba(0,0,0,0.75);
                background-size: cover;
            }
            #sender {
                padding-top: 2%;
                box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
                background-color: darkgray;
                width: 600px;
                padding-bottom: 25px;
                margin-bottom: 25px;
            }
            a:visited {
                color: transparent;
            }
            a:link {
                color: transparent;
            }
            
            .thumb img {
                min-width: 300px;
                min-height: 300px;
                width: 300px;
            }
        </style>
    </head>
    <body>
    <center>
        <h1>Bilderbrett Online</h1>
        <form method="POST" action="index.php" enctype="multipart/form-data">
                <br>
                <div id="sender">
                    
                    <table>
                        <tr>
                            <td width="40%">File</td><td><input type="file" name="pic" size="14"></td>
                        </tr>
                        <tr>
                            <td>User</td><td><input type="text" name="usr" size="14"></td>
                        </tr>
                        <tr>
                            <td>Password</td><td><input type="password" name="pw" size="14"></td>
                        </tr>
                        <tr>
                            <td></td><td><input type="submit" size="14"></td>
                        </tr>
                    </table>
                    <br>
                    <h5> Please do not upload NSFW content! </h5>
                </div>
        <div class="thumb">
            <?php
            
            function make_thumb($src, $dest, $desired_width, $type) {

                            /* read the source image */
                switch($type) {
                    case "jpg" :
                        $source_image = imagecreatefromjpeg($src);
                        break;
                    case "png" :
                        $source_image = imagecreatefrompng($src);
                        break;
                    case "gif" :
                        $source_image = imagecreatefromgif($src);
                        break;
                    default :
                        echo "File Error";
                        die();
                        break;   
                }
                            
                            $width = imagesx($source_image);
                            $height = imagesy($source_image);

                            /* find the "desired height" of this thumbnail, relative to the desired width  */
                            $desired_height = floor($height * ($desired_width / $width));

                            /* create a new, "virtual" image */
                            $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

                            /* copy source image at a resized size */
                            imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

                            /* create the physical thumbnail image to its destination */
                            switch($type) {
                                case "jpg" :
                                    imagejpeg($virtual_image, $dest);
                                    break;
                                case "png" :
                                    imagepng($virtual_image, $dest);
                                    break;
                                case "gif" :
                                    imagegif($virtual_image, $dest);
                                    break;
                                default :
                                    echo "Output File Error";
                                    die();
                                    break;   
                            }
                            
                    }
            
            if (isset($_POST["usr"])) {
                if ($_POST["usr"] == "adminlogin") {
                    echo "<a href=\"administration.php\"><h6>Click me ;)</h6></a>";
                    die();
                }
            }
            
            if(isset($_FILES["pic"])) {
                
               
                
                //Check login
                $pwasitshould = "Wrong password or user!";
                include "inc_mysql.php";
                $query = "SElECT * FROM users";
                $sql = mysql_query($query);
                while($ds = mysql_fetch_array($sql)) {

                    if ($ds["Name"] == $_POST["usr"]) {
                        $pwasitshould = $ds["Password"];
                    }
                }
                
                if ($pwasitshould == "Wrong password or user!") {
                    echo $pwasitshould;
                    die();
                }

                mysql_close($dz);

                
                if ($pwasitshould == $_POST["pw"]) {
            
                //Check if pic exists
                if (!empty($_FILES["pic"]["tmp_name"])){
                    
                    $imagesize = getimagesize($_FILES["pic"]["tmp_name"]);
                    $imagetype = $imagesize[2];
                    switch ($imagetype) {
                    // 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
                        case 1: // GIF
                            $image = imagecreatefromgif($_FILES["pic"]["tmp_name"]);
                            $unix_stamp_name = time() . "-" . $_POST["usr"] . ".gif";
                            $file_type = "gif";
                            break;
                        case 2: // JPEG
                            $image = imagecreatefromjpeg($_FILES["pic"]["tmp_name"]);
                            $unix_stamp_name = time() . "-" . $_POST["usr"] . ".jpeg";
                            $file_type = "jpg";
                            break;
                        case 3: // PNG
                            $image = imagecreatefrompng($_FILES["pic"]["tmp_name"]);
                            $unix_stamp_name = time() . "-" . $_POST["usr"] . ".png";
                            $file_type = "png";
                            break;
                        default:
                            die('Unsupported imageformat');
                    }
                    
                    copy($_FILES["pic"]["tmp_name"], "pics/" . $unix_stamp_name);
                    make_thumb("pics/" . $unix_stamp_name, "thumbnails/pics/" . $unix_stamp_name, 240, $file_type);
                    
                }
                    
                }
                
            }
            
            //foreach (array_reverse(glob("pics/*.*")) as $pic_pic) {
            
            session_start();

            $vardrop = null;

            if (!isset($_SESSION['vardrop'])) {
                $vardrop = 19;
                $_SESSION['vardrop'] = $vardrop;
            } else {
                $vardrop = $_SESSION['vardrop'];
            }

            
            if (isset($_GET["mehr"])) {
                if ($_GET["mehr"] == true) {
                    $vardrop = $vardrop +20;
                    $_SESSION['vardrop'] = $vardrop;
                }
            }

            $pic_puc = array_reverse(glob("pics/*.*"));
            for ($i = 0; $i < sizeof($pic_puc); $i++) {
                $pic_pic = $pic_puc[$i];
                echo "<a href=\"" . $pic_pic . "\"> <img src=\"thumbnails/" . $pic_pic . "\" width=\"300px\" height=\"300px\"></a>";
               
                if ($i == $vardrop) {
                    break;
                }
                
            }
                    
?>
            <br>
            <br>
            <a href="index.php?mehr=true" style="color: white; text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;"><p>Load more</p></a>
        </form>
        </div>
        <br>
        <br>
        <br>
    </center>
    <h6 align="right">Version 1.2 Beta</h6>
    </body>
</html>
