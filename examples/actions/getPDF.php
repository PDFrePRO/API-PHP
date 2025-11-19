<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $data, $pdfrepro, $printLanguage, $templateId;

// Get the PDF.
try {
    $pdf = $pdfrepro->getPDF($templateId, $data, $printLanguage);

    // Do something with the produced PDF.

    // Version 1: Forward the PDF as output to your browser.
    /*
    header('Content-type: application/pdf;base64');
    header('Content-Disposition: inline;filename="examples_actions_getPDF.pdf"');

    echo $pdf;
    // */

    // Version 2: Put the PDF directly onto your filesystem:
    /*
    file_put_contents('/tmp/examples_actions_getPDF.pdf', base64_decode($pdf));
    // */
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
