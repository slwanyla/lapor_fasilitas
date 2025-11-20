<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/user.css">
       
    <title>User Dashboard</title>
</head>

<body>
    
<header class="header">

    <div class="left-area">
        <div class="toggle-btn" onclick="toggleSidebar()">
            <i class="fi fi-br-align-justify"></i>
        </div>

        <div class="user-profile">
            <img src="../pfp/icon.jpg">
            <div class="user-text">
                <strong>Ahmad Dhani</strong><br>
                <small>284985699995</small>
            </div>
        </div>
    </div>

    <h2 class="header-title">Lapor Kampus</h2>

    <div class="header-right">
        <div class="notif-icon">
            <i class="fi fi-rr-bell"></i>
        </div>

        <div class="search-box">
            <input type="text" placeholder="Search...">
            <button><i class="fi fi-rr-search"></i></button>
        </div>
    </div>
</header>

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("closed");
}
</script>



