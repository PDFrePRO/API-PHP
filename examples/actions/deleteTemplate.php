<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro;

// Delete a certain template of your PDFrePRO account.
try {
    // Create the template, which shall be deleted.
    $templateUrl = $pdfrepro->createTemplate('Created Template');

    // Print it onto your console.
    /*
    echo $templateUrl . PHP_EOL;
    // */

    // Extract the template ID.
    $templateId = substr($templateUrl, strrpos($templateUrl, '/') + 1);

    // Print it onto your console.
    /*
    echo $templateId . PHP_EOL;
    // */

    // Delete the template.
    $pdfrepro->deleteTemplate($templateId);

    // Try to get the deleted template.
    try {
        $template = $pdfrepro->getTemplate($templateId);

        // Print it onto your console.
        /*
        echo json_encode($template, JSON_PRETTY_PRINT) . PHP_EOL;
        // */
    } catch (Throwable $throwable) {
        // Print it onto your console.
        /*
        echo 'Deleted' . PHP_EOL;
        // */
    }
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
