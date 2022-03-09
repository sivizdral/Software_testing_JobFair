<?php

function uploadSlike($id, $folder, $un) {
    global $putanja;
    global $poruka;
    $target_dir = "img/" . $folder . "/";
    $imageFileType = strtolower(pathinfo(basename($_FILES[$id]["name"]), PATHINFO_EXTENSION));
    $target_file = $target_dir . $un . "." . $imageFileType;
    $uploadOk = 1;
    $putanja = $target_file;
// Check if image file is a actual image or fake image

    $check = getimagesize($_FILES[$id]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $poruka = $poruka . "<br>File is not an image.";
        $uploadOk = 0;
    }

// Check if file already exists
    if (file_exists($target_file)) {
        $poruka = $poruka . "<br>Sorry, file already exists.";
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES[$id]["size"] > 500000) {
        $poruka = $poruka . "<br>Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $poruka = $poruka . "<br>Sorry, only JPG, JPEG, PNG files are allowed.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $poruka = $poruka . "<br>Sorry, your file was not uploaded.";
        return false;
// if everything is ok, try to upload file
    } else {
        if (!move_uploaded_file($_FILES[$id]["tmp_name"], $target_file)) {
            $poruka = $poruka . "<br>Sorry, there was an error uploading your file.";
            return false;
        }
    }
    return true;
}

?>