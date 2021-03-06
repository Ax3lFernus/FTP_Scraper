<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Un semplice scraper di FTP completamente online!">
    <meta name="author" content="Alessandro Annese, Davide De Salvo">
    <meta property="og:title" content="FTP Scraper">
    <meta property="og:site_name" content="FTP Scraper">
    <meta property="og:url" content="<?php $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']); echo $link;?>">
    <meta property="og:description" content="Un semplice scraper di FTP completamente online! Scarica i tuoi file in maniera facile e veloce!">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo $link . '/assets/images/og-image.jpg'; ?>">
    <meta property="og:locale" content="it">
    <link rel="shortcut icon" href="<?php echo $link . '/assets/images/logo.png'; ?>" />
    <title>FTP Scraper<?php if(isset($page_title)) echo " · " . $page_title;?></title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <?php if(isset($style)){ echo $style; } ?>
</head>