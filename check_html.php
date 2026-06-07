<?php
$html = file_get_contents("http://localhost:8000/reports");
preg_match_all('/<label[^>]*for="([^"]+)"[^>]*>/i', $html, $matches);
foreach (array_unique($matches[1]) as $id) {
    if (!preg_match("/id=[\"']" . preg_quote($id) . "[\"']/i", $html)) {
        echo "Missing ID on /reports: $id\n";
    }
}
