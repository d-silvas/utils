<!DOCTYPE html>
<html>
<head>
    <title>Silva's portscan</title>
    <style>
        td {
            padding: 5px;
        }
    </style>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>IP</th>
                <th>Hostname</th>
                <th>Port</th>
                <th>Service</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
        <?php

        $subnet = "10.10.10.";
        $start = 1;
        $end = 254;
        $port = 80;

        for ($i = $start; $i <= $end; $i++) {
            echo "<tr>";

            $host = $subnet . $i;
            $connection = @fsockopen($host, $port, $err_code, $err_str, 0.6);

            if (is_resource($connection)) {
                echo "<td>" . $host . "</td>";
                echo "<td>" . gethostbyaddr($host) . "</td>";
                echo "<td>" . $port . "</td>";
                echo "<td>" . getservbyport($port, "tcp") . "</td>";
                echo "<td>Open</td>";
                echo "<td></td>";
                fclose($connection);
            } else {
                echo "<td>" . $host . "</td>";
                echo "<td></td>";
                echo "<td>" . $port . "</td>";
                echo "<td>" . getservbyport($port, "tcp") . "</td>";
                echo "<td>Closed</td>";
                echo "<td>" . $err_code . " - " . $err_str . "</td>";
            }

            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</body>
</html>