<?php
$pageTitle = 'التقارير - iHUBGCC';
$currentPage = 'reports';
require_once '../includes/db.php';

$page = $_GET['page'] ?? 1;
$perPage = 9;
$offset = ($page - 1) * $perPage;

// جلب التقارير مع الترقيم
$stmt = $pdo->prepare("SELECT * FROM reports ORDER BY report_date DESC LIMIT ? OFFSET ?");
$stmt->execute([$perPage, $offset]);
$reports = $stmt->fetchAll();

// عدد التقارير الكلي للترقيم
$totalReports = $pdo->query("SELECT COUNT(*) FROM reports")->fetchColumn();
$totalPages = ceil($totalReports / $perPage);

include '../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>التقارير الأسبوعية</h1>
        <p>تقارير شاملة لصفقات الاستثمار الجريء في المنطقة العربية</p>
    </div>
</section>

<section class="reports-list">
    <div class="container">
        <div class="reports-grid">
            <?php foreach($reports as $report): ?>
            <article class="report-card fade-in">
                <div class="report-image-wrapper">
                    <img src="<?php echo htmlspecialchars($report['image_url']); ?>" alt="<?php echo htmlspecialchars($report['title']); ?>" class="report-image">
                    <span class="report-period"><?php echo $report['period']; ?></span>
                </div>
                <div class="report-content">
                    <div class="report-date">
                        <i class="far fa-calendar-alt"></i>
                        <span><?php echo date('j F، Y', strtotime($report['report_date'])); ?></span>
                    </div>
                    <h3 class="report-title"><?php echo htmlspecialchars($report['title']); ?></h3>
                    <p class="report-summary"><?php echo substr(strip_tags($report['summary']), 0, 200); ?>...</p>
                    
                    <div class="report-highlights">
                        <div class="highlight">
                            <span class="number"><?php echo $report['deals_count']; ?></span>
                            <span class="label">صفقة</span>
                        </div>
                        <div class="highlight">
                            <span class="number"><?php echo number_format($report['total_amount']); ?></span>
                            <span class="label">دولار</span>
                        </div>
                    </div>
                    
                    <a href="report-detail.php?id=<?php echo $report['id']; ?>" class="btn-primary">
                        عرض التقرير الكامل
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>" class="prev">
                <i class="fas fa-chevron-right"></i> السابق
            </a>
            <?php endif; ?>
            
            <div class="page-numbers">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
            </div>
            
            <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>" class="next">
                التالي <i class="fas fa-chevron-left"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>