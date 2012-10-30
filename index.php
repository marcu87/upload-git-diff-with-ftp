<?

set_time_limit(0);

#################################
#################################
#################################
# FTP uploader
# by Marco
# 2012
# ^_^
#################################

// FTP Definitions:
$server = array ( "quality" => 
                        array( "host" => 'YOUR-FTP-HOSTING.com', 
                               "usr" => 'CHANGE-USERNAME', 
                               "pwd" => 'CHANGE-PASSWORD'),
                  "production" => 
                        array( "host" => 'YOUR-FTP-HOSTING.com', 
                               "usr" => 'CHANGE-USERNAME', 
                               "pwd" => 'CHANGE-PASSWORD' 
                 ) );

// Misc Definitions:
$localPath = "/opt/lampp/htdocs/myproject";
$remotePath = "/";

if (isset($_POST["action"]) && $_POST["action"] == "process" )
{
    // First i'll process the list:
    $listLines = explode("\n", $_POST["listOfFiles"] );

    if ( !empty($listLines) )
    {
        // Now i need to remove and sanatice all the lines:
        foreach($listLines as $line )
        {
            $line = trim($line);

            // Continue if the line is empty
            if (!$line ) continue;

            // line sanatized:
            $sanatization = "M       trunk/web/";
            $line = str_replace ( $sanatization , "" , $line );

            // now check if the file exists in local:
            if (file_exists( $localPath . $line ) ) $sanaticedLines[] = $line;
        }

    }

    // if there are files to upload:
    if ( isset($sanaticedLines) && !empty($sanaticedLines) )
    {
        // connect to FTP server (port 21)
        $conn_id = ftp_connect($server[$_POST["server"]]['host'], 21) or die ("Cannot connect to host");

        // send access parameters
        ftp_login($conn_id, $server[$_POST["server"]]['usr'], $server[$_POST["server"]]['pwd']) or die("Cannot login");

        // perform file upload
        foreach ($sanaticedLines as $fileToUpload )
        {
            $fileToUploadLocalPath = $localPath . $fileToUpload;
            $fileToUploadRemotePath = $remotePath . $fileToUpload;

            // upload the file
            $upload = ftp_put($conn_id, $fileToUploadRemotePath, $fileToUploadLocalPath, FTP_ASCII);
            if (!$upload ) $failedUploadFiles[] = $fileToUpload;

        }

        // close the FTP stream
        ftp_close($conn_id);

        // *****************
        //  done :D !
        if (empty($failedUploadFiles) )
        {
            $msg = 'EVERYTHING WAS UPLOADED SUCCESFULLY!!! :D';
        }
        else
        {
            if (!empty($failedUploadFiles) )
            {
                echo "FAILED UPLOAD FILES: <br><br>";
                echo "<pre>";
                print_r($failedUploadFiles);
                echo "</pre>";
                echo "<br><br>";
            }

            die;
        }
        // ****************

    }
    else
    {
        $msg = "No files to upload";
    }

}


?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es-ar" dir="ltr">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>FTP Uploader - by Marc0</title>

    <style>
        * { margin:0; padding:0; }
        body { padding:20px }
        #form .submit { padding:10px; }
        #form textarea { width:800px; height:450px; padding: 5px; }
        .successMsg { display: block; background: #05D005; padding: 5px 10px; color: white; font-weight:bold; }
    </style>

</head>
<body>

    <?
    if (isset($msg) )
    {
        ?>
        <p class="successMsg"><?= $msg ?></p>
        <br>
        <?
    }
    ?>

    <form id="form" action="index.php" method="POST">
        <textarea name="listOfFiles"></textarea>
        <br>

        <select name="server">
            <option <?= (isset($_POST["server"]) && $_POST["server"] == "quality") ? 'selected="selected"' : '' ?> value="quality">Quality</option>
            <option <?= (isset($_POST["server"]) && $_POST["server"] == "production") ? 'selected="selected"' : '' ?> value="production">Production</option>
        </select>

        <br>
        <br>

        <input class="submit" type="submit" value="Send">
        <input type="hidden" name="action" value="process">
    </form>

</body>
</html>