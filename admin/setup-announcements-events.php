<?php
require_once '../config/database.php';

// Create announcements table
$sql_announcements = "CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    image VARCHAR(255),
    category VARCHAR(100) DEFAULT 'General',
    status ENUM('draft', 'published') DEFAULT 'published',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// Create events table
$sql_events = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    image VARCHAR(255),
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255),
    status ENUM('draft', 'published') DEFAULT 'published',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

try {
    $pdo->exec($sql_announcements);
    echo "Announcements table created successfully!<br>";
    
    $pdo->exec($sql_events);
    echo "Events table created successfully!<br>";
    
    // Insert sample announcements
    $sampleAnnouncements = [
        [
            'title' => 'New Energy Policy Guidelines Released',
            'slug' => 'new-energy-policy-guidelines-released',
            'excerpt' => 'ECN announces new guidelines for energy policy implementation across Nigeria.',
            'content' => 'The Energy Commission of Nigeria has released comprehensive guidelines for energy policy implementation. These guidelines will help streamline energy projects and ensure sustainable development across the country.',
            'category' => 'Policy',
            'featured' => true
        ],
        [
            'title' => 'Training Program for Energy Professionals',
            'slug' => 'training-program-energy-professionals',
            'excerpt' => 'Registration now open for the annual energy professionals training program.',
            'content' => 'ECN is pleased to announce the opening of registration for our annual training program designed for energy professionals. This comprehensive program covers the latest developments in renewable energy, policy implementation, and sustainable practices.',
            'category' => 'Training',
            'featured' => false
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO announcements (title, slug, excerpt, content, category, featured) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($sampleAnnouncements as $announcement) {
        $stmt->execute([
            $announcement['title'],
            $announcement['slug'],
            $announcement['excerpt'],
            $announcement['content'],
            $announcement['category'],
            $announcement['featured']
        ]);
    }
    
    // Insert sample events
    $sampleEvents = [
        [
            'title' => 'National Energy Summit 2025',
            'slug' => 'national-energy-summit-2025',
            'excerpt' => 'Join us for the annual National Energy Summit focusing on sustainable energy solutions.',
            'content' => 'The National Energy Summit 2025 will bring together energy experts, policymakers, and stakeholders to discuss the future of energy in Nigeria. Topics include renewable energy adoption, energy security, and climate change mitigation.',
            'event_date' => '2025-03-15',
            'event_time' => '09:00:00',
            'location' => 'Abuja International Conference Centre',
            'featured' => true
        ],
        [
            'title' => 'Renewable Energy Workshop',
            'slug' => 'renewable-energy-workshop',
            'excerpt' => 'Technical workshop on renewable energy technologies and implementation strategies.',
            'content' => 'This workshop will cover the latest developments in solar, wind, and hydroelectric power technologies. Participants will learn about implementation strategies, financing options, and policy frameworks.',
            'event_date' => '2025-02-20',
            'event_time' => '10:00:00',
            'location' => 'ECN Headquarters, Abuja',
            'featured' => false
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO events (title, slug, excerpt, content, event_date, event_time, location, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($sampleEvents as $event) {
        $stmt->execute([
            $event['title'],
            $event['slug'],
            $event['excerpt'],
            $event['content'],
            $event['event_date'],
            $event['event_time'],
            $event['location'],
            $event['featured']
        ]);
    }
    
    echo "Sample data inserted successfully!<br>";
    echo "<a href='index.php'>Go to Admin Dashboard</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>