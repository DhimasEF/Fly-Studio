<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title; ?></title>

  <link rel="shortcut icon" href="<?=base_url('assets/resource/icon.png')?>" type="image/svg+xml">
  <link rel="stylesheet" href="<?= base_url('assets/css/boxicon.min.css'); ?>">
  <link rel="stylesheet" href="<?=base_url('assets/css/style.css'); ?>">
  <link rel="stylesheet" href="<?=base_url('assets/css/aos.css'); ?>">
</head>

<body>

  <header>
    <!-- Desktop Navbar -->
    <div class="navbar">
      <!-- Mobile header only appears on small screens -->
      <div class="mobile-header">
        <div class="logo">
          <img src="<?= base_url('assets/resource/logofly.png'); ?>" alt="Logo">
        </div>
        <div class="hamburger" id="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>

      <!-- Desktop Left Menu -->
      <div class="nav-left">
        <li class="<?= set_active('content'); ?>"><a href="<?= base_url('content'); ?>" class="navbar-link">Content</a></li>
		<li class="<?= set_active('event'); ?>"><a href="<?= base_url('event'); ?>" class="navbar-link">Event</a></li>
      </div>

      <!-- Desktop Center Logo -->
      <div class="nav-center">
        <a href="<?= base_url('dashboard'); ?>" class="logo">
          <img src="<?= base_url('assets/resource/logofly.png'); ?>" alt="Logo">
        </a>
      </div>

      <!-- Desktop Right Menu -->
      <div class="nav-right">
        <li class="<?= set_active('creatorteam'); ?>"><a href="<?= base_url('creatorteam'); ?>" class="navbar-link">Creator</a></li>
        <li class="<?= set_active('signin'); ?>"><a href="<?= base_url('signin'); ?>" class="navbar-link">Sign in</a></li>
      </div>
    </div>

    <!-- Mobile Slide Menu -->
    <div class="mobile-menu" id="mobileMenu">
      <div class="logo">
        <img src="<?= base_url('assets/resource/logofly.png'); ?>" alt="Logo" height="40">
      </div>
      <ul>
        <li><a href="<?= base_url('dashboard'); ?>" class="navbar-link">Dashboard</a></li>
        <li><a href="<?= base_url('content'); ?>" class="navbar-link">Content</a></li>
        <li><a href="<?= base_url('event'); ?>" class="navbar-link">Event</a></li>
        <li><a href="<?= base_url('creatorteam'); ?>" class="navbar-link">Creator</a></li>
        <li><a href="<?= base_url('signin'); ?>" class="navbar-link">Sign in</a></li>
      </ul>
    </div>
  </header>

  <script src="<?= base_url('assets/js/jquery-3.5.1.min.js'); ?>"></script>
  <script>
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      mobileMenu.classList.toggle('active');
    });

    // Optional: Hide mobile menu if window resized to desktop
    window.addEventListener('resize', () => {
      if (window.innerWidth > 768) {
        hamburger.classList.remove('active');
        mobileMenu.classList.remove('active');
      }
    });
  </script>
</body>
</html>
