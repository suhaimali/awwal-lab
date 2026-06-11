<?php
$dir = "d:/lab/awwal-lab/resources/views";
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        // Find all for="xxx"
        preg_match_all('/for=[\'"]([^\'"]+)[\'"]/i', $content, $forMatches);
        // Find all id="xxx"
        preg_match_all('/id=[\'"]([^\'"]+)[\'"]/i', $content, $idMatches);
        
        $ids = array_unique($idMatches[1]);
        
        foreach ($forMatches[1] as $forId) {
            // Ignore blade directives in IDs
            if (strpos($forId, '{{') !== false) continue;
            
            if (!in_array($forId, $ids)) {
                echo "Mismatched 'for' in $path: $forId\n";
            }
        }
    }
}
