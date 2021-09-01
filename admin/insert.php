<?php
    session_start();
    if(!isset($_SESSION['05e252a0-8c03-4b70-95fc-5f64a6a2df6c'])) {
        header("Location:http://flavorgallery.000webhostapp.com/admin/login.php");
    }
    $con=mysqli_connect("localhost","id17518869_amanabat1","<ceI8t_7q]Zfn5oT","id17518869_flavorgallery");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    include("../includes/header.php");

    $originalsFolder = "../img/originals/";
    $thumbsFolder = "../img/thumbs/";
    $displayFolder = "../img/display/";
    $unmarkedFolder = "../img/unmarked/";
    $watermark = "../img/watermark.png";

    if (isset($_POST['submit']))
    {
        $errors = 0;
        $id = uniqid();

        if (($_FILES["file"]["type"] == "image/jpeg")||($_FILES["file"]["type"] == "image/pjpeg")|| ($_FILES["file"]["type"] == "image/png"))
        {
            $filename = $id . "." . substr($_FILES["file"]["type"], 6);

            if($_FILES["file"]["size"] > 1000000)
            {
                echo "<style> .file-size { display: block; }</style>";
                $errors = 1;
            }
        }
        else
        {
            echo "<style> .file-type { display: block; }</style>";
            $errors = 1;
        }

        if ($_FILES["file"]["error"] > 0)
        {
            $error = $_FILES["file"]["error"];
            echo "<style> .gen-err { display: block; }</style>";
            $errors = 1;
        }

        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';

        $title = filter_var($title, FILTER_SANITIZE_STRING);
        if ($title == "") {
            echo "<style> .title-required { display: block; }</style>";
            $errors = 1;
        }
        if ($title != "" && strlen($title) < 3) {
            echo "<style> .title-min { display: block; }</style>";
            $errors = 1;
        }
        if ($title != "" && strlen($title) > 50) {
            echo "<style> .title-max { display: block; }</style>";
            $errors = 1;
        }

        $description = filter_var($description, FILTER_SANITIZE_STRING);
        if ($description == "") {
            echo "<style> .desc-required { display: block; }</style>";
            $errors = 1;
        }
        if ($description != "" && strlen($description) < 5) {
            echo "<style> .desc-min { display: block; }</style>";
            $errors = 1;
        }
        if ($description != "" && strlen($description) > 200) {
            echo "<style> .desc-max { display: block; }</style>";
            $errors = 1;
        }

        if ($errors == 0) {
            //make watermark on original size, make smaller img for thumb
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $originalsFolder . $filename))
            {
                //place uploaded image into originals folder
                $originalFile = $originalsFolder . $filename;

                //create display image
                createThumbnail($filename, $originalFile, $displayFolder, 800);
                //mergePix($unmarkedFolder . $filename, $watermark, $displayFolder . $filename, 0, 50);

                //create thumbnail
                createThumbnail($filename, $originalFile, $thumbsFolder, 150);

                mysqli_query($con, "INSERT INTO ama_gallery (id, ama_filename, ama_title, ama_description) VALUES ('$id', '$filename', '$title', '$description')") or die(mysqli_error($con));
                echo "<style> .success-msg { display: block; }</style>";
                $oldTitle = $title;
                $title = "";
                $description = "";
            }
            else
            {
                $errorMsg = $_FILES["file"]["error"];
                echo "<style> .upload-failed-msg { display: block; }</style>";
            }
        }
    } //end of submit

    function createThumbnail($filename, $file, $folder, $newwidth) {
        //get proper image ratio from uploaded file
        list($width, $height) = getimagesize($file);
        $imgRatio = $width/$height;
        $newheight = $newwidth / $imgRatio;

        //create holder for new image destination variable

        $thumb = imagecreatetruecolor($newwidth, $newheight);

        //create image files

        if ($_FILES["file"]["type"] == "image/png")
        {
            $source = imagecreatefrompng($file);
        }
        else 
        {
            $source = imagecreatefromjpeg($file);
        }
        
        //resize image to destination output
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        
        //output, get original filename for dest filename
        $newFileName = $folder . $filename;
    
        //display image back at 80% quality
        imagejpeg($thumb,$newFileName,80);

        //destroy created images from memory once placed in destination folder
        imagedestroy($thumb); 
        imagedestroy($source); 
    
        // if ($newwidth == 150) {
        //     echo "<img src=\"$newFileName\" />";
        // }
    }

?>

<h1 class="text-dark">Insert Image</h1>
<hr>
<div class="success-msg mt-3 p-2 rounded border text-white bg-success"><?php echo $oldTitle ?> image added</div>
<div class="error-msg upload-failed-msg mt-3 p-2 rounded border text-white bg-danger">Error occured of type: <?php echo $errorMsg ?></div>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="imgUpload" enctype="multipart/form-data" class="mt-3 mb-5">
    <div class="form-group">
        <label for="file">Image File</label>
        <input type="file" name="file" id="file">
        <div class="error-msg file-type mb-1 p-2 rounded border bg-danger text-white">File must be a valid file type (JPEG, PJPEG, PNG)</div>
        <div class="error-msg file-size mb-1 p-2 rounded border bg-danger text-white">File must be equal to or less than 1 MB</div>
        <div class="error-msg gen-err mb-1 p-2 rounded border bg-danger text-white">Error type <?php echo $error ?> has occured</div>
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <br>
        <input type="text" name="title" id="title" class="form-control" value="<?php echo isset($title) ? $title : ''; ?>">
        <div class="error-msg title-required mb-1 p-2 rounded border bg-danger text-white">Image title is required</div>
        <div class="error-msg title-min mb-1 p-2 rounded border bg-danger text-white">Image title must be a minimum of 3 characters</div>
        <div class="error-msg title-max mb-1 p-2 rounded border bg-danger text-white">Image title must be a maximum of 50 characters</div>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="2"><?php echo isset($description) ? $description : ''; ?></textarea>
        <div class="error-msg desc-required mb-1 p-2 rounded border bg-danger text-white">Image description is required</div>
        <div class="error-msg desc-min mb-1 p-2 rounded border bg-danger text-white">Image description must be a minimum of 5 characters</div>
        <div class="error-msg desc-max mb-1 p-2 rounded border bg-danger text-white">Image description must be a maximum of 200 characters</div>
    </div>
    <input type="submit" value="Submit" name="submit" class="btn btn-primary mt-3">
</form>

<?php
    include("../includes/footer.php");
?>