<?php
session_start();
$PASSWORD = 'Rawgee97!'; // CHANGE THIS after deployment
$csvFile = __DIR__ . '/applications.csv';

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if (isset($_POST['password'])) {
    if ($_POST['password'] === $PASSWORD) {
        $_SESSION['admin'] = true;
    } else {
        $error = "Incorrect password";
    }
}

if (!empty($_SESSION['admin'])) {
    // show dashboard
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Admin - Applications</title><link href="css/bootstrap.min.css" rel="stylesheet"></head><body class="p-4">';
    echo '<div class="container"><h2>Applications</h2><form method="post" style="display:inline"><button name="logout" class="btn btn-secondary btn-sm">Logout</button></form><a href="applications.csv" class="btn btn-success btn-sm ms-2">Download CSV</a><hr>';
    if (file_exists($csvFile)) {
        echo '<table class="table table-striped"><thead><tr>';
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            $first = true;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($first) {
                    echo '<tr>';
                    foreach ($data as $h) echo '<th>'.htmlspecialchars($h).'</th>';
                    echo '</tr></thead><tbody>';
                    $first = false;
                } else {
                    echo '<tr>';
                    foreach ($data as $c) echo '<td>'.htmlspecialchars($c).'</td>';
                    echo '</tr>';
                }
            }
            echo '</tbody></table>';
            fclose($handle);
        }
    } else {
        echo '<p>No applications yet.</p>';
    }
    echo '</div></body></html>';
    exit;
} else {
    // show login form
    ?>
    <!doctype html><html><head><meta charset="utf-8"><title>Admin Login</title><link href="css/bootstrap.min.css" rel="stylesheet"></head><body class="p-4"><div class="container"><h3>Admin Login</h3><?php if(!empty($error)) echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; ?><form method="post" style="max-width:420px"><div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div><button class="btn btn-primary">Login</button></form></div></body></html><?php
}
?>