

    <?php

    if($_SERVER["REQUEST_METHOD"] === "POST") {


        $fileName = $_FILES["textfile"]["name"];
        $temp = $_FILES["textfile"]["tmp_name"];
        move_uploaded_file($temp, $fileName);

        $mode = $_POST["mode"];

        function normalizeText($fileName, $mode) {
            $corrections = 0;
            $punctuationLines = [];

            $lines = file($fileName, FILE_IGNORE_NEW_LINES);

            $newLines = [];

            foreach ($lines as $index => $line) {

            
                $corrected = preg_replace("/[ \t]+/", " ", $line);
                if ($corrected !== $line) $corrections++;
                $line = $corrected;

        
                $trimmed = trim($line);
                if ($trimmed !== $line) $corrections++;
                $line = $trimmed;

            
                if ($line !== "" && preg_match("/^[[:punct:]]+$/", $line)) {
                    $punctuationLines[] = $index + 1;
                }

                $newLines[] = $line;
            }

            
            if ($mode === "compress") 
                $compressed = [];
                $blankSeen = false;

                foreach ($newLines as $line) {
                    if ($line === "") {
                        if (!$blankSeen) {
                            $compressed[] = "";
                            $blankSeen = true;
                        }
                    } else {
                        $compressed[] = $line;
                        $blankSeen = false;
                    }
                }

                $newLines = $compressed;
            }
            else if ($mode === "expand") 
                $expanded = [];
                foreach ($newLines as $line) {
                    $expanded[] = $line;
                    $expanded[] = "";
                }
                $newLines = $expanded;
            }

            file_put_contents($fileName, implode("\n", $newLines));

            return [
                "corrections" => $corrections,
                "punctuationLines" => $punctuationLines
            ];
        }
        $result = normalizeText($fileName, $mode);

        echo "<div class='result'>";
        echo "<b>File processed:</b> $fileName<br>";
        echo "<b>Total whitespace corrections:</b> " . $result["corrections"] . "<br>";

        if(!empty($result["punctuationLines"])) {
            echo "<b>Punctuation-only lines found at:</b> " . implode(", ", $result["punctuationLines"]);
        } else {
            echo "<b>No punctuation-only lines found.</b>";
        }

        echo "</div>";
    }

    ?>
</div>

</body>
</html>
