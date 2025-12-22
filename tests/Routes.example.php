<?php

/**
 * Example Routes
 * 
 * Bu dosyayı app/Config/Routes.php dosyanıza ekleyin.
 */

// Export routes
$routes->get('export', 'ExportController::index');
$routes->get('export/excel', 'ExportController::excel');
$routes->get('export/excel-styled', 'ExportController::excelStyled');
$routes->get('export/word', 'ExportController::word');
$routes->get('export/word-advanced', 'ExportController::wordAdvanced');
$routes->get('export/pdf', 'ExportController::pdf');
$routes->get('export/pdf-custom', 'ExportController::pdfCustom');
$routes->get('export/html', 'ExportController::html');
$routes->get('export/csv', 'ExportController::csv');
$routes->get('export/helper', 'ExportController::helperExample');
$routes->get('export/save', 'ExportController::saveExample');

