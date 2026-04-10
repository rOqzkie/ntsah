<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="uploads/nah-logo.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <title>NEMSU Archiving Hub</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      text-align: center;
      background: url('uploads/bg-ntfs.jpg') no-repeat center center fixed;
      background-size: cover;
      background-color: #f0f2f5;
      padding: 10px;
    }

    nav {
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #17445abf;
      backdrop-filter: blur(10px);
      padding: 15px;
      min-height: 80px;
      color: white;
    }

    nav i {
      margin-right: 10px;
    }

    nav div {
      font-size: 28px;
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      text-align: center;
    }

    .header {
      background-color: #116A75;
      color: white;
      padding: 15px 10px;
      font-size: 20px;
      font-weight: bold;
      margin-top: 10px;
      position: relative;
    }

    .header::after {
      content: "";
      display: block;
      width: 100%;
      height: 5px;
      background-color: #ffb800;
    }

    .container {
      margin-top: 30px;
      padding: 0 15px;
    }

    .logo {
      width: 100px;
      height: 100px;
      margin: 0 auto 20px auto;
      border-radius: 50%;
      overflow: hidden;
    }

    .logo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .title {
      font-size: 28px;
      font-weight: bold;
      color: #ff9900;
    }

    .subtitle {
      font-style: italic;
      color: #ffffff;
      margin: 10px 0 30px;
      font-size: 16px;
    }

    .login-text {
      color: #ffffff;
      margin-bottom: 15px;
    }

    .login-text a {
      font-weight: bold;
      text-decoration: none;
      color: #ffffff;
    }

    .btn-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
    }

    .btn-container a {
      text-decoration: none;
    }

    .btn {
      padding: 12px 24px;
      font-size: 16px;
      font-weight: bold;
      border: 2px solid #0026ff;
      border-radius: 8px;
      background: white;
      color: black;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: 0.3s ease-in-out;
    }

    .btn:hover {
      background-color: #116A75;
      color: white;
    }

    /* ---------- Responsive Design ---------- */

    @media (max-width: 768px) {
      nav div {
        font-size: 24px;
        flex-direction: column;
        text-align: center;
      }

      .header {
        font-size: 18px;
        padding: 12px 8px;
      }

      .title {
        font-size: 24px;
      }

      .subtitle {
        font-size: 14px;
      }

      .btn {
        font-size: 14px;
        padding: 10px 20px;
      }
    }

    @media (max-width: 480px) {
      .btn-container {
        flex-direction: column;
        gap: 10px;
      }

      .logo {
        width: 80px;
        height: 80px;
      }

      .title {
        font-size: 20px;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <nav>
    <div><i class="fa fa-archive"></i> NEMSU Archiving Hub</div>
  </nav>

  <div class="header">Empowering Research, Preserving Knowledge</div>

  <div class="container">
    <div class="logo">
      <img src="uploads/nah-logo.png" alt="Logo" />
    </div>
    <div class="title">NEMSU-AH</div>
    <div class="subtitle">Discover Groundbreaking Theses & Feasibility Studies</div>
    <div class="login-text">Have an Account? <a href="#">Login as</a></div>
    <div class="btn-container">
      <a href="login.php"><button class="btn">👤 Student</button></a>
      <a href="login-adviser.php"><button class="btn">👨‍🏫 Adviser</button></a>
    </div>
  </div>
</body>
</html>