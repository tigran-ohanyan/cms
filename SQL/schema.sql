CREATE TABLE IF NOT EXISTS users (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT DEFAULT 0,
    created_at DATETIME
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pages (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     slug VARCHAR(200) NOT NULL UNIQUE,
    title VARCHAR(255),
    content TEXT,
    created_at DATETIME,
    updated_at DATETIME
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
