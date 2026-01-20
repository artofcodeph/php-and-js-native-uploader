<?php
require_once 'db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// fetch all records available
if ($action === 'fetch') {
    $stmt = $pdo->query("Select * from images ORDER by created_at DESC");
    header('Content-Type: application/json');
    echo json_encode($stmt->fetchAll());
    exit();
}

// add new record
if ($action === 'add') {
    $title = $_POST['title'] ?? '';
    $file = $_FILES['image'] ?? null;

    if (!$file) {
        error_log("Upload Error: No file uploaded or 'image' key missing in \$_FILES");
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
        exit;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
        ];
        $msg = $errorMessages[$file['error']] ?? 'Unknown PHP upload error';
        error_log("PHP Upload Error Code: " . $file['error'] . " - " . $msg);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Upload Failed: ' . $msg]);
        exit;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '-' . uniqid() . '.' . $ext;
    $targetDir = 'uploads/';
    $target = $targetDir . $filename;

    if (!is_dir($targetDir)) {
        error_log("Upload Error: Target directory '$targetDir' does not exist.");
        if (!mkdir($targetDir, 0777, true)) {
            error_log("Upload Error: Failed to create target directory '$targetDir'.");
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Upload directory error']);
            exit;
        }
    }

    if (!is_writable($targetDir)) {
        error_log("Upload Error: Target directory '$targetDir' is not writable.");
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Upload directory not writable']);
        exit;
    }

    if (move_uploaded_file($file['tmp_name'], $target)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO images (title, filename, thumbnail) VALUES (?, ?, ?)");
            $stmt->execute([$title, $filename, $filename]);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
        }
    } else {
        error_log("Upload Error: move_uploaded_file failed for " . $file['tmp_name'] . " to " . $target);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
    }
    exit;
}

// delete record
if ($action === 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT filename FROM images WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();

    if ($img) {
        unlink('uploads/' . $img['filename']);
        $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
        $stmt->execute([$id]);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
    }
    exit;
}

// edit record
if ($action === 'edit') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $stmt = $pdo->prepare("UPDATE images SET title = ? WHERE id = ?");
    $stmt->execute([$title, $id]);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}