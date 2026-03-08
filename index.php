<?php
require 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Benny's Original Motor Service</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    width: 100%;
    height: auto;
    overflow-x: hidden;
    font-family: Arial, Helvetica, sans-serif;
}

/* ===== BACKGROUND IMAGE ===== */
.bg-wrapper {
    position: relative;
    width: 100%;
}

.bg-wrapper img {
    width: 100%;
    height: auto;
    display: block;
}

/* Dark overlay */
.bg-wrapper::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.25);
    pointer-events: none;
}

/* ===== UI LAYER ===== */
.ui-layer {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

/* ===== USER BAR (TOP RIGHT CORNER) ===== */
.user-bar {
    position: absolute;
    top: 20px;
    right: 40px;
    pointer-events: auto;
    text-align: right;
}

.user-role {
    color: #ffffff;
    font-size: 13px;
    margin-bottom: 8px;
    letter-spacing: 0.5px;
    background: rgba(0,0,0,0.55);
    padding: 8px 12px;
    border-radius: 6px;
    display: inline-block;
}

.user-role b {
    color: #ffd700;
}

.user-bar a {
    display: inline-block;
    color: #ffd700;
    padding: 6px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 700;
    letter-spacing: 1px;
    border: 1px solid #333;
    background: rgba(0,0,0,0.55);
    transition: all 0.2s ease;
}

.user-bar a:hover {
    border-color: #ffd700;
}

/* ---------- TOP NAV ---------- */
.top-nav {
    position: sticky;
    top: 150px;
    display: flex;
    justify-content: space-between;
    padding: 0 430px;
    pointer-events: auto;
}

.view-btn {
    position: relative;
    right: 70px;
}

.top-nav span {
    color: #ffffff;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 2px;
    cursor: pointer;
    text-transform: uppercase;
}

.top-nav span:hover {
    color: #ffd700;
}

/* ---------- CENTER WRAPPER ---------- */
.center-wrapper {
    position: absolute;
    bottom: 40px;
    left: 52%;
    transform: translateX(-50%);
    text-align: center;
    pointer-events: auto;
}

/* ---------- CENTER LOGO ---------- */
.center-image {
    width: 450px;
    height: 200px;
    background: url("images/center.jpg") center / contain no-repeat;
    cursor: pointer;
}

/* ---------- TEXT UNDER CENTER IMAGE ---------- */
.center-text {
    margin-top: 8px;
}

.center-title {
    color: #ffffff;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 2px;
}

.center-desc {
    color: rgba(255,255,255,0.75);
    font-size: 13px;
    margin-top: 4px;
}

/* ---------- OPTIONS OVERLAY ---------- */
.options-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 100;
}

.options-box {
    width: 900px;
    padding: 30px;
    background: rgba(0,0,0,0.8);
}

.close-btn {
    color: #ffffff;
    text-align: right;
    margin-bottom: 20px;
    cursor: pointer;
    letter-spacing: 2px;
    font-weight: 600;
}

.options-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.option-item {
    height: 250px;
    background-color: #000;
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.option-item:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 30px rgba(0,0,0,0.6);
}
</style>
</head>

<body>

<div class="bg-wrapper">
    <img src="images/bg.jpg" alt="Background">

    <div class="ui-layer">

        <!-- USER INFO -->
        <div class="user-bar">
            <div class="user-role">
                Logged in as: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>
                (<?php echo htmlspecialchars($_SESSION["role"]); ?>)
            </div><br>
            <a href="logout.php">Logout</a>
        </div>

        <!-- TOP NAV -->
        <div class="top-nav">
            <span onclick="openOptions()">OPTIONS</span>
            <span class="view-btn" onclick="goToView()">VIEW</span>
        </div>

        <!-- CENTER IMAGE + TEXT -->
        <div class="center-wrapper">
            <div class="center-image" onclick="openOptions()"></div>

            <div class="center-text">
                <div class="center-title">OPTIONS</div>
                <div class="center-desc">
                    Manage customers, vehicles and services
                </div>
            </div>
        </div>

    </div>
</div>

<!-- OPTIONS POPUP -->
<div class="options-overlay" id="optionsOverlay">
    <div class="options-box">
        <div class="close-btn" onclick="closeOptions()">CLOSE ✕</div>

        <div class="options-grid">
            <div class="option-item" style="background-image:url('images/opt1.jpg')"
                 onclick="location.href='add_customer.php'"></div>

            <div class="option-item" style="background-image:url('images/opt2.jpg')"
                 onclick="location.href='add_vehicle.php'"></div>

            <div class="option-item" style="background-image:url('images/opt3.jpg')"
                 onclick="location.href='add_service.php'"></div>

            <div class="option-item" style="background-image:url('images/opt4.jpg')"
                 onclick="location.href='add_service_record.php'"></div>

            <div class="option-item" style="background-image:url('images/opt5.jpg')"
                 onclick="location.href='view_records.php'"></div>
        </div>
    </div>
</div>

<script>
function openOptions() {
    document.getElementById("optionsOverlay").style.display = "flex";
}
function closeOptions() {
    document.getElementById("optionsOverlay").style.display = "none";
}
function goToView() {
    window.location.href = "view_records.php";
}
</script>

</body>
</html>