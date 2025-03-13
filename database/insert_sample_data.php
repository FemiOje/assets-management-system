<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create database if not exists
$temp_conn = mysqli_connect("localhost", "root", "");
if (!$temp_conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$create_db = "CREATE DATABASE IF NOT EXISTS asset_mgt_db";
if (!mysqli_query($temp_conn, $create_db)) {
    die("Error creating database: " . mysqli_error($temp_conn));
}

mysqli_close($temp_conn);

// Connect to the database
include __DIR__ . "/../app/includes/db.php";

function executeQuery($conn, $query)
{
    if (!mysqli_query($conn, $query)) {
        die("Critical error: " . mysqli_error($conn)); // Changed to die() for critical errors
    }
}

// ================= TABLE MANAGEMENT =================
// Disable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// Drop tables in PROPER reverse dependency order
$tables = ['assignments', 'requests', 'assets', 'employees'];
foreach ($tables as $table) {
    $query = "DROP TABLE IF EXISTS $table";
    executeQuery($conn, $query);
}

// Enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

// ================= TABLE CREATION =================
$employees_table = "CREATE TABLE IF NOT EXISTS employees (
    employee_id INT NOT NULL AUTO_INCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    department TEXT NULL,
    position TEXT NOT NULL,
    role ENUM('EMPLOYEE', 'HR', 'MANAGER') NOT NULL DEFAULT 'EMPLOYEE',
    date_joined DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ACTIVE', 'RESIGNED', 'INACTIVE') NOT NULL DEFAULT 'ACTIVE',
    PRIMARY KEY (employee_id)
) ENGINE = InnoDB AUTO_INCREMENT = 101";

$assets_table = "CREATE TABLE IF NOT EXISTS assets (
    asset_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    total_quantity INT NOT NULL,
    available_quantity INT NOT NULL,
    category VARCHAR(50)
) AUTO_INCREMENT = 301";

$requests_table = "CREATE TABLE IF NOT EXISTS requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    asset_id INT NOT NULL,
    quantity INT NOT NULL,
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('SUBMITTED', 'PENDING_APPROVAL', 'APPROVED', 'DECLINED') DEFAULT 'SUBMITTED',
    hr_action BOOLEAN DEFAULT FALSE,
    manager_action BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(asset_id) ON DELETE CASCADE
) AUTO_INCREMENT = 201";

$assignments_table = "CREATE TABLE IF NOT EXISTS assignments (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    asset_id INT NOT NULL,
    assigned_date DATE NOT NULL,
    returned_date DATE NULL,
    request_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(asset_id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE
) AUTO_INCREMENT = 401";

// Execute table creation
$tables = [
    'employees' => $employees_table,
    'assets' => $assets_table,
    'requests' => $requests_table,
    'assignments' => $assignments_table
];

foreach ($tables as $name => $query) {
    executeQuery($conn, $query);
}

$check_unique_query = "SELECT COUNT(*) as constraint_exists
                       FROM information_schema.TABLE_CONSTRAINTS
                       WHERE TABLE_SCHEMA = DATABASE()
                         AND TABLE_NAME = 'employees'
                         AND CONSTRAINT_NAME = 'email_unique'";
$result = mysqli_query($conn, $check_unique_query);
$row = mysqli_fetch_assoc($result);

if ($row['constraint_exists'] == 0) {
    $unique_email = "ALTER TABLE employees ADD UNIQUE INDEX email_unique (email(255))";
    executeQuery($conn, $unique_email);
}

// ================= DATA INSERTION =================
$employees = [
    [
        'first_name' => 'Michael',
        'last_name' => 'Scott',
        'email' => 'blindguymcsqueezy@yahoo.com',
        'password' => 'blindguymcsqueezy',
        'department' => NULL,
        'position' => 'REGIONAL MANAGER',
        'role' => 'MANAGER',
        'date_joined' => '2006-05-09',
        'status' => 'ACTIVE'
    ],
    [
        'first_name' => 'Dwight',
        'last_name' => 'Schrute',
        'email' => 'dschrute@aol.com',
        'password' => 'dschrute',
        'department' => NULL,
        'position' => 'ASSISTANT TO THE REGIONAL MANAGER',
        'role' => 'MANAGER',
        'date_joined' => '2006-05-09',
        'status' => 'ACTIVE'
    ],
    [
        'first_name' => 'Toby',
        'last_name' => 'Flenderson',
        'email' => 'maybescrantonstrangler1@gmail.com',
        'password' => 'maybescrantonstrangler1',
        'department' => 'HR',
        'position' => 'HR MANAGER',
        'role' => 'HR',
        'date_joined' => '2020-03-10',
        'status' => 'ACTIVE'
    ],
    [
        'first_name' => 'Creed',
        'last_name' => 'Bratton',
        'email' => 'maybescrantonstrangler2@gmail.com',
        'password' => 'maybescrantonstrangler2',
        'department' => 'QUALITY CONTROL',
        'position' => 'QUALITY CONTROL OFFICER',
        'role' => 'EMPLOYEE',
        'date_joined' => '2007-07-07',
        'status' => 'RESIGNED'
    ],
    [
        'first_name' => 'Pam',
        'last_name' => 'Beesly',
        'email' => 'beesly@gmail.com',
        'password' => 'beesly',
        'department' => 'SALES',
        'position' => 'SALES REPRESENTATIVE',
        'role' => 'EMPLOYEE',
        'date_joined' => '2006-01-30',
        'status' => 'ACTIVE'
    ],
    [
        'first_name' => 'Ryan',
        'last_name' => 'Howard',
        'email' => 'ryanhoward@gmail.com',
        'password' => 'ryanhoward',
        'department' => 'ACCOUNTING',
        'position' => 'INTERN',
        'role' => 'EMPLOYEE',
        'date_joined' => '2017-05-20',
        'status' => 'ACTIVE'
    ]
];

$query_add_employees = "INSERT INTO employees (first_name, last_name, email, password_hash, department, position, role, date_joined, status) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt_add_employees = mysqli_prepare($conn, $query_add_employees);

foreach ($employees as $employee) {
    $hashed_password = password_hash($employee['password'], PASSWORD_DEFAULT);

    mysqli_stmt_bind_param(
        $stmt_add_employees,
        "sssssssss",
        $employee['first_name'],
        $employee['last_name'],
        $employee['email'],
        $hashed_password,
        $employee['department'],
        $employee['position'],
        $employee['role'],
        $employee['date_joined'],
        $employee['status']
    );

    if (!mysqli_stmt_execute($stmt_add_employees)) {
        echo "Error adding employee: " . mysqli_stmt_error($stmt_add_employees) . "<br>";
    }
}

// Insert Assets
$assets = [
    [
        'name' => 'Office Table',
        'description' => 'Mahogany Table',
        'total_quantity' => 50,
        'available_quantity' => 50,
        'category' => 'Furniture'
    ],
    [
        'name' => 'Pencil',
        'description' => 'HB Pencil',
        'total_quantity' => 3000,
        'available_quantity' => 3000,
        'category' => 'Stationery'
    ],
    [
        'name' => 'Work Computer',
        'description' => 'Work Computer',
        'total_quantity' => 10,
        'available_quantity' => 10,
        'category' => 'Computer'
    ],
];

$query_add_assets = "INSERT INTO assets (name, description, total_quantity, available_quantity, category) 
          VALUES (?, ?, ?, ?, ?)";

$stmt_add_assets = mysqli_prepare($conn, $query_add_assets);

foreach ($assets as $asset) {
    mysqli_stmt_bind_param(
        $stmt_add_assets,
        "ssiis",
        $asset['name'],
        $asset['description'],
        $asset['total_quantity'],
        $asset['available_quantity'],
        $asset['category']
    );

    if (!mysqli_stmt_execute($stmt_add_assets)) {
        echo "Error adding asset: " . mysqli_stmt_error($stmt_add_assets) . "<br>";
    }
}

// Insert Requests
$requests = [
    [
        'employee_id' => 101,
        'asset_id' => 301,
        'quantity' => 1,
        'request_date' => '2024-05-25',
        'status' => 'SUBMITTED',
        'hr_action' => false,
        'manager_action' => false
    ],
    [
        'employee_id' => 102,
        'asset_id' => 301,
        'quantity' => 1,
        'request_date' => '2024-05-27',
        'status' => 'PENDING_APPROVAL',
        'hr_action' => true,
        'manager_action' => false
    ],
    [
        'employee_id' => 105,
        'asset_id' => 302,
        'quantity' => 10,
        'request_date' => '2024-06-06',
        'status' => 'APPROVED',
        'hr_action' => true,
        'manager_action' => true
    ],
    [
        'employee_id' => 106,
        'asset_id' => 301,
        'quantity' => 1,
        'request_date' => '2024-08-10',
        'status' => 'DECLINED',
        'hr_action' => true,
        'manager_action' => true
    ],
    [
        'employee_id' => 103,
        'asset_id' => 303,
        'quantity' => 1,
        'request_date' => '2024-08-13',
        'status' => 'APPROVED',
        'hr_action' => true,
        'manager_action' => true
    ],
];

$query_add_requests = "INSERT INTO requests (employee_id, asset_id, quantity, request_date, status, hr_action, manager_action) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt_add_requests = mysqli_prepare($conn, $query_add_requests);

foreach ($requests as $request) {
    mysqli_stmt_bind_param(
        $stmt_add_requests,
        "iiisiii",
        $request['employee_id'],
        $request['asset_id'],
        $request['quantity'],
        $request['request_date'],
        $request['status'],
        $request['hr_action'],
        $request['manager_action']
    );

    if (!mysqli_stmt_execute($stmt_add_requests)) {
        echo "Error adding request: " . mysqli_stmt_error($stmt_add_requests) . "<br>";
    }
}

// Insert Assignments
$assignments = [
    [
        'employee_id' => 105,
        'asset_id' => 302,
        'assigned_date' => '2024-06-07',
        'returned_date' => NULL,
        'request_id' => 203
    ],
    [
        'employee_id' => 103,
        'asset_id' => 303,
        'assigned_date' => '2024-08-13',
        'returned_date' => NULL,
        'request_id' => 205
    ]
];

$query_add_assignments = "INSERT INTO assignments (employee_id, asset_id, assigned_date, returned_date, request_id) 
          VALUES (?, ?, ?, ?, ?)";

$stmt_add_assignments = mysqli_prepare($conn, $query_add_assignments);

foreach ($assignments as $assignment) {
    mysqli_stmt_bind_param(
        $stmt_add_assignments,
        "iissi",
        $assignment['employee_id'],
        $assignment['asset_id'],
        $assignment['assigned_date'],
        $assignment['returned_date'],
        $assignment['request_id']
    );

    if (!mysqli_stmt_execute($stmt_add_assignments)) {
        echo "Error adding assignment: " . mysqli_stmt_error($stmt_add_assignments) . "<br>";
    }
}

// ================= CLEANUP =================
mysqli_stmt_close($stmt_add_employees);
mysqli_stmt_close($stmt_add_assets);
mysqli_stmt_close($stmt_add_requests);
mysqli_stmt_close($stmt_add_assignments);
mysqli_close($conn);
