<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
  <title><?php echo (isset($titulo))? $titulo : 'Lavados Especiales' ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="msapplication-TileColor" content="#00a8ff" />
  <meta name="msapplication-config" content="<?php echo base_url()?>img/favicons/browserconfig.xml" />
  <meta name="theme-color" content="#ffffff" />
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="shortcut icon" href="<?php echo base_url()?>img/favicons/favicon.ico">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap-multiselect.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/fontawesome-all.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/fa-brands.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/fa-regular.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/dataTables.bootstrap4.min.css">
  <script src="<?php echo base_url()?>js/popper.min.js"></script>
  <script src="<?php echo base_url()?>js/jquery-3.3.1.min.js"></script>
  <script src="<?php echo base_url()?>js/bootstrap.min.js"></script>
  <script src="<?php echo base_url()?>js/bootstrap-multiselect.js"></script>
  <script src="<?php echo base_url()?>js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url()?>js/dataTables.bootstrap4.min.js"></script>
  <style media="screen">
  body {
    background: url("<?php echo base_url()?>img/fondo2.png") no-repeat fixed;
    height: 591px;
    background-size: cover;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto",
    "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans",
    "Helvetica Neue", sans-serif;
  }

  .jumbotron {
    background: rgba(255, 255, 255, .5);
  }

  nav {
    background-color: #0180ff4f !important;
  }

  form {
    background-color: rgba(255, 255, 255, .9);
  }
  </style>
</head>
<body>
