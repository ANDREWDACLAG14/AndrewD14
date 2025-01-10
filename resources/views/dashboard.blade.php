<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .sidebar h4 {
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .user-panel {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #495057;
            border-radius: 8px;
        }

        .user-panel img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .user-panel .info p {
            margin: 0;
            font-weight: bold;
        }

        .user-panel .info small {
            color: #00ff00;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            padding: 10px 15px;
            margin-bottom: 8px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar a.active {
            background-color: #007bff;
        }

        .logout-btn {
            background-color: #e50914;
            color: white;
            padding: 10px;
            border: none;
            text-align: center;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #f6121d;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <h4>RV Milktea & Coffee Shop</h4>

            <div class="user-panel">
                <img src="https://via.placeholder.com/50" alt="User Image">
                <div class="info">
                    <p>{{ Auth::user()->username }}</p>
                    <small>Online</small>
                </div>
            </div>

            <div>
                <a href="/profile" class="{{ request()->is('profile') ? 'active' : '' }}">
                    <i class="bi bi-person"></i> Profile
                </a>
                <a href="/sales" class="{{ request()->is('sales') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i> Track Sales
                </a>
                <a href="/orders" class="{{ request()->is('orders') ? 'active' : '' }}">
                    <i class="bi bi-cart"></i> Receive Orders
                </a>
                <a href="/inventory" class="{{ request()->is('inventory') ? 'active' : '' }}">
                    <i class="bi bi-boxes"></i> Inventory
                </a>
            </div>

        </div>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-left"></i> Logout</button>
        </form>
    </div>

    <div class="main-content">
        <h1>Welcome to the Dashboard</h1>
        <p>Manage your shop efficiently using the menu on the left.</p>
    </div>
</body>
</html>
