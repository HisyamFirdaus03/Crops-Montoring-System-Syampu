<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Database;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$db = (new Database())->getConnection();

// JWT Configuration
$secretKey = 'your-secret-key'; 
$jwtAlgorithm = 'HS256';

// CORS Middleware
$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Max-Age', '86400');
});

// Global OPTIONS route to handle preflight requests
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Max-Age', '86400')
        ->withStatus(204); // No Content
});

// Middleware for JWT authentication
$jwtMiddleware = function (Request $request, $handler) use ($secretKey, $jwtAlgorithm) {
    $authHeader = $request->getHeaderLine('Authorization');
    error_log("JWT Middleware: Authorization header: " . $authHeader);
    if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        error_log("JWT Middleware: Missing or invalid Authorization header");
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => 'Token required']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    try {
        $token = $matches[1];
        error_log("JWT Middleware: Decoding token: " . $token);
        JWT::decode($token, new Key($secretKey, $jwtAlgorithm));
        error_log("JWT Middleware: Token valid");
        return $handler->handle($request);
    } catch (\Exception $e) {
        error_log("JWT Middleware: Invalid token - " . $e->getMessage());
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => 'Invalid token: ' . $e->getMessage()]));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
};

// POST /api/login
$app->post('/api/login', function (Request $request, Response $response) use ($db, $secretKey, $jwtAlgorithm) {
    $rawBody = $request->getBody()->getContents();
    error_log("Login raw body: " . $rawBody);
    $data = json_decode($rawBody, true);
    error_log("Login parsed data: " . print_r($data, true));
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
        error_log("Login failed: Missing username or password");
        $response->getBody()->write(json_encode(['error' => 'Missing username or password']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("User fetched: " . print_r($user, true));

    if ($user && password_verify($password, $user['password'])) {
        $payload = [
            'sub' => $user['id'],
            'iat' => time(),
            'exp' => time() + 3600 // Token expires in 1 hour
        ];
        $token = JWT::encode($payload, $secretKey, $jwtAlgorithm);
        error_log("Token generated: $token");
        $response->getBody()->write(json_encode(['token' => $token]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    error_log("Login failed: Invalid credentials");
    $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
    return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
});

// POST /api/register
$app->post('/api/register', function (Request $request, Response $response) use ($db) {
    $rawBody = $request->getBody()->getContents();
    error_log("Register raw body: " . $rawBody);
    $data = json_decode($rawBody, true);
    error_log("Register parsed data: " . print_r($data, true));
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
        error_log("Register failed: Missing username or password");
        $response->getBody()->write(json_encode(['error' => 'Username and password required']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    try {
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        error_log("User registered: $username");
        $response->getBody()->write(json_encode(['message' => 'User registered successfully']));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    } catch (PDOException $e) {
        error_log("Register failed: " . $e->getMessage());
        $response->getBody()->write(json_encode(['error' => 'Registration failed: ' . $e->getMessage()]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
});

// POST /api/sensor-data (JWT protected)
$app->post('/api/sensor-data', function (Request $request, Response $response) use ($db) {
    $rawBody = $request->getBody()->getContents();
    error_log("Sensor data raw body: " . $rawBody);
    $data = json_decode($rawBody, true);
    error_log("Sensor data parsed: " . print_r($data, true));

    if (!is_array($data) || empty($data)) {
        error_log("Sensor data failed: Invalid or empty payload");
        $response->getBody()->write(json_encode(['error' => 'Invalid or empty payload']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    if (!isset($data['temperature'], $data['soil_moisture'], $data['humidity'], $data['light_intensity'], $data['co2_level'])) {
        error_log("Sensor data failed: Missing required fields");
        $response->getBody()->write(json_encode(['error' => 'Missing required fields']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    try {
        $stmt = $db->prepare("INSERT INTO sensor_data (temperature, soil_moisture, humidity, light_intensity, co2_level) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['temperature'],
            $data['soil_moisture'],
            $data['humidity'],
            $data['light_intensity'],
            $data['co2_level']
        ]);

        // Check for alerts (e.g., temperature > 30°C)
        if ($data['temperature'] > 30) {
            $stmt = $db->prepare("INSERT INTO alerts (type, message, threshold_value, actual_value) VALUES (?, ?, ?, ?)");
            $stmt->execute(['temperature_spike', 'High temperature detected', 30, $data['temperature']]);
        }

        error_log("Sensor data saved successfully");
        $response->getBody()->write(json_encode(['message' => 'Data saved successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (PDOException $e) {
        error_log("Sensor data failed: Database error - " . $e->getMessage());
        $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }
})->add($jwtMiddleware);

// GET /api/sensor-data/historical
$app->get('/api/sensor-data/historical', function (Request $request, Response $response) use ($db) {
    $stmt = $db->query("SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 50");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

// GET /api/sensor-data/latest
$app->get('/api/sensor-data/latest', function (Request $request, Response $response) use ($db) {
    $stmt = $db->query("SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($data ?: []));
    return $response->withHeader('Content-Type', 'application/json');
});

// GET /api/alerts (JWT protected)
$app->get('/api/alerts', function (Request $request, Response $response) use ($db) {
    $stmt = $db->query("SELECT * FROM alerts WHERE is_active = 1 ORDER BY timestamp DESC");
    $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($alerts));
    return $response->withHeader('Content-Type', 'application/json');
})->add($jwtMiddleware);

// POST /api/control-command
$app->post('/api/control-command', function (Request $request, Response $response) use ($db) {
    $rawBody = $request->getBody()->getContents();
    error_log("Control command raw body: " . $rawBody);
    $data = json_decode($rawBody, true);
    error_log("Control command parsed: " . print_r($data, true));

    if (!is_array($data) || empty($data)) {
        error_log("Control command failed: Invalid or empty payload");
        $response->getBody()->write(json_encode(['error' => 'Invalid or empty payload']));
        return $response
            ->withStatus(400)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5173');
    }

    if (!isset($data['actuator_type']) || !isset($data['command'])) {
        error_log("Control command failed: Missing actuator_type or command");
        $response->getBody()->write(json_encode(['error' => 'Missing actuator_type or command']));
        return $response
            ->withStatus(400)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5173');
    }

    try {
        $stmt = $db->prepare("SELECT id FROM actuator_commands WHERE actuator_type = ?");
        $stmt->execute([$data['actuator_type']]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $stmt = $db->prepare("UPDATE actuator_commands SET command = ?, timestamp = CURRENT_TIMESTAMP WHERE actuator_type = ?");
            $stmt->execute([$data['command'], $data['actuator_type']]);
            error_log("Control command updated: {$data['actuator_type']} - {$data['command']}");
            $response->getBody()->write(json_encode(['message' => 'Command updated successfully']));
        } else {
            $stmt = $db->prepare("INSERT INTO actuator_commands (actuator_type, command) VALUES (?, ?)");
            $stmt->execute([$data['actuator_type'], $data['command']]);
            error_log("Control command saved: {$data['actuator_type']} - {$data['command']}");
            $response->getBody()->write(json_encode(['message' => 'Command saved successfully']));
        }

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5173');
    } catch (PDOException $e) {
        error_log("Control command failed: Database error - " . $e->getMessage());
        $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5173');
    }
})->add($jwtMiddleware);

// GET /api/actuator-status (JWT protected)
$app->get('/api/actuator-status', function (Request $request, Response $response) use ($db) {
    try {
        // Fetch latest fan command
        $fanStmt = $db->prepare("SELECT command FROM actuator_commands WHERE actuator_type = ?");
        $fanStmt->execute(['fan']);
        $fanCommand = $fanStmt->fetch(PDO::FETCH_ASSOC);
        $fanStatus = $fanCommand ? ($fanCommand['command'] === 'on' ? 'ON' : 'OFF') : 'OFF';

        // Fetch latest irrigation command
        $irrigationStmt = $db->prepare("SELECT command FROM actuator_commands WHERE actuator_type = ?");
        $irrigationStmt->execute(['irrigation']);
        $irrigationCommand = $irrigationStmt->fetch(PDO::FETCH_ASSOC);
        $irrigationStatus = $irrigationCommand ? ($irrigationCommand['command'] === 'on' ? 'ON' : 'OFF') : 'OFF';

        $status = [
            'fan' => $fanStatus,
            'irrigation' => $irrigationStatus
        ];

        error_log("Actuator status: " . print_r($status, true));
        $response->getBody()->write(json_encode($status));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (PDOException $e) {
        error_log("Actuator status failed: Database error - " . $e->getMessage());
        $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }
})->add($jwtMiddleware);

$app->run();