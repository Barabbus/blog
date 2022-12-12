<?php

// phpinfo();

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';

if (isset($_GET['id'])) {
    $article = Article::getByID($conn, $_GET['id']);

    if (!$article) {
        die("Article not found.");
    }
} else {
    die("id not supplied. Article not found.");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Check for any errors generated when uploading image file
    try {
        if (empty($_FILES)) {
            throw new Exception('Invalid upload');
        }

        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file uploaded');
                break;
            case UPLOAD_ERR_INI_SIZE:
                throw new Exception('File is too large (from the server settings)');
            default:
                throw new Exception('An error occurred');
        }

        // Check file size is not too large
        if ($_FILES['file']['size'] > 1000000) {
            throw new Exception('File is too large');
        }

        $mime_types = ['image/gif', 'image/png', 'image/jpeg'];

        // Restrict the type of the uploaded image file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['file']['tmp_name']);

        if (!in_array($mime_type, $mime_types)) {
            throw new Exception('Invalid file type');
        }

        /* Sanitise the filename */

        // Returns an array which has split the filename up into its various parts
        $pathinfo = pathinfo($_FILES["file"]["name"]);

        // Get the name of the file from the array
        $base = $pathinfo['filename'];

        // Replace any characters that are not specified in the regexp with an underscore char 
        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);

        // Limit thee length of the filename to 200 characters
        $base = mb_substr($base, 0, 200);

        // Append the file extension to the sanitised filename
        $filename = $base . "." . $pathinfo['extension'];

        $destination = "../uploads/$filename";

        $i = 1;

        /* Check to ensure we don't have a file with the same name already stored
         in the uploads directory. If so, rename the file being moved. */
        while (file_exists($destination)) {
            $filename = $base . "-$i." . $pathinfo['extension'];
            $destination = "../uploads/$filename";

            $i++;
        }

        // Move uploaded image file from the temporary folder to the uploads folder
        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {

            $previous_image = $article->image_file;

            // Store filename of image in database
            if ($article->setImageFile($conn, $filename)) {

                if ($previous_image) {
                    unlink("../uploads/$previous_image");
                }
                Url::redirect("/admin/edit-article-image.php?id={$article->id}");
            };
        } else {
            throw new Exception('Unable to move uploaded file');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>

<?php require '../includes/header.php'; ?>

<h2>Edit article image</h2>

<?php if ($article->image_file) : ?>
    <img src="/uploads/<?= $article->image_file; ?>">
    <a class="btn btn-outline-primary" href="delete-article-image.php?id=<?= $article->id; ?>">Delete</a>
<?php endif; ?>

<?php if (isset($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div>
        <label for="file">Image file</label>
        <input type="file" name="file" id="file">
    </div>
    <button class="btn btn-outline-primary">Upload</button>
</form>

<?php require '../includes/footer.php'; ?>