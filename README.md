# assets-management-system
### This is group 10's submission for CSC434( Web Design and Data Security) project

<p>This is an Assets Management System that allows employees to request office items, tracks asset assignments, and enables efficient approval workflows between the HR department and the manager. 
The system also maintains records of assigned assets for easy retrieval when an employee resigns. </p>

[ER Diagram](https://lucid.app/lucidchart/a3306a6d-dd63-4e10-aa91-82ac93e66541/edit?invitationId=inv_d4963a3c-4eeb-4c3e-a5d1-a6cae4cb8226&page=0_0#)
<br>
[Database Table Design](https://docs.google.com/document/d/14YLVsWOCC5YYNDU1jR4f3EzR9nHA6FsWQ8jM2PKTqWo/edit?usp=sharing)

## Running Locally
[Setting Up the Backend (PHP)]
1. Install XAMPP:
    Download and install XAMPP.
    XAMPP includes Apache (for running PHP) and MySQL.

2. Start XAMPP:
    Launch XAMPP and start the Apache and MySQL services.
    Apache will run on http://localhost:80, and MySQL will run on http://localhost:3306.

3. Create a PHP Backend:
    - Navigate to the XAMPP htdocs folder (usually located at C:\xampp\htdocs on Windows or /opt/lampp/htdocs on Linux/Mac).
    - Create a new folder for your project, e.g., asset-management-system-backend.
    - Inside this folder, create a PHP file, e.g., index.php, to handle API requests.

    Example PHP API:
    Create a simple PHP API to fetch data from MySQL:

    ```php
    <?php
    header("Access-Control-Allow-Origin: *"); // Allow requests from your React app
    header("Content-Type: application/json; charset=UTF-8");

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "my_database";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch data from MySQL
    $sql = "SELECT * FROM my_table";
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

    Download PHP Intelliphense and PHP debug extensions.

4. Setting Up the Database (MySQL)
    Create a Database:
        - Open http://localhost/phpmyadmin in your browser (this is the MySQL admin tool provided by XAMPP).
        - Create a new database, e.g., my_database.
        - Create a table, e.g., my_table, and add some sample data.

    Connect PHP to MySQL:
        - Use the PHP code above to connect to your MySQL database and fetch data.

5. Connecting ReactJS to PHP Backend
    Fetch Data from PHP API in React:
        In your React app, use the fetch API to call your PHP backend:

            ```javascript
            import React, { useEffect, useState } from 'react';

            function App() {
            const [data, setData] = useState([]);

            useEffect(() => {
                fetch('http://localhost/asset-management-system-backend/index.php')
                .then(response => response.json())
                .then(data => setData(data))
                .catch(error => console.error('Error fetching data:', error));
            }, []);

            return (
                <div>
                <h1>Data from PHP Backend</h1>
                <ul>
                    {data.map(item => (
                    <li key={item.id}>{item.name}</li>
                    ))}
                </ul>
                </div>
            );
            }

            export default App;
            ```

6. Handle CORS:
    Ensure your PHP backend allows requests from your React app by setting the Access-Control-Allow-Origin header (as shown in the PHP example above).

7. Using XAMPP in Your Workflow
    Development:

    Use XAMPP to run your PHP backend and MySQL database locally.

    React runs on its own development server (http://localhost:3000 or http://localhost:5173), while PHP runs on http://localhost.

    Production(TBD):
    Deploy your React app (using the build folder) and PHP backend to a live server (e.g., Apache or Nginx).
    Use a live MySQL database (e.g., via a hosting provider like AWS, DigitalOcean, or shared hosting).
