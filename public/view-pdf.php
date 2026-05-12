<?php

$file = $_SERVER['DOCUMENT_ROOT'] . '/public/docs/api-documentation.pdf';

if (!file_exists($file)) {
    die('PDF not found');
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline');

readfile($file);
exit;