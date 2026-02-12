<?php
$pageTitle = 'قاعدة البيانات - iHUBGCC';
$currentPage = 'database';
require_once '../includes/db.php';

// فلاتر البحث
$country = $_GET['country'] ?? '';
$sector = $_GET['sector'] ?? '';
$stage = $_GET['stage'] ?? '';
$search = $_GET['search'] ?? '';

// بناء الاستعلام
$params = [];
$sql = "SELECT * FROM deals WHERE 1=1";

if ($country) {
    $sql .= " AND country = ?";
    $params[] = $country;
}
if ($sector) {
    $sql .= " AND sector = ?";
    $params[] = $sector;
}
if ($stage) {
    $sql .= " AND stage = ?";
    $params[] = $stage;
}
if ($search) {
    $sql .= " AND (company_name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY deal_date DESC LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$deals = $stmt->fetchAll();

// إحصائيات للفلاتر
$countries = $pdo->query("SELECT DISTINCT country FROM deals ORDER BY country")->fetchAll(PDO::FETCH_COLUMN);
$sectors = $pdo->query("SELECT DISTINCT sector FROM deals ORDER BY sector")->fetchAll(PDO::FETCH_COLUMN);
$stages = $pdo->query("SELECT DISTINCT stage FROM deals ORDER BY stage")->fetchAll(PDO::FETCH_COLUMN);

// إجماليات
$totalAmount = $pdo->query("SELECT SUM(amount) FROM deals")->fetchColumn();
$totalDeals = $pdo->query("SELECT COUNT(*) FROM deals")->fetchColumn();

include '../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>قاعدة بيانات الصفقات</h1>
        <p>تصفح وابحث في جميع صفقات الاستثمار الجريء المعلنة</p>
    </div>
</section>

<section class="database-stats">
    <div class="container">
        <div class="stats-row">
            <div class="stat-card">
                <i class="fas fa-handshake"></i>
                <span class="number"><?php echo number_format($totalDeals); ?></span>
                <span class="label">إجمالي الصفقات</span>
            </div>
            <div class="stat-card highlight">
                <i class="fas fa-dollar-sign"></i>
                <span class="number"><?php echo number_format($totalAmount / 1000000, 1); ?>M</span>
                <span class="label">إجمالي الاستثمارات</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-globe"></i>
                <span class="number"><?php echo count($countries); ?></span>
                <span class="label">دولة</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-industry"></i>
                <span class="number"><?php echo count($sectors); ?></span>
                <span class="label">قطاع</span>
            </div>
        </div>
    </div>
</section>

<section class="database-content">
    <div class="container">
        <div class="filters-bar">
            <form method="GET" class="search-form">
                <div class="search-box large">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="ابحث عن شركة...">
                </div>
                
                <select name="country" onchange="this.form.submit()">
                    <option value="">جميع الدول</option>
                    <?php foreach ($countries as $c): ?>
                    <option value="<?php echo $c; ?>" <?php echo $country == $c ? 'selected' : ''; ?>><?php echo $c; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <select name="sector" onchange="this.form.submit()">
                    <option value="">جميع القطاعات</option>
                    <?php foreach ($sectors as $s): ?>
                    <option value="<?php echo $s; ?>" <?php echo $sector == $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <select name="stage" onchange="this.form.submit()">
                    <option value="">جميع المراحل</option>
                    <?php foreach ($stages as $st): ?>
                    <option value="<?php echo $st; ?>" <?php echo $stage == $st ? 'selected' : ''; ?>><?php echo $st; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="btn-filter">تصفية</button>
            </form>
        </div>

        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>الشركة</th>
                        <th>الدولة</th>
                        <th>القطاع</th>
                        <th>المرحلة</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                        <th>المستثمرون</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deals as $deal): ?>
                    <tr>
                        <td>
                            <div class="company-info">
                                <img src="<?php echo $deal['logo_url']; ?>" alt="" class="company-logo">
                                <div>
                                    <strong><?php echo htmlspecialchars($deal['company_name']); ?></strong>
                                    <small><?php echo substr(strip_tags($deal['description']), 0, 50); ?>...</small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($deal['country']); ?></td>
                        <td><?php echo htmlspecialchars($deal['sector']); ?></td>
                        <td><span class="stage-badge <?php echo strtolower(str_replace(' ', '-', $deal['stage'])); ?>"><?php echo $deal['stage']; ?></span></td>
                        <td class="amount-cell">$<?php echo number_format($deal['amount']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($deal['deal_date'])); ?></td>
                        <td><?php echo htmlspecialchars($deal['investors']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="export-options">
            <button class="btn-export" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> تصدير Excel
            </button>
            <button class="btn-export" onclick="exportToPDF()">
                <i class="fas fa-file-pdf"></i> تصدير PDF
            </button>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>