<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- STYLES CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/adminstyle.css'); ?>">
		<link rel="shortcut icon" href="<?=base_url('assets/resource/icon.png')?>" type="image/svg+xml">

        <!-- BOX ICONS CSS-->
        <link href="<?= base_url('assets/css/boxicon.min.css'); ?>" rel='stylesheet'>
		<script src="<?= base_url('assets/js/jquery-3.5.1.min.js'); ?>"></script>	 
        <title><?= isset($title) ? $title : 'Fly Studio'; ?></title>
    </head>
    <body id="body">
        <div class="l-navbar" id="navbar">
            <nav class="nav">
                <div>
                    <a href="#" class="nav__logo">
                        <img src="<?=base_url('assets/resource/icon.png')?>"  alt="" class="nav__logo-icon">
                        <span class="nav__logo-text">Fly Studio</span>
                    </a>
    
                    <div class="nav__toggle" id="nav-toggle">
                        <i class='bx bx-chevron-right'></i>
                    </div>
    
                    <ul class="nav__list">
						<a href="<?= base_url('Admin/profil'); ?>" class="nav__link" id="profile-link" data-href="profil">
							<div class="profile-bubble">
								<img src="<?= base_url('assets/uploads/Profil/' . ($user->profile_picture ?? 'default.jpg')); ?>" alt="Profil" class="profile-img">
							</div>
							<span class="nav__text ml-2"><?= htmlspecialchars($user->name ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?></span>
						</a>
						<a href="<?= base_url('Admin/event'); ?>" class="nav__link" data-href="event">
							<i class='bx bx-bell nav__icon'></i>
							<span class="nav__text">Event</span>
						</a>
						<a href="<?= base_url('Admin/creator'); ?>" class="nav__link" data-href="creator">
							<i class='bx bx-palette nav__icon'></i>
							<span class="nav__text">Creator</span>
						</a> 
						<a href="<?= base_url('Admin/user'); ?>" class="nav__link" data-href="user">
							<i class='bx bx-user nav__icon'></i>
							<span class="nav__text">User</span>
						</a> 
						<a href="<?= base_url('Admin/chat'); ?>" class="nav__link" data-href="chat">
							<i class='bx bx-chat nav__icon'></i>
							<span class="nav__text">Chat</span>
						</a> 
						<a href="<?= base_url('Admin/content'); ?>" class="nav__link" data-href="content">
							<i class='bx bx-grid-alt nav__icon'></i>
							<span class="nav__text">Content</span>
						</a>  
						<a href="<?= base_url('Admin/transaction'); ?>" class="nav__link" data-href="transaction">
							<i class='bx bx-transfer nav__icon'></i>
							<span class="nav__text">Transaction</span>
						</a>                 
					</ul>

                </div>
                <a href="<?= base_url('Admin/profil/logout'); ?>" class="nav__link">           
                    <i class='bx bx-log-out-circle nav__icon'></i>
                    <span class="nav__text">Log out</span>
                </a>
            </nav>
        </div>
    </div>
    