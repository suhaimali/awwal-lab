<?php
$dir = "d:/lab/awwal-lab/resources/views";
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        $original = $content;

        $tokens = preg_split('/(<label[^>]*>.*?<\/label>|<(?:input|select|textarea)[^>]*>)/is', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        $lastLabelIndex = -1;
        
        for ($i = 0; $i < count($tokens); $i++) {
            $token = $tokens[$i];
            if (preg_match('/^<label([^>]*)>(.*?)<\/label>$/is', $token, $m)) {
                $attrs = $m[1];
                if (!preg_match('/\bfor=/i', $attrs)) {
                    $lastLabelIndex = $i;
                } else {
                    $lastLabelIndex = -1;
                }
            } elseif ($lastLabelIndex !== -1 && preg_match('/^<(input|select|textarea)([^>]*)>/is', $token, $m)) {
                $attrs = $m[2];
                if (preg_match('/\bid=[\'"]([^\'"]+)[\'"]/i', $attrs, $idMatch)) {
                    $id = $idMatch[1];
                    // Update the label
                    $labelToken = $tokens[$lastLabelIndex];
                    $labelToken = preg_replace('/^<label/i', '<label for="' . $id . '"', $labelToken);
                    $tokens[$lastLabelIndex] = $labelToken;
                    // Reset
                    $lastLabelIndex = -1;
                }
            }
        }
        
        $content = implode('', $tokens);

        if ($content !== $original) {
            file_put_contents($path, $content);
            echo "Updated labels in: $path\n";
        }
    }
}
echo "Done processing labels.\n";
