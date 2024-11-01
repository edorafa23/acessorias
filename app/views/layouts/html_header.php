<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <!-- fontawesome -->
    <link rel="stylesheet" href="assets/fontawesome/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="assets/app.css">

    <!-- jquery -->
    <script src="assets/jquery/jquery-3.6.0.min.js"></script>
    
    <!-- datatables -->
    <link rel="stylesheet" href="assets/datatables/datatables.min.css">
    <script src="assets/datatables/datatables.min.js"></script>

    <?php if(isset($flatpickr)):?>
        <!-- flatpickr -->
        <link rel="stylesheet" href="assets/flatpickr/flatpickr.min.css">
        <script src="assets/flatpickr/flatpickr.js"></script>
    <?php endif;?>
</head>
<body>