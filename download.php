<?php
if (isset($_GET['id']) && isset($_GET['url'])) {
    $id = escapeshellarg($_GET['id']);
    $url = escapeshellarg($_GET['url']);

    // Command to download the video using yt-dlp
    $command = "yt-dlp -f $id --merge-output-format mp4 -o - $url";

    // Execute the command
    $output = shell_exec($command);

    // Check for errors or warnings
    if (strpos($output, '[error]') !== false || strpos($output, '[warning]') !== false) {
        echo "Error or warning occurred:<br>";
        echo nl2br($output); // Output any errors or warnings
        exit;
    }

    // Open a pipe to the yt-dlp process
    $handle = popen($command, 'rb');

    // Ensure the filename is retrieved correctly
    $filename_command = "yt-dlp --get-filename -o '%(title)s.%(ext)s' --no-warnings $url";
    $filename_execute = trim(shell_exec($filename_command)); // Trim to remove any extra whitespace

    // Ensure the filename has a proper extension
    if (pathinfo($filename_execute, PATHINFO_EXTENSION) != 'mp4') {
        $filename_execute .= '.mp4';
    }

    if ($handle && $filename_execute) {
        // Set headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename_execute . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        // Stream the content to the browser
        while (!feof($handle)) {
            echo fread($handle, 8192);
            flush(); // Ensure the content is sent to the browser immediately
        }

        // Close the handle
        pclose($handle);
        exit; // Stop script execution after download
    } else {
        echo "Failed to download the video.";
    }
} elseif (isset($_GET['url'])) {
    $url = escapeshellarg($_GET['url']);
    // Command to download the video using yt-dlp
    $command = "yt-dlp --merge-output-format mp4 -o - $url";

    // Execute the command
    $output = shell_exec($command);
    // Check for errors or warnings
    if (strpos($output, '[error]') !== false || strpos($output, '[warning]') !== false) {
        echo "Error or warning occurred:<br>";
        echo nl2br($output); // Output any errors or warnings
        exit;
    }
    // Open a pipe to the yt-dlp process
    $handle = popen($command, 'rb');

    // Ensure the filename is retrieved correctly
    $filename_command = "yt-dlp --get-filename -o '%(title)s.%(ext)s' --no-warnings $url";
    $filename_execute = trim(shell_exec($filename_command)); // Trim to remove any extra whitespace
    // Ensure the filename has a proper extension
    if (pathinfo($filename_execute, PATHINFO_EXTENSION) != 'mp4') {
        $filename_execute .= '.mp4';
    }

    // Check if the file was created
    if ($handle && $filename_execute) {
        // Set headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename_execute . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        // Stream the content to the browser
        while (!feof($handle)) {
            echo fread($handle, 8192);
            flush(); // Ensure the content is sent to the browser immediately
        }

        // Close the handle
        pclose($handle);
        exit; // Stop script execution after download
    } else {
        echo "Failed to download the video.";
    }
} else {
    echo "URL and Parameter is not set";
}
echo "<h2 style='width: 100%;text-align: center;'>Support <a style='text-decoration: none;' href='https://www.youtube.com/@devzonedeveloper'>Dev Zone Developer</a> by Ads and 1 Subscribe</h2>";
    echo '<script async="async" data-cfasync="false" src="//pl18450445.highrevenuenetwork.com/f363aa2191c3b38786f7d09c1d4cf4ab/invoke.js"></script>
<div id="container-f363aa2191c3b38786f7d09c1d4cf4ab"></div>';
?>



