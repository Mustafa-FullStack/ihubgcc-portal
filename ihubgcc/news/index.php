<?php
$pageTitle = 'الأخبار - iHUBGCC';
$currentPage = 'news';
require_once '../includes/db.php';

$page = $_GET['page'] ?? 1;
$category = $_GET['category'] ?? '';
$perPage = 12;
$offset = ($page - 1) * $perPage;

// فلترة حسب التصنيف
$params = [];
$sql = "SELECT * FROM news WHERE 1=1";
if ($category) {
    $sql .= " AND category = ?";
    $params[] = $category;
}
$sql .= " ORDER BY publish_date DESC LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = $offset;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$news = $stmt->fetchAll();

// التصنيفات
$categories = $pdo->query("SELECT DISTINCT category FROM news ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

include '../includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>آخر الأخبار</h1>
        <p>أحدث تطورات النظام البيئي للشركات الناشئة</p>
    </div>
</section>

<section class="news-filter">
    <div class="container">
        <div class="filter-tabs">
            <a href="?" class="<?php echo empty($category) ? 'active' : ''; ?>">الكل</a>
            <?php foreach ($categories as $cat): ?>
            <a href="?category=<?php echo urlencode($cat); ?>" class="<?php echo $category == $cat ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($cat); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="news-list">
    <div class="container">
        <div class="news-grid large">
            <?php foreach ($news as $item): ?>
            <article class="news-card horizontal fade-in">
                <div class="news-image-wrapper">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                </div>
                <div class="news-content">
                    <span class="news-category-tag"><?php echo htmlspecialchars($item['category']); ?></span>
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo substr(strip_tags($item['content']), 0, 250); ?>...</p>
                    <div class="news-meta">
                        <span><i class="far fa-calendar"></i> <?php echo date('j F، Y', strtotime($item['publish_date'])); ?></span>
                        <a href="article.php?id=<?php echo $item['id']; ?>" class="read-more">اقرأ المزيد <i class="fas fa-arrow-left"></i></a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>