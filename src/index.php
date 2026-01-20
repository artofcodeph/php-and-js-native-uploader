<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Keys</title>
</head>

<body>
    <div class="container">
        <header>
            <h1>Native PHP + JS Image Uploader, with Docker</h1>
            <button id="openModal" class="btn-primary">Upload Image</button>
        </header>

        <table id="imageTable">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Filename</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>

    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <h2>Upload Image</h2>
                <input type="text" name="title" placeholder="Image Title" required>
                <input type="file" name="image" accept="image/*" required>
                <div class="modal-buttons">
                    <button type="button" id="closeModal" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <form id="editForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editId">
                <h2>Edit Image Title</h2>
                <input type="text" name="title" id="editTitle" placeholder="Image Title" required>
                <div class="modal-buttons">
                    <button type="button" id="closeEditModal" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="modal lightbox">
        <span class="close-lightbox">&times;</span>
        <img class="lightbox-content" id="lightboxImage">
        <div id="lightboxCaption"></div>
    </div>

    <?php
    $file = 'uploads/test.txt';
    if (is_writable('uploads')) {
        file_put_contents($file, 'Docker permissions are working!');
        echo "<div style='padding: 7px; position: fixed; bottom: 0;color: green;'>Diagnostic: Uploads folder permission is working.</div>";
    } else {
        echo "<div style='padding: 7px; position: fixed; bottom: 0;color: red;'>Diagnostic: Uploads folder permission is not working.</div>";
    }
    ?>

    <script src="script.js"></script>

</body>

</html>