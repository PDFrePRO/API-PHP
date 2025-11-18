<?php

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                PDFrePRO - PHP example script to produce your first dynamic PDF document!                               \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

include ('./my_credentials.inc');

// One of your prepared templates, into which you want to merge data to produce a PDF.
$templateId = '<your-template-ID>';

// The following structure "myData" represents the data object you want to merge into your PDF document. It's normally populated based on
// your users input or an SQL result.
//
// The data to print in this example is located in a JSON file, which we now read in directly from the filesystem:
$myData = json_decode(file_get_contents('./my_example_data_to_print.json'), true);

// The next variable "printLanguage" defines the language, which will be used for templates defined for multiple languages. Please, use only
// the languages defined in the corresponding API-KEY. If left empty, the first language of the template is used. Only language codes in
// ISO 638-1 are supported.
$printLanguage = 'de';

// First of all, include the PDFrePRO.class to make life easier. You can store our PHP class wherever you prefer; as long as you are able to
// access it.
require_once ('./PDFrePRO.class.php');

// The "magic" part of PDFrePRO: merge template and your data to get a ready-to-use PDF:
try {
    // Initialize a new instance of the PDFrePRO.class with your API key and its associated shared key.
    $pdfrepro = new PDFrePRO($apiKey, $sharedKey);

    // Produce the PDF by merging real data to the placeholder of your prepared template:
    $pdf      = $pdfrepro->getPDF($templateId, $myData, $printLanguage);

    // Finally, enable one of the two output methods below:

    // Version 1: Forward the PDF as output to your browser.
    /*
    header('Content-type: application/pdf;base64');
    header('Content-Disposition: inline;filename="my_first_print_result.pdf"');

    echo $pdf;
    // */

    // Version 2: Put the PDF directly onto your filesystem:
    /*
    file_put_contents('/tmp/my_first_print_result.pdf', base64_decode($pdf));
    // */
} catch (Throwable $throwable) {
    // Print any occurring throwable.
    echo '<pre>' . $throwable->getMessage() . '</pre>';
}
