DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    last_login DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    deleted_at DATETIME NULL,
    CONSTRAINT pk_users PRIMARY KEY(id)
) DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO users (name, email, password, role, status, created_at, updated_at)
VALUES (
    'Administrator',
    'admin@admin.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: admin123
    'admin',
    'active',
    NOW(),
    NOW()
); 