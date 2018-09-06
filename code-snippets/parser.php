<?php

$handle = fopen("GeoLite2-Country-Blocks-IPv4.csv", "r");

$netmask = 24;
$bin = -1 << (32 - (int) $netmask);
$my_ip = "192.168.3.3";
$my_ip_long = ip2long($my_ip);

echo "<pre>";
echo "<table>";
echo "<tr>";
echo "<td>IP</td><td>" . decbin($my_ip_long) . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Netmask</td><td>" . decbin($bin) . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Subnet</td><td>" . decbin($bin & $my_ip_long) . "</td>";
echo "</tr>";
echo "<table>";
echo "</pre>";

function get_the_file() {
    echo "<pre>";
    while (($data = fgetcsv($handle)) !== FALSE) {
        $ip_mask = explode("/", $data[0]);
        $ip = $ip_mask[0];
        $mask = $ip_mask[1];

        echo decbin(ip2long($ip)) . "<br>";
    }
    echo "</pre>";
    fclose($handle);
}

//http://php.net/manual/en/language.types.integer.php
// check PHP_INT_SIZE
class Subnet implements Countable {
    public $subnet_ip = "";
    public $broadcast_ip = "";
    public $int_mask = 0;
    public $int_ips = [];

    public function count() {
        return count($this->int_ips);
    }

    private function __construct ($int_mask, $int_ips) {
        $this->int_mask = $int_mask;
        $this->int_ips  = $int_ips;
    }

    /**
     * @param $cidr_subnet, String, e.g.: 192.168.3.3/24
     */
    public static function create_from_cidr($cidr_subnet) {
        $values = [];

        // ["192.168.3.3", "24"]
        $cidr_arr = explode("/", $cidr_subnet);
        // "192.168.3.3"
        $str_ip = $cidr_arr[0];
        // -1062731005, or 3232236291 if we print it as unsigned int
        $int_ip = ip2long($str_ip);
        // 24
        $cidr_mask = (int) $cidr_arr[1];
        // -256, or 4294967040 if we print it as unsigned int
        $int_mask = -1 << (32 - (int) $cidr_mask);
        // -1062731008, or 3232236288
        $int_subnet_ip = $int_ip & $int_mask;
        // -1062730753, or 3232236543
        $int_broadcast_ip = $int_ip | ( ~ $int_mask);

        $int_ips = [];

        for ($ip = $int_subnet_ip; $ip <= $int_broadcast_ip; $ip++) {
            $int_ips[] = $ip;
        }

        return new Subnet($int_mask, $int_ips);
    }

    public function get_ip_range() {
        return $this->int_ips;
    }
}

$subnet = Subnet::create_from_cidr("1.2.3.4/8");

$host = '127.0.0.1';
$db   = 'ip-test';
$user = 'root';
$pass = '';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);
$statement = $pdo->prepare("INSERT INTO list (ip) VALUES (?)");

foreach ($subnet->get_ip_range() as $ip) {
    $statement->execute(array($ip));
}

