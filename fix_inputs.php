<?php
$dir = "e:/lab/awwal-lab.in/resources/views";
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$counter = 1000;

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        $original = $content;

        // 1. Add autocomplete="off" to input, select, textarea if missing
        $content = preg_replace_callback('/<(input|select|textarea)([^>]*)>/i', function($matches) use (&$counter) {
            $tag = $matches[1];
            $attrs = $matches[2];
            
            // Skip hidden inputs, buttons, submits, checkbox, radio for autocomplete
            if (preg_match('/type=[\'"]?(hidden|button|submit|checkbox|radio)[\'"]?/i', $attrs)) {
                return $matches[0];
            }

            if (!preg_match('/\bautocomplete=/i', $attrs)) {
                $attrs .= ' autocomplete="off"';
            }
            
            return "<$tag$attrs>";
        }, $content);

        // 2. Add id to inputs, selects, textareas if missing
        $content = preg_replace_callback('/<(input|select|textarea)([^>]*)>/i', function($matches) use (&$counter) {
            $tag = $matches[1];
            $attrs = $matches[2];

            if (!preg_match('/\bid=/i', $attrs)) {
                $id = "field_" . $counter++;
                $attrs .= ' id="' . $id . '"';
            }
            
            if (!preg_match('/\bname=/i', $attrs)) {
                // If it doesn't have a name, give it a generic one to satisfy the warning
                // Only if it's an input/select/textarea
                $name = "name_" . $counter++;
                $attrs .= ' name="' . $name . '"';
            }

            return "<$tag$attrs>";
        }, $content);

        if ($content !== $original) {
            file_put_contents($path, $content);
            echo "Updated: $path\n";
        }
    }
}
echo "Done processing inputs.\n";
