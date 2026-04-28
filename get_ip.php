<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your IP Address</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 40px 50px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .card h1 {
            font-size: 1.4rem;
            font-weight: 400;
            margin-bottom: 16px;
            color: rgba(255, 255, 255, 0.7);
        }
        .ip-address {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #a78bfa, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 24px;
        }
        .details {
            text-align: left;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.8;
        }
        .details span {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Your IP Address</h1>

        <?php
        /**
         * Get the real IP address of the user.
         *
         * Checks multiple headers to handle users behind
         * proxies, load balancers, or CDNs (e.g. Cloudflare).
         *
         * @return string The detected IP address.
         */
        function getUserIP(): string
        {
            // Headers to check, ordered by priority
            $headers = [
                'HTTP_CF_CONNECTING_IP',   // Cloudflare
                'HTTP_X_FORWARDED_FOR',    // Common proxy header
                'HTTP_X_REAL_IP',          // Nginx proxy
                'HTTP_CLIENT_IP',          // Shared internet
                'REMOTE_ADDR',             // Direct connection (fallback)
            ];

            foreach ($headers as $header) {
                if (!empty($_SERVER[$header])) {
                    // X-Forwarded-For may contain a comma-separated list;
                    // the first IP is the original client.
                    $ip = trim(explode(',', $_SERVER[$header])[0]);

                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            }

            return 'Unknown';
        }

        $userIP    = getUserIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $method    = $_SERVER['REQUEST_METHOD']   ?? 'Unknown';
        $timestamp = date('Y-m-d H:i:s T');
        ?>

        <div class="ip-address"><?= htmlspecialchars($userIP) ?></div>

        <div class="details">
            <strong>Request Details</strong><br>
            Method: <span><?= htmlspecialchars($method) ?></span><br>
            Time: <span><?= htmlspecialchars($timestamp) ?></span><br>
            User-Agent: <span><?= htmlspecialchars($userAgent) ?></span>
        
        </div>
    </div>
</body>
</html>
