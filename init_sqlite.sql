
-- SQLite Database Schema for MENTORA Educational Portal

-- Enable foreign key constraints
PRAGMA foreign_keys = ON;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    student_id VARCHAR(20) UNIQUE,
    university VARCHAR(100),
    department VARCHAR(50) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Departments table
CREATE TABLE IF NOT EXISTS departments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(50) NOT NULL,
    code VARCHAR(10) NOT NULL
);

-- Modules table
CREATE TABLE IF NOT EXISTS modules (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    department_id INTEGER,
    module_type VARCHAR(50),
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(50) NOT NULL,
    department_id INTEGER,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Uploads table
CREATE TABLE IF NOT EXISTS uploads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    department VARCHAR(50) NOT NULL,
    module_type VARCHAR(50),
    module_name VARCHAR(100),
    category VARCHAR(50) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INTEGER,
    approval_status VARCHAR(20) DEFAULT 'pending',
    approved_by INTEGER NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Comments table
CREATE TABLE IF NOT EXISTS comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    upload_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    comment TEXT NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (upload_id) REFERENCES uploads(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Downloads tracking
CREATE TABLE IF NOT EXISTS downloads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    upload_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    downloaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (upload_id) REFERENCES uploads(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Ratings table
CREATE TABLE IF NOT EXISTS ratings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    upload_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    rating INTEGER NOT NULL CHECK(rating >= 1 AND rating <= 5),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (upload_id) REFERENCES uploads(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE(upload_id, user_id)
);

-- Insert essential departments only
INSERT INTO departments (name, code) VALUES 
('IT', 'IT'),
('Business Management', 'BM'),
('Biomedical', 'BIO');

-- Insert essential modules
INSERT INTO modules (name, department_id, module_type) VALUES
-- IT modules
('Web Development', 1, 'Web Application'),
('Mobile Development', 1, 'Mobile Application'),
-- Business modules
('Marketing Management', 2, NULL),
('Business Law', 2, NULL),
('Financial Accounting', 2, NULL),
-- Biomedical modules
('Human Anatomy', 3, NULL),
('Biomedical Instrumentation', 3, NULL),
('Pharmacology', 3, NULL);

-- Insert essential categories
INSERT INTO categories (name, department_id) VALUES
-- IT categories
('Backend', 1),
('Frontend', 1),
('Full Stack', 1),
('Styling (CSS)', 1),
('Native', 1),
('Cross Platform', 1),
-- Business and Biomedical categories
('Lecture Notes', 2),
('Assignment', 2),
('Research Paper', 2),
('Past Paper', 2),
('Lecture Notes', 3),
('Assignment', 3),
('Research Paper', 3),
('Past Paper', 3),
('Lab Report', 3);
