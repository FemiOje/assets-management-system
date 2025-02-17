CREATE TABLE employees (
        employee_id INT NOT NULL AUTO_INCREMENT ,
        first_name TEXT NOT NULL , 
        last_name TEXT NOT NULL , 
        email TEXT NOT NULL UNIQUE, 
        password_hash TEXT NOT NULL , 
        department TEXT NULL , 
        position TEXT NOT NULL , 
        role ENUM('EMPLOYEE', 'HR', 'MANAGER') NOT NULL DEFAULT 'EMPLOYEE', 
        date_joined DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
        status ENUM('ACTIVE', 'RESIGNED', 'INACTIVE') NOT NULL DEFAULT 'ACTIVE', 
        PRIMARY KEY (employee_id)
    ) ENGINE = InnoDB;

-- Assets Table
CREATE TABLE assets (
    asset_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    total_quantity INT NOT NULL,
    available_quantity INT NOT NULL,
    category VARCHAR(50)
);

-- Requests Table
CREATE TABLE requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    asset_id INT NOT NULL,
    quantity INT NOT NULL,
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('submitted', 'pending_approval', 'approved', 'declined') DEFAULT 'submitted',
    hr_action BOOLEAN DEFAULT FALSE,
    manager_action BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(asset_id) ON DELETE CASCADE
);

-- Assignments Table
CREATE TABLE assignments (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    asset_id INT NOT NULL,
    assigned_date DATE NOT NULL,
    returned_date DATE NULL,
    request_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(asset_id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE
);

INSERT INTO employees (employee_id, first_name, last_name, email, password_hash, department, position, role, date_joined, status) VALUES (101, 'Michael', 'Scott', 'blindguymcsqueezy@yahoo.com', '********', NULL, 'MANAGER', 'MANAGER', '2006-05-09', 'ACTIVE');
