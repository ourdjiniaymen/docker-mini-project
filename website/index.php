<?php
// Suppress deprecated warnings
error_reporting(E_ALL & ~E_DEPRECATED);

$api_url  = getenv('API_URL');
$username = getenv('USERNAME');
$password = getenv('PASSWORD');

$students = [];
$error    = null;

if (isset($_POST['load'])) {
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code === 200) {
        $data     = json_decode($response, true);
        $students = $data['students'];
    } else {
        $error = "API returned HTTP $http_code";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POZOS - Student List</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h1   { color: #2c3e50; }
        button { padding: 10px 20px; background: #2c3e50; color: white; border: none; cursor: pointer; font-size: 15px; border-radius: 4px; margin-bottom: 20px; }
        button:hover { background: #3d5166; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 16px; border: 1px solid #ccc; text-align: left; }
        th   { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>POZOS - Student List</h1>

    <form method="POST">
        <button type="submit" name="load">List Students</button>
    </form>

    <?php if ($error): ?>
        <p class="error">Error: <?= htmlspecialchars($error) ?></p>
    <?php elseif (!empty($students)): ?>
        <table>
            <tr><th>Name</th><th>Age</th></tr>
            <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['name']) ?></td>
                    <td><?= htmlspecialchars($s['age']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>
</html>