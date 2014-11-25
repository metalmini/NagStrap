<?php
require_once 'vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();
$res = $client->get('http://testurl/nagios/statusJson.php', ['auth' => ['nagiosadmin', 'nagiospassword']]);

$arr = $res->json();
$services = $arr['services'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php
    $yellow = '0';
    $red = '0';
    foreach ($services as $service) {
        foreach ($service as $output) {
            if ($output['current_state'] == '1' && $output['notifications_enabled'] == '1') {
                $yellow++;
            } elseif ($output['current_state'] == '2' && $output['notifications_enabled'] == '1') {
                $red++;
            }
        }
    }

    if ($yellow > '0' && $red == '0') {
        echo "<link rel='icon' href='yellow.ico?v=2'>";
    } elseif ($red > '0') {
        echo "<link rel='icon' href='red.ico?v=2'>";
    } else {
        echo "<link rel='icon' href='green.ico?v=2'>";
    }
    ?>

    <title>Monitoring</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Monitoring</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <?php
                foreach ($services as $host => $service) {
                    echo "<li><a href='#" . $host . "'><span class='badge'>" . count($service) . "</span> " . $host . "</a></li>";
                }
                ?>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>

            <div class="row placeholders">
            </div>
            <?php
            foreach ($services as $service) {
                foreach ($service as $output) {
                    if ($output['current_state'] == '1' && $output['notifications_enabled'] == '1') {
                        echo "<div class='alert alert-warning' role='alert'>" . $output['host_name'] . " | " . $output['service_description'] . " - " . $output['plugin_output'] . "</div>";
                    } elseif ($output['current_state'] == '2' && $output['notifications_enabled'] == '1') {
                        echo "<div class='alert alert-danger' role='alert'>" . $output['host_name'] . " | " . $output['service_description'] . " - " . $output['plugin_output'] . "</div>";
                    }
                }
            }

            foreach ($services as $host => $service) {
                echo "<h2 id='" . $host . "' class='sub-header'>" . $host . "</h2>";
                echo "<div class='table-responsive'>";
                echo "<table class='table table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Service</th>";
                echo "<th>Output</th>";
                echo "<th>Last check</th>";
                echo "<th>Next check</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($service as $command => $output) {
                    $lastCheck = new DateTime("@" . $output['last_check']);
                    $nextCheck = new DateTime("@" . $output['next_check']);
                    if ($output['current_state'] == '0') {
                        echo "<tr class='success'>";
                    } elseif ($output['current_state'] == '1') {
                        echo "<tr class='warning'>";
                    } elseif ($output['current_state'] == '2') {
                        echo "<tr class='danger'>";
                    } else {
                        echo "<tr>";
                    }
                    echo "<td>" . $command . "</td>";
                    echo "<td>" . $output['plugin_output'] . "</td>";
                    echo "<td>" . $lastCheck->format('d-m-Y H:i:s') . "</td>";
                    echo "<td>" . $nextCheck->format('d-m-Y H:i:s') . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";

            }
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        setInterval(function () {
            location.reload();
        }, 30000);
    });
</script>

</body>
</html>

