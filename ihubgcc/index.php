<?php
$pageTitle = 'iHUBGCC - مركز الابتكار الخليجي';
$currentPage = 'home';
require_once 'includes/db.php';

// جلب آخر 3 تقارير
$stmt = $pdo->query("SELECT * FROM reports ORDER BY report_date DESC LIMIT 3");
$latestReports = $stmt->fetchAll();

// جلب آخر 4 أخبار
$stmt = $pdo->query("SELECT * FROM news ORDER BY publish_date DESC LIMIT 4");
$latestNews = $stmt->fetchAll();

// إحصائيات
$stats = $pdo->query("SELECT 
    COUNT(*) as total_deals,
    SUM(amount) as total_amount,
    COUNT(DISTINCT country) as total_countries 
    FROM deals")->fetch();

include 'includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-badge fade-in">
            <i class="fas fa-chart-line"></i>
            <span>أحدث تقارير الاستثمار الجريء</span>
        </div>
        <h1 class="fade-in">
            مركز الابتكار <span>الخليجي</span>
        </h1>
        <p class="fade-in">
            نقدم لك أحدث التقارير والتحليلات حول النظام البيئي للشركات الناشئة والاستثمار الجريء في المنطقة العربية
        </p>
        
        <div class="stats-bar">
            <div class="stat-item fade-in">
                <span class="stat-number" data-target="<?php echo $stats['total_deals'] ?? 30; ?>">0</span>
                <div class="stat-label">صفقة استثمارية</div>
            </div>
            <div class="stat-item fade-in">
                <span class="stat-number" data-target="<?php echo round(($stats['total_amount'] ?? 276000000) / 1000000); ?>">0</span>
                <div class="stat-label">مليون دولار إجمالي الاستثمارات</div>
            </div>
            <div class="stat-item fade-in">
                <span class="stat-number" data-target="<?php echo $stats['total_countries'] ?? 15; ?>">0</span>
                <div class="stat-label">دولة عربية</div>
            </div>
        </div>
    </section>

    <!-- Latest Reports -->
    <section id="reports">
        <div class="section-header fade-in">
            <h2>أحدث التقارير الأسبوعية</h2>
            <p>تقارير شاملة لصفقات الاستثمار الجريء في المنطقة العربية</p>
            <a href="/reports/" class="view-all">عرض جميع التقارير <i class="fas fa-arrow-left"></i></a>
        </div>

        <div class="reports-grid">
            <?php foreach($latestReports as $report): ?>
            <article class="report-card fade-in">
                <img src="<?php echo htmlspecialchars($report['image_url']); ?>" alt="<?php echo htmlspecialchars($report['title']); ?>" class="report-image">
                <div class="report-content">
                    <div class="report-date">
                        <i class="far fa-calendar-alt"></i>
                        <span><?php echo date('j F، Y', strtotime($report['report_date'])); ?></span>
                    </div>
                    <h3 class="report-title"><?php echo htmlspecialchars($report['title']); ?></h3>
                    <div class="report-stats">
                        <div class="report-stat">
                            <span class="report-stat-value"><?php echo $report['deals_count']; ?></span>
                            <span class="report-stat-label">صفقات</span>
                        </div>
                        <div class="report-stat">
                            <span class="report-stat-value"><?php echo number_format($report['total_amount'] / 1000000, 1); ?>M</span>
                            <span class="report-stat-label">دولار</span>
                        </div>
                    </div>
                    <a href="/reports/report-detail.php?id=<?php echo $report['id']; ?>" class="read-more">
                        اقرأ التقرير كاملاً
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Latest News -->
    <section id="news" style="background: rgba(255,255,255,0.02);">
        <div class="section-header fade-in">
            <h2>آخر الأخبار</h2>
            <p>أحدث تطورات النظام البيئي للشركات الناشئة</p>
            <a href="/news/" class="view-all">عرض جميع الأخبار <i class="fas fa-arrow-left"></i></a>
        </div>

        <div class="news-grid">
            <?php foreach($latestNews as $news): ?>
            <article class="news-card fade-in">
                <div class="news-image-wrapper">
                    <img src="<?php echo htmlspecialchars($news['image_url']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" class="news-image">
                    <span class="news-category"><?php echo htmlspecialchars($news['category']); ?></span>
                </div>
                <div class="news-content">
                    <div class="news-date">
                        <i class="far fa-clock"></i>
                        <span><?php echo date('j F، Y', strtotime($news['publish_date'])); ?></span>
                    </div>
                    <h3 class="news-title"><?php echo htmlspecialchars($news['title']); ?></h3>
                    <p class="news-excerpt"><?php echo substr(strip_tags($news['content']), 0, 150) . '...'; ?></p>
                    <a href="/news/article.php?id=<?php echo $news['id']; ?>" class="read-more">
                        اقرأ المزيد
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Featured Section: Top 10 Deals -->
    <section id="featured">
        <div class="section-header fade-in">
            <h2>أكبر 10 جولات تمويل في السعودية 2025</h2>
            <p>تقرير خاص بأضخم الصفقات الاستثمارية</p>
        </div>
        
        <div class="featured-chart fade-in">
            <div class="chart-container" id="fundingChart">
                <!-- سيتم رسم الرسم البياني هنا باستخدام JavaScript -->
                <div class="chart-bar" style="--value: 400; --color: #00d4aa;">
                    <span class="bar-label">ساري</span>
                    <span class="bar-value">$400M</span>
                </div>
                <div class="chart-bar" style="--value: 200; --color: #0891b2;">
                    <span class="bar-label">الموارد</span>
                    <span class="bar-value">$200M</span>
                </div>
                <div class="chart-bar" style="--value: 175; --color: #f59e0b;">
                    <span class="bar-label">تمارا</span>
                    <span class="bar-value">$175M</span>
                </div>
                <div class="chart-bar" style="--value: 130; --color: #ec4899;">
                    <span class="bar-label">سيلفي</span>
                    <span class="bar-value">$130M</span>
                </div>
                <div class="chart-bar" style="--value: 105; --color: #8b5cf6;">
                    <span class="bar-label">أكورد</span>
                    <span class="bar-value">$105M</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section id="contact">
        <div class="newsletter fade-in">
            <h2>اشترك في نشرتنا الأسبوعية</h2>
            <p>احصل على أحدث تقارير الاستثمار الجريء مباشرة إلى بريدك الإلكتروني</p>
            <form class="newsletter-form" action="/subscribe.php" method="POST">
                <input type="email" name="email" placeholder="أدخل بريدك الإلكتروني" required>
                <button type="submit">اشترك الآن</button>
            </form>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>