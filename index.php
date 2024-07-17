<?php
if (isset($_POST['submit'])) {
    $URL = $_POST['url'];
    $prompt = "yt-dlp -F --skip-unavailable-fragments " . escapeshellarg($URL);
    $raw_data = shell_exec($prompt);

    $formats = ['144p', '240p', '360p', '480p', '720p', '1080p'];
    $filtered_data = [];

    foreach (explode("\n", $raw_data) as $line) {
        foreach ($formats as $format) {
            if (strpos($line, $format) !== false) {
                $filtered_data[$format][] = $line;
                break;
            }
        }
    }

    // Remove unavailable formats
    $available_formats = array_intersect_key($filtered_data, array_flip($formats));

    // Function to extract details using regular expressions
    function extract_details($line) {
        preg_match('/^(\d+)\s+mp4\s+(\d+x\d+)\s+\d+\s+\S+\s+\S+\s+(\S+)\s+\S+\s+(\S.*)$/', $line, $matches);
        return [
            'ID' => $matches[1] ?? 'N/A',
            'Resolution' => $matches[2] ?? 'N/A',
            'File Size' => $matches[3] ?? 'N/A',
            'More Info' => $matches[4] ?? 'N/A'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloader | Dev Zone Developer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
            text-decoration: none;
        }
        form {
            width: 40%;
            margin: 60px auto;
        }
        input {
            width: 100%;
            margin: 0 auto;
            padding: 10px 20px;
            border: 5px solid #FF0068;
            outline: none;
        }
        button, .button {
            margin: 10px auto;
            padding: 10px 20px;
            background-color: #FF0068;
            border: none;
            outline: none;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
        }
        .container {
            width: 70%;
            margin: 0 auto;
            border: 2px solid #FF0068;
        }
        .container h1 {
            text-align: center;
        }
        .sub-container {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 4px 20px;
            gap: 20px;
        }
        .sub-container .group {
            flex: 1 3 30%;
            border: 1px solid #000;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <input type="text" name="url" id="url" placeholder="Enter Video Link to Download"><br>
        <center><button type="submit" name="submit">Download</button></center>
    </form>
    <?php if (isset($available_formats)) : ?>
        <div class="container">
            <h1>Video Available Links</h1>
            <div class="sub-container">
                <div class="group" style="font-weight: 600;">ID's</div>
                <div class="group" style="font-weight: 600;">Resolution</div>
                <div class="group" style="font-weight: 600;">File Size</div>
                <div class="group" style="font-weight: 600;">More Info</div>
                <div class="group" style="font-weight: 600;">Download</div>
            </div>
            <?php
            function convert_size_to_mb($size) {
                if (strpos($size, 'k') !== false) {
                    // Remove 'k' and convert to float
                    $size_in_k = floatval($size);
                    // Convert kilobits to megabytes
                    $size_in_mb = $size_in_k * 0.125 * 0.001;
                    return round($size_in_mb, 2) . ' MB'; // Round to 2 decimal places for better readability
                }
                return $size; // Return original size if it's not in kilobits
            }

            foreach ($available_formats as $format => $lines) : ?>
                <?php if (!empty($lines)) : ?>
                    <?php foreach ($lines as $line) : 
                        $details = extract_details($line);
                        $details['File Size'] = convert_size_to_mb($details['File Size']);
                    ?>
                        <div class="sub-container">
                            <div class='group'><?= $details['ID'] ?></div>
                            <div class='group'><?= $details['Resolution'] ?></div>
                            <div class='group'><?= $details['File Size'] ?></div>
                            <div class='group'><?= $details['More Info'] ?></div>
                            <a href="download.php?id=<?= $details['ID'] ?>&url=<?php echo $URL; ?>" class='group button'>Download</a>
                        </div><br>
                    <?php endforeach; ?>
                    
                    <?php endif; ?>
                    
                
            <?php endforeach; ?>
            <div class="sub-container">
                        <div class='group'  style="border: none;" colspan="5"><a class="button" href="download.php?url=<?php echo $URL; ?>">Fast Download</a></div>
                    </div>
        </div>
    <?php endif; ?>
</body>

</html>
