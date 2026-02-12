<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'iHUBGCC - مركز الابتكار الخليجي'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
    <!-- Loader -->
    <div class="loader" id="loader">
        <div class="loader-spinner"></div>
    </div>

    <!-- Background Animation -->
    <div class="bg-animation"></div>

    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="/" class="logo">
                <i class="fas fa-rocket"></i>
                <span>iHUBGCC</span>
            </a>
            <ul class="nav-links">
                <li><a href="/" class="<?php echo $currentPage == 'home' ? 'active' : ''; ?>">الرئيسية</a></li>
                <li><a href="/reports/" class="<?php echo $currentPage == 'reports' ? 'active' : ''; ?>">التقارير</a></li>
                <li><a href="/news/" class="<?php echo $currentPage == 'news' ? 'active' : ''; ?>">الأخبار</a></li>
                <li><a href="/database/" class="<?php echo $currentPage == 'database' ? 'active' : ''; ?>">قاعدة البيانات</a></li>
                <li><a href="/education/" class="<?php echo $currentPage == 'education' ? 'active' : ''; ?>">التعليم</a></li>
                <li><a href="/about.php" class="<?php echo $currentPage == 'about' ? 'active' : ''; ?>">من نحن</a></li>
                <li><a href="/contact.php" class="<?php echo $currentPage == 'contact' ? 'active' : ''; ?>">اتصل بنا</a></li>
            </ul>
            <div class="mobile-menu" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>
    <div class="mobile-nav" id="mobileNav">
        <a href="/">الرئيسية</a>
        <a href="/reports/">التقارير</a>
        <a href="/news/">الأخبار</a>
        <a href="/database/">قاعدة البيانات</a>
        <a href="/education/">التعليم</a>
        <a href="/about.php">من نحن</a>
        <a href="/contact.php">اتصل بنا</a>
    </div>