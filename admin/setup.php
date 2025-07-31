<?php
require_once '../config/database.php';

// Create news table
$sql = "CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    thumbnail VARCHAR(255),
    gallery TEXT,
    category VARCHAR(100) DEFAULT 'News',
    author VARCHAR(100) DEFAULT 'Admin',
    status ENUM('draft', 'published') DEFAULT 'published',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

try {
    $pdo->exec($sql);
    echo "Database table 'news' created successfully!<br>";
    
    // Insert sample data
    $sampleNews = [
        [
            'title' => 'Nigeria\'s Energy Transition Plan: Progress and Future Outlook',
            'slug' => 'nigeria-energy-transition-plan-progress-future-outlook',
            'excerpt' => 'The Energy Commission of Nigeria continues to lead the country\'s transition towards sustainable energy sources.',
            'content' => 'The Energy Commission of Nigeria continues to lead the country\'s transition towards sustainable energy sources. Our latest initiatives focus on renewable energy development and energy efficiency programs. This comprehensive plan outlines our strategic approach to achieving sustainable energy goals by 2030.',
            'thumbnail' => 'assets/images/blog/news-1-1.jpg',
            'category' => 'Energy Policy',
            'featured' => true
        ],
        [
            'title' => 'New Solar Energy Research Initiative Launched',
            'slug' => 'new-solar-energy-research-initiative-launched',
            'excerpt' => 'ECN announces a groundbreaking solar energy research program in collaboration with leading universities.',
            'content' => 'ECN announces a groundbreaking solar energy research program in collaboration with leading universities across Nigeria. This initiative aims to enhance solar technology adoption nationwide and develop innovative solutions for rural electrification.',
            'thumbnail' => 'assets/images/blog/news-1-2.jpg',
            'category' => 'Research',
            'featured' => false
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO news (title, slug, excerpt, content, thumbnail, category, featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($sampleNews as $news) {
        $stmt->execute([
            $news['title'],
            $news['slug'],
            $news['excerpt'],
            $news['content'],
            $news['thumbnail'],
            $news['category'],
            $news['featured']
        ]);
    }
    
    echo "Sample news data inserted successfully!<br>";
    echo "<a href='index.php'>Go to Admin Dashboard</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>