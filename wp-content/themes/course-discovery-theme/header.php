<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="site-header">

<div class="container header-flex">

<div class="logo">
<a href="<?php echo home_url(); ?>">
<h1>Course Discovery</h1>
</a>
</div>

<nav class="main-nav" aria-label="Main Navigation">
<ul>
<li><a href="<?php echo home_url(); ?>">Home</a></li>
<li><a href="<?php echo site_url('/courses'); ?>">Courses</a></li>
<li><a href="#">Providers</a></li>
<li><a href="#">Contact</a></li>
</ul>
</nav>

</div>

</header>

<main class="site-main">