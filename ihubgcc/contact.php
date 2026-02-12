<?php
$pageTitle = 'اتصل بنا - iHUBGCC';
$currentPage = 'contact';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'يرجى ملء جميع الحقول المطلوبة';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'يرجى إدخال بريد إلكتروني صحيح';
    } else {
        // هنا يمكنك إضافة كود إرسال البريد
        $success = true;
    }
}

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>اتصل بنا</h1>
        <p>نحن هنا لمساعدتك</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info fade-in">
                <h2>معلومات التواصل</h2>
                <p>يمكنك التواصل معنا عبر القنوات التالية:</p>
                
                <div class="info-items">
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>البريد الإلكتروني</h4>
                            <p>info@ihubgcc.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>الموقع</h4>
                            <p>المملكة العربية السعودية</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>ساعات العمل</h4>
                            <p>الأحد - الخميس: 9:00 ص - 5:00 م</p>
                        </div>
                    </div>
                </div>

                <div class="social-contact">
                    <h4>تابعنا على</h4>
                    <div class="social-links large">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper fade-in">
                <h2>أرسل لنا رسالة</h2>
                
                <?php if ($success): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i>
                    تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <form class="contact-form" method="POST" action="">
                    <div class="form-group">
                        <label>الاسم الكامل *</label>
                        <input type="text" name="name" required placeholder="أدخل اسمك">
                    </div>
                    
                    <div class="form-group">
                        <label>البريد الإلكتروني *</label>
                        <input type="email" name="email" required placeholder="أدخل بريدك الإلكتروني">
                    </div>
                    
                    <div class="form-group">
                        <label>الموضوع</label>
                        <select name="subject">
                            <option value="general">استفسار عام</option>
                            <option value="report">طلب تقرير</option>
                            <option value="partnership">شراكة</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>الرسالة *</label>
                        <textarea name="message" rows="5" required placeholder="اكتب رسالتك هنا..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        إرسال الرسالة
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>