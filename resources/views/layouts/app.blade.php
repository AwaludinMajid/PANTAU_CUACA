<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANTAU CUACA - Pantau Cuaca Indonesia Real-time</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e3c72;
            --secondary: #2a5298;
            --success: #17a2b8;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .navbar {
            background: rgba(30, 60, 114, 0.95) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .container-main {
            max-width: 1200px;
            margin-top: 30px;
        }

        .header-section {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 10px;
        }

        .header-section p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        .weather-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 5px solid var(--secondary);
        }

        .weather-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .weather-card .city-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .weather-card .province-name {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .weather-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .weather-item {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .weather-item .label {
            font-size: 0.85rem;
            color: #6c757d;
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .weather-item .value {
            font-size: 1.5rem;
            color: var(--secondary);
            font-weight: 700;
        }

        .condition-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--info) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .row-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .footer-section {
            text-align: center;
            color: white;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .update-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 15px;
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .api-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 1.8rem;
            }

            .row-cards {
                grid-template-columns: 1fr;
            }

            .weather-info {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-cloud-sun"></i> PANTAU CUACA
            </a>
            <span class="navbar-text text-white">Monitor Cuaca Indonesia Real-time</span>
        </div>
    </nav>

    <div class="container container-main">
        @yield('content')
    </div>

    <footer class="footer-section">
        <p>&copy; 2026 PANTAU CUACA - Monitoring Cuaca Indonesia. Data real-time dari Weather API.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
