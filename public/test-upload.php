<?php
// Display PHP upload settings
echo "<h3>PHP Upload Settings:</h3>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "temp directory: " . sys_get_temp_dir() . "<br>";
echo "temp writable: " . (is_writable(sys_get_temp_dir()) ? 'Yes' : 'No') . "<br><br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    $file = $_FILES['test_image'];
    
    echo "<h3>Upload Debug Info:</h3>";
    echo "File name: " . $file['name'] . "<br>";
    echo "File type: " . $file['type'] . "<br>";
    echo "File size: " . $file['size'] . " bytes<br>";
    echo "Temp path: " . $file['tmp_name'] . "<br>";
    echo "Error code: " . $file['error'] . "<br>";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../storage/app/public/test/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            echo "Created directory: " . $uploadDir . "<br>";
        }
        
        $filename = time() . '_' . $file['name'];
        $targetFile = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            echo "<span style='color:green'>✓ Upload successful!</span><br>";
            echo "File saved to: " . $targetFile . "<br>";
            echo "<a href='/storage/test/" . $filename . "'>View uploaded file</a><br>";
        } else {
            echo "<span style='color:red'>✗ Failed to move uploaded file</span><br>";
        }
    } else {
        $errors = [
            1 => 'File too large (upload_max_filesize)',
            2 => 'File too large (MAX_FILE_SIZE)',
            3 => 'File only partially uploaded',
            4 => 'No file uploaded',
            6 => 'Missing temporary folder',
            7 => 'Failed to write file to disk',
            8 => 'PHP extension stopped file upload'
        ];
        echo "<span style='color:red'>Error: " . ($errors[$file['error']] ?? 'Unknown error') . "</span><br>";
    }
}
?>

<h3>Test File Upload</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test_image" accept="image/*" required>
    <button type="submit">Upload</button>
</form>

<?php
// List uploaded files
$testDir = __DIR__ . '/../storage/app/public/test/';
if (is_dir($testDir) && $handle = opendir($testDir)) {
    echo "<h3>Previously Uploaded Files:</h3>";
    echo "<ul>";
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            echo "<li><a href='/storage/test/$entry'>$entry</a></li>";
        }
    }
    closedir($handle);
    echo "</ul>";
}
?>