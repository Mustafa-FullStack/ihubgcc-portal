<?php
$pageTitle = 'تقرير - iHUBGCC';
$currentPage = 'reports';
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM reports WHERE id = ?");
$stmt->execute([$id]);
$report = $stmt->fetch();

if (!$report) {
    header('Location: /reports/');
    exit;
}

// جلب صفقات هذا التقرير
$stmt = $pdo->prepare("SELECT * FROM deals WHERE report_id = ? ORDER BY amount DESC");
$stmt->execute([$id]);
$deals = $stmt->fetchAll();

include '../includes/header.php';
?>

<section class="report-detail-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">الرئيسية</a>
            <i class="fas fa-chevron-left"></i>
            <a href="/reports/">التقارير</a>
            <i class="fas fa-chevron-left"></i>
            <span><?php echo htmlspecialchars($report['title']); ?></span>
        </div>
        <h1><?php echo htmlspecialchars($report['title']); ?></h1>
        <div class="meta">
            <span><i class="far fa-calendar"></i> <?php echo date('j F، Y', strtotime($report['report_date'])); ?></span>
            <span><i class="far fa-clock"></i> <?php echo $report['reading_time']; ?> دقيقة قراءة</span>
        </div>
    </div>
</section>

<section class="report-content-full">
    <div class="container">
        <div class="report-stats-overview">
            <div class="stat-box">
                <i class="fas fa-handshake"></i>
                <span class="number"><?php echo $report['deals_count']; ?></span>
                <span class="label">صفقة</span>
            </div>
            <div class="stat-box highlight">
                <i class="fas fa-dollar-sign"></i>
                <span class="number"><?php echo number_format($report['total_amount']); ?></span>
                <span class="label">إجمالي المبلغ</span>
            </div>
            <div class="stat-box">
                <i class="fas fa-building"></i>
                <span class="number"><?php echo count($deals); ?></span>
                <span class="label">شركة</span>
            </div>
        </div>

        <div class="report-main-image">
            <img src="<?php echo htmlspecialchars($report['image_url']); ?>" alt="<?php echo htmlspecialchars($report['title']); ?>">
        </div>

        <div class="report-text">
            <?php echo $report['content']; ?>
        </div>

        <h2 class="deals-title">تفاصيل الصفقات</h2>
        <div class="deals-table-wrapper">
            <table class="deals-table detailed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الشركة</th>
                        <th>الدولة</th>
                        <th>القطاع</th>
                        <th>المرحلة</th>
                        <th>المبلغ</th>
                        <th>المستثمرون</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deals as $index => $deal): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td>
                            <div class="company-cell">
                                <img src="<?php echo $deal['logo_url']; ?>" alt="<?php echo $deal['company_name']; ?>" class="company-logo-small">
                                <span><?php echo htmlspecialchars($deal['company_name']); ?></span>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($deal['country']); ?></td>
                        <td><?php echo htmlspecialchars($deal['sector']); ?></td>
                        <td><span class="stage-badge <?php echo strtolower($deal['stage']); ?>"><?php echo $deal['stage']; ?></span></td>
                        <td class="amount">$<?php echo number_format($deal['amount']); ?></td>
                        <td><?php echo htmlspecialchars($deal['investors']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="share-section">
            <h3>شارك التقرير</h3>
            <div class="share-buttons">
                <a href="#" class="share-btn twitter"><i class="fab fa-twitter"></i> تويتر</a>
                <a href="#" class="share-btn linkedin"><i class="fab fa-linkedin-in"></i> لينكدإن</a>
                <a href="#" class="share-btn whatsapp"><i class="fab fa-whatsapp"></i> واتساب</a>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>