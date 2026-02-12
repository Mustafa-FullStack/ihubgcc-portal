<?php
$pageTitle = 'خبر - iHUBGCC';
$currentPage = 'news';
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: /news/');
    exit;
}

// أخبار ذات صلة
$stmt = $pdo->prepare("SELECT * FROM news WHERE category = ? AND id != ? LIMIT 3");
$stmt->execute([$article['category'], $id]);
$related = $stmt->fetchAll();

include '../includes/header.php';
?>

<section class="article-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">الرئيسية</a>
            <i class="fas fa-chevron-left"></i>
            <a href="/news/">الأخبار</a>
            <i class="fas fa-chevron-left"></i>
            <span><?php echo htmlspecialchars($article['title']); ?></span>
        </div>
        <span class="article-category"><?php echo htmlspecialchars($article['category']); ?></span>
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
        <div class="article-meta">
            <span><i class="far fa-calendar"></i> <?php echo date('j F، Y', strtotime($article['publish_date'])); ?></span>
            <span><i class="far fa-user"></i> <?php echo htmlspecialchars($article['author']); ?></span>
        </div>
    </div>
</section>

<section class="article-content">
    <div class="container">
        <div class="article-layout">
            <main class="article-main">
                <div class="article-image">
                    <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                </div>
                
                <div class="article-body">
                    <?php echo $article['content']; ?>
                </div>

                <div class="article-tags">
                    <?php 
                    $tags = explode(',', $article['tags']);
                    foreach ($tags as $tag): 
                    ?>
                    <span class="tag"><?php echo trim(htmlspecialchars($tag)); ?></span>
                    <?php endforeach; ?>
                </div>

                <div class="article-share">
                    <h4>شارك المقال</h4>
                    <div class="share-buttons">
                        <a href="#" class="share-circle twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="share-circle linkedin"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="share-circle facebook"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </main>

            <aside class="article-sidebar">
                <div class="sidebar-widget">
                    <h3>أخبار ذات صلة</h3>
                    <?php foreach ($related as $item): ?>
                    <a href="article.php?id=<?php echo $item['id']; ?>" class="related-article">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="">
                        <div>
                            <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                            <span><?php echo date('j F', strtotime($item['publish_date'])); ?></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <div class="sidebar-widget newsletter-widget">
                    <h3>اشترك في النشرة</h3>
                    <p>احصل على آخر الأخبار مباشرة إلى بريدك</p>
                    <form action="/subscribe.php" method="POST">
                        <input type="email" name="email" placeholder="بريدك الإلكتروني" required>
                        <button type="submit">اشترك</button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>