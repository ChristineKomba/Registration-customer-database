<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Country Materials Limited</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* BODY */
        body {
            font-family: Arial, sans-serif;
            background: #e8ebf0; /* light neutral gray */
            margin: 0;
            padding: 0;
        }

        /* HEADER */
        .header {
            text-align: center;
            padding: 40px;
            background: #ffffff; /* logo pops */
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .header img {
            height: 80px;
        }

        /* DASHBOARD CONTAINER */
        .dashboard {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
            padding: 50px;
        }

        /* CARDS */
        .card {
            width: 250px;
            height: 200px;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card i {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 14px;
            margin-top: 5px;
            color: #ffffff;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        /* REGISTER CARD - GOLD */
        .register {
            background: linear-gradient(to right, #FFD700, #FFC107);
        }
        .register:hover {
            box-shadow: 0 12px 30px rgba(255, 215, 0, 0.4);
        }

        /* VIEW ALL CARD - GREEN from logo */
        .view-all {
            background: linear-gradient(to right, #4CAF50, #43A047); /* green exact from logo */
        }
        .view-all:hover {
            box-shadow: 0 12px 30px rgba(76, 175, 80, 0.4);
        }
    </style>
</head>
<body>

    <!-- HEADER WITH LOGO -->
    <div class="header">
        <img src="https://countrymaterial.com/itheeglu/2024/02/Country-Materials-Logo.png" alt="Country Materials Logo">
    </div>

    <!-- DASHBOARD CARDS -->
    <div class="dashboard">
        <a href="index.html" class="card register">
            <i class="fa-solid fa-user-plus"></i>
            Register
            <p>Add a new user</p>
        </a>
        <a href="admin_view.php" class="card view-all">
            <i class="fa-solid fa-users"></i>
            View All
            <p>See registered users</p>
        </a>
    </div>

</body>
</html>
