# Asset Management System
#### Submission for CSC434- Web Design and Data Security project, Group 10

<p>This is an Assets Management System that allows employees to request office items, tracks asset assignments, and enables efficient approval workflows between the HR department and the manager. 
The system also maintains records of assigned assets for easy retrieval when an employee resigns. </p>


## Running Locally
### Setting Up the Backend (PHP)
1. Install XAMPP:
    Download and install XAMPP.
    XAMPP includes Apache (for running PHP) and MySQL.

2. Start XAMPP:
    Launch XAMPP and start the Apache and MySQL services.
    Apache will run on http://localhost:80, and MySQL will run on http://localhost:3306.

3. Create a PHP Backend:
    - Navigate to the XAMPP htdocs folder (usually located at C:\xampp\htdocs on Windows or /opt/lampp/htdocs on Linux/Mac).
    - Create a new folder for your project, asset-management-system-backend in our case.
    - Inside this folder, paste the content of the project.

4. Setting Up the Database (MySQL)
    Create a Database:
        - The database is set up with preloaded records once you load the index page.

   Example PHP API:
   Create a simple PHP API to fetch data from MySQL:
    
   ```php
        <?php
        header("Access-Control-Allow-Origin: *"); 
        header("Content-Type: application/json; charset=UTF-8");
    
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "asset_mgt_db";
    
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        // Fetch data from MySQL
        $sql = "SELECT * FROM employees";
        $result = $conn->query($sql);
    
        $data = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
    
        echo json_encode($data);
        $conn->close();
        ?>
   ```

5. Using XAMPP in Your Workflow
    Development:
    - Use XAMPP to run your PHP backend and MySQL database locally.
    - Navigate to http://localhost/assets-management-system/app/index.php.


<br><br>

### ER Diagram
<a href='https://lucid.app/lucidchart/a3306a6d-dd63-4e10-aa91-82ac93e66541/edit?invitationId=inv_d4963a3c-4eeb-4c3e-a5d1-a6cae4cb8226&page=0_0#'>
    <p> Link </p>
</a>
<img width="890" alt="image" src="https://github.com/user-attachments/assets/7bf4f9e1-23df-41ca-adf7-2930a5a9d992" />

<br><br>

### Database Table Design
<a href='https://docs.google.com/document/d/14YLVsWOCC5YYNDU1jR4f3EzR9nHA6FsWQ8jM2PKTqWo/edit?usp=sharing'>
    <p> Link </p>
</a>
<img width="640" alt="image" src="https://github.com/user-attachments/assets/320ab549-1ec7-451a-9679-87126875b9e9" />
