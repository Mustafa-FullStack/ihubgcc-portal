-- ============================================
-- iHUBGCC Database Schema
-- File: database.sql
-- Run this in phpMyAdmin or MySQL CLI
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS ihubgcc 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE ihubgcc;

-- ============================================
-- Table: reports (التقارير الأسبوعية)
-- ============================================
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL COMMENT 'عنوان التقرير',
    summary TEXT COMMENT 'ملخص التقرير',
    content LONGTEXT COMMENT 'محتوى التقرير الكامل',
    report_date DATE NOT NULL COMMENT 'تاريخ التقرير',
    period VARCHAR(100) COMMENT 'الفترة الزمنية (مثال: 1-7 فبراير 2026)',
    deals_count INT DEFAULT 0 COMMENT 'عدد الصفقات',
    total_amount DECIMAL(15,2) DEFAULT 0 COMMENT 'إجمالي قيمة الصفقات بالدولار',
    image_url VARCHAR(500) COMMENT 'رابط صورة التقرير',
    reading_time INT DEFAULT 5 COMMENT 'وقت القراءة بالدقائق',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_report_date (report_date),
    INDEX idx_period (period)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='التقارير الأسبوعية للاستثمار الجريء';

-- ============================================
-- Table: news (الأخبار)
-- ============================================
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL COMMENT 'عنوان الخبر',
    content LONGTEXT COMMENT 'محتوى الخبر',
    excerpt TEXT COMMENT 'مقتطف من الخبر',
    category VARCHAR(100) COMMENT 'تصنيف الخبر',
    author VARCHAR(100) COMMENT 'كاتب الخبر',
    publish_date DATE NOT NULL COMMENT 'تاريخ النشر',
    image_url VARCHAR(500) COMMENT 'صورة الخبر الرئيسية',
    tags VARCHAR(500) COMMENT 'الوسوم مفصولة بفواصل',
    views INT DEFAULT 0 COMMENT 'عدد المشاهدات',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_publish_date (publish_date),
    INDEX idx_category (category),
    FULLTEXT idx_title_content (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='أخبار الشركات الناشئة';

-- ============================================
-- Table: deals (الصفقات الاستثمارية)
-- ============================================
CREATE TABLE deals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT COMMENT 'رقم التقرير المرتبط (اختياري)',
    company_name VARCHAR(200) NOT NULL COMMENT 'اسم الشركة',
    company_name_en VARCHAR(200) COMMENT 'اسم الشركة بالإنجليزية',
    country VARCHAR(100) COMMENT 'الدولة',
    city VARCHAR(100) COMMENT 'المدينة',
    sector VARCHAR(100) COMMENT 'القطاع',
    sub_sector VARCHAR(100) COMMENT 'القطاع الفرعي',
    stage VARCHAR(50) COMMENT 'مرحلة التمويل (Pre-seed, Seed, Series A, etc)',
    amount DECIMAL(15,2) COMMENT 'مبلغ التمويل بالدولار',
    amount_sar DECIMAL(15,2) COMMENT 'مبلغ التمويل بالريال السعودي',
    investors TEXT COMMENT 'المستثمرون (مفصولون بفواصل)',
    lead_investor VARCHAR(200) COMMENT 'المستثمر الرئيسي',
    description TEXT COMMENT 'وصف الصفقة',
    company_website VARCHAR(200) COMMENT 'موقع الشركة',
    logo_url VARCHAR(500) COMMENT 'شعار الشركة',
    deal_date DATE COMMENT 'تاريخ الإعلان عن الصفقة',
    founded_year INT COMMENT 'سنة تأسيس الشركة',
    employees_count INT COMMENT 'عدد الموظفين',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE SET NULL,
    INDEX idx_company_name (company_name),
    INDEX idx_country (country),
    INDEX idx_sector (sector),
    INDEX idx_stage (stage),
    INDEX idx_deal_date (deal_date),
    INDEX idx_amount (amount)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='صفقات الاستثمار الجريء';

-- ============================================
-- Table: subscribers (المشتركون)
-- ============================================
CREATE TABLE subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(200) NOT NULL UNIQUE COMMENT 'البريد الإلكتروني',
    name VARCHAR(100) COMMENT 'الاسم (اختياري)',
    country VARCHAR(100) COMMENT 'الدولة',
    interests VARCHAR(500) COMMENT 'الاهتمامات (مفصولة بفواصل)',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'هل الاشتراك نشط؟',
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL DEFAULT NULL,
    ip_address VARCHAR(45) COMMENT 'عنوان IP',
    INDEX idx_email (email),
    INDEX idx_subscribed_at (subscribed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='المشتركون في النشرة البريدية';

-- ============================================
-- Table: contact_messages (رسائل التواصل)
-- ============================================
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL COMMENT 'اسم المرسل',
    email VARCHAR(200) NOT NULL COMMENT 'البريد الإلكتروني',
    subject VARCHAR(200) COMMENT 'الموضوع',
    message TEXT NOT NULL COMMENT 'الرسالة',
    is_read BOOLEAN DEFAULT FALSE COMMENT 'هل تم قراءتها؟',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    replied_at TIMESTAMP NULL DEFAULT NULL,
    reply_message TEXT COMMENT 'رد الإدارة',
    INDEX idx_created_at (created_at),
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='رسائل اتصل بنا';

-- ============================================
-- Table: users (المستخدمين - للإدارة)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(200) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(200),
    role ENUM('admin', 'editor', 'viewer') DEFAULT 'editor',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='مستخدمي لوحة التحكم';

-- ============================================
-- Insert Sample Data (بيانات تجريبية)
-- ============================================

-- Reports
INSERT INTO reports (title, summary, report_date, period, deals_count, total_amount, image_url, reading_time) VALUES
('صفقات استثمار معلنة في الشركات الناشئة بالمنطقة العربية للفترة 1 – 7 فبراير 2026', 
 'شهدت الفترة من 1 إلى 7 فبراير 2026 نشاطاً ملحوظاً في مجال الاستثمار الجريء بالمنطقة العربية، حيث تم الإعلان عن 8 صفقات بقيمة إجمالية تجاوزت 13.8 مليون دولار.', 
 '2026-02-08', '1-7 فبراير 2026', 8, 13808333, 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80', 5),

('صفقات الاستثمار الجريء في الشركات الناشئة بالمنطقة العربية للفترة 25 – 31 يناير 2026', 
 'أسبوع استثنائي شهد إعلان 13 صفقة استثمارية بقيمة إجمالية 216 مليون دولار، مع ظهور استثمارات ضخمة في قطاع التقنية المالية.', 
 '2026-02-02', '25-31 يناير 2026', 13, 216000000, 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80', 7),

('صفقات استثمار جريء معلنة في الشركات الناشئة بالمنطقة العربية للفترة 18 – 24 يناير 2026', 
 'تسع صفقات استثمارية بقيمة 47.6 مليون دولار، مع تنوع ملحوظ في القطاعات المستثمرة.', 
 '2026-01-25', '18-24 يناير 2026', 9, 47630000, 'https://images.unsplash.com/photo-1553729459-efe14ef6055d?w=800&q=80', 6),

('أكبر 10 جولات تمويل الشركات الناشئة بالمملكة العربية السعودية لعام 2025', 
 'تقرير خاص يستعرض أضخم الصفقات الاستثمارية في المملكة خلال عام 2025، بقيمة إجمالية تجاوزت مليار دولار.', 
 '2026-02-11', 'عام 2025', 10, 1200000000, 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80', 10);

-- News
INSERT INTO news (title, content, category, author, publish_date, image_url, tags) VALUES
('ساري تجمع 400 مليون دولار في أكبر جولة تمويل سعودية', 
 '<p>أعلنت شركة ساري، المنصة الرائدة في مجال التجارة الإلكترونية B2B، عن جمعها 400 مليون دولار في جولة تمويل Series C بقيادة صندوق الاستثمارات العامة وSanabil Investments.</p><p>تأسست ساري عام 2019 بهدف تمكين تجار التجزئة من شراء المنتجات بشكل مباشر من الموردين، وقد نجحت في تسهيل عمليات تجارية بقيمة تجاوزت 2 مليار ريال.</p>', 
 'تقنية مالية', 'فريق التحرير', '2026-02-10', 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=800&q=80', 'ساري, تمويل, سعودية, تجارة إلكترونية'),

('تمارا ترفع مستوى التمويل إلى 175 مليون دولار', 
 '<p>أعلنت شركة تمارا، منصة "اشتري الآن وادفع لاحقاً" الرائدة، عن زيادة جولتها التمويلية إلى 175 مليون دولار، بمشاركة من Checkout.com وCoatue.</p><p>تأسست تمارا عام 2020 وتقدم حلول دفع مرنة للمستهلكين في السعودية والإمارات.</p>', 
 'تقنية مالية', 'فريق التحرير', '2026-02-08', 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800&q=80', 'تمارا, BNPL, تمويل, سعودية'),

('الموارد تجمع 200 مليون دولار لتوسيع عملياتها', 
 '<p>أعلنت شركة الموارد، منصة التمويل العقاري الرقمية، عن جمع 200 مليون دولار في جولة تمويل بقيادة STV وRaed Ventures.</p>', 
 'عقارات رقمية', 'فريق التحرير', '2026-02-05', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80', 'الموارد, عقارات, تمويل, سعودية');

-- Deals
INSERT INTO deals (report_id, company_name, company_name_en, country, city, sector, stage, amount, investors, description, deal_date, founded_year) VALUES
(1, 'تمارا', 'Tamara', 'السعودية', 'الرياض', 'التقنية المالية', 'Series A', 5000000, 'Impact46, Vision Ventures', 'منصة BNPL رائدة في المنطقة', '2026-02-05', 2020),
(1, 'سيلة', 'Sila', 'الإمارات', 'دبي', 'التقنية المالية', 'Seed', 3200000, 'Global Ventures, MEVP', 'منصة مدفوعات للشركات', '2026-02-04', 2022),
(1, 'موقع', 'Mawq3', 'مصر', 'القاهرة', 'العقارات', 'Pre-seed', 1500000, 'Flat6Labs, Algebra Ventures', 'منصة عقارات رقمية', '2026-02-03', 2023),
(1, 'أكورد', 'Akkord', 'السعودية', 'جدة', 'إدارة المخزون', 'Series A', 4000000, 'Raed Ventures, Impact46', 'حلول إدارة المخزون للتجزئة', '2026-02-02', 2021),
(1, 'نومو', 'Nomo', 'الإمارات', 'أبوظبي', 'التقنية المالية', 'Seed', 2800000, 'ADQ, Shorooq Partners', 'خدمات مصرفية رقمية', '2026-02-01', 2021),

(2, 'ساري', 'Sary', 'السعودية', 'الرياض', 'التجارة الإلكترونية', 'Series C', 400000000, 'Sanabil Investments, PIF', 'منصة B2B للتجارة الإلكترونية', '2026-01-30', 2019),
(2, 'الموارد', 'Almoared', 'السعودية', 'الرياض', 'العقارات الرقمية', 'Growth', 200000000, 'STV, Raed Ventures', 'تمويل عقاري رقمي', '2026-01-28', 2018),
(2, 'تمارا', 'Tamara', 'السعودية', 'الرياض', 'التقنية المالية', 'Series B', 175000000, 'Checkout.com, Coatue', 'منصة BNPL', '2026-01-27', 2020),
(2, 'سيلفي', 'Selfie', 'السعودية', 'الرياض', 'التجارة الإلكترونية', 'Series B', 130000000, 'SNB Capital, Derayah', 'منصة تجارة إلكترونية', '2026-01-26', 2020),

(3, 'أكورد', 'Akkord', 'السعودية', 'جدة', 'إدارة المخزون', 'Series A', 105000000, 'Vision Ventures, Raed Ventures', 'توسيع Series A', '2026-01-24', 2021),
(3, 'نيو', 'Nio', 'الإمارات', 'دبي', 'التقنية المالية', 'Series A', 100000000, 'Global Ventures, Mubadala', 'خدمات مالية رقمية', '2026-01-22', 2019),
(3, 'لمسة', 'Lamsa', 'الأردن', 'عمان', 'التعليم', 'Series B', 95000000, 'Endure Capital, Wamda', 'منصة تعليمية للأطفال', '2026-01-20', 2012),
(3, 'بيتك', 'Beytik', 'مصر', 'القاهرة', 'العقارات', 'Series A', 85000000, 'Algebra Ventures, Nclude', 'تقنية عقارية', '2026-01-19', 2020);

-- Admin User (password: admin123 - change in production!)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@ihubgcc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'مدير النظام', 'admin');

-- ============================================
-- Views (عرض مبسط للبيانات)
-- ============================================

CREATE VIEW v_monthly_stats AS
SELECT 
    DATE_FORMAT(deal_date, '%Y-%m') as month,
    COUNT(*) as deal_count,
    SUM(amount) as total_amount,
    AVG(amount) as avg_amount
FROM deals
GROUP BY DATE_FORMAT(deal_date, '%Y-%m')
ORDER BY month DESC;

CREATE VIEW v_sector_summary AS
SELECT 
    sector,
    COUNT(*) as deal_count,
    SUM(amount) as total_amount,
    ROUND(AVG(amount), 2) as avg_amount
FROM deals
GROUP BY sector
ORDER BY total_amount DESC;

-- ============================================
-- Stored Procedures (إجراءات مخزنة)
-- ============================================

DELIMITER //

CREATE PROCEDURE GetDealsByCountry(IN p_country VARCHAR(100))
BEGIN
    SELECT * FROM deals 
    WHERE country = p_country 
    ORDER BY deal_date DESC;
END //

CREATE PROCEDURE GetTopDeals(IN p_limit INT)
BEGIN
    SELECT * FROM deals 
    ORDER BY amount DESC 
    LIMIT p_limit;
END //

DELIMITER ;

-- ============================================
-- Triggers (محفزات)
-- ============================================

DELIMITER //

CREATE TRIGGER after_deal_insert 
AFTER INSERT ON deals
FOR EACH ROW
BEGIN
    UPDATE reports 
    SET deals_count = deals_count + 1,
        total_amount = total_amount + NEW.amount
    WHERE id = NEW.report_id;
END //

DELIMITER ;