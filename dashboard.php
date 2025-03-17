<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        

        <link rel="stylesheet" href="dashboard.css">
        <link rel="stylesheet" href="global.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


        <title>Admin Dashboard</title>
    </head>
    <body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="logo_lib.png" alt="logo">
                </span>

                <div class="text header-text">
                <span class="name">LibTrack</span>
                <span class="profession">PHINMA</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>

        </header>

        <div class ="menu-bar">
            <div class="menu">
                
            <ul class="menu-links">
                
                <li class="nav-link">
                    <a href="#dashboard">
                        <i class='bx bx-home-alt icon' ></i>
                        <span class="text nav-text">Dashboard</span>
                    </a>
                </li>
                
                
                <li class="nav-link">
                    <a href="#addbook">
                    <i class='bx bx-book icon' ></i>
                        <span class="text nav-text">Book Management</span>
                    </a>
                </li>
                

                <li class="nav-link">
                    <a href="#borrowedbook">
                    <i class='bx bx-book-alt icon' ></i>
                        <span class="text nav-text">Borrowed Book</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#attendance">
                    <i class='bx bx-history icon' ></i>
                        <span class="text nav-text">Attendance</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#users">
                    <i class='bx bx-user icon' ></i>
                        <span class="text nav-text">Students</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#staff">
                    <i class='bx bx-user icon' ></i>
                        <span class="text nav-text">Staff</span>
                    </a>
                </li>


            </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="logout.php">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
                <li class="mode">
                    <div class="moon-sun">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark Mode</span>
                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                    
                </li>
            </div>
        </div>
    </nav>
    
    <section id="dashboard" class="home-addbook">
        <div class="home-content">
            <?php include 'home.php'; ?>
            </div>
    </section>

    <section id="addbook" class="home-addbook">
        <div class="home-content">
            <?php include 'addbook.php'; ?>
        </div>
    </section>

    <section id="borrowedbook" class="home-addbook">
        <div class="home-content">
            <?php include 'borrowedbook.php'; ?>
        </div>
    </section>

    

    <section id="attendance" class="home-addbook">
        <div class="home-content">
            <?php include 'attendance.php'; ?>
        </div>
    </section>
    
    <section id="users" class="home-addbook">
        <div class="home-content">
            <?php include 'users.php'; ?>
        </div>
    </section>

    <section id="staff" class="home-addbook">
        <div class="home-content">
            <?php include 'staff.php'; ?>
        </div>
    </section>


        <script src="script.js"> </script>
    
    </body>
</html>