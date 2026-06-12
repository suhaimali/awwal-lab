<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/resources/views'));
foreach ($files as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        $original = $content;
        
        $content = str_replace('â‚¹', '₹', $content);
        $content = str_replace('â€”', '—', $content);
        $content = str_replace('âœ ï¸ ', '✏️', $content);
        $content = str_replace('â€¢', '•', $content);
        $content = str_replace('â• ', '═', $content);
        $content = str_replace('â”€', '─', $content);
        $content = str_replace('â†’', '→', $content);
        
        if ($content !== $original) {
            file_put_contents($path, $content);
            echo "Fixed: " . $file->getFilename() . "\n";
        }
    }
}
echo "Done.\n";
