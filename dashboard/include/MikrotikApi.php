<?php
require_once '../vendor/autoload.php';

error_reporting(E_ALL);

use \RouterOS\Client;
use \RouterOS\Query;
use \RouterOS\Exceptions\ConnectException;

class MikrotikApi {
    private $client;

    public function __construct($host, $user, $pass) {
        try {
            $this->client = new Client([
                'timeout' => 1,
                'host'    => $host,
                'user'    => $user,
                'pass'    => $pass,
                'port'    => 8728
            ]);
        } catch (ConnectException $e) {
            throw new Exception("Failed to connect to MikroTik: " . $e->getMessage());
        }
    }

    public function monitorInterfaces(){
        $query = new Query('/system/resource/print');

        $response = $this->client->query($query)->read();

        if (!empty($response)) {
            $result = array(
                'cpu-load' => $response[0]['cpu-load'],
                'free-memory' => $response[0]['free-memory'],
                'uptime' => $response[0]['uptime']
            );
        }
        return $result;
    }

    public function getAllPpoein(){
        // Build query
        $query = new Query('/interface/print');
        $query->where('type', "pppoe-in");

        // Send query to RouterOS
        $response = $this->client->query($query)->read();

        return $response;
    }

    public function getSecret($name){
        $query = new Query('/ppp/secret/print');
        $query->where('name', $name);

        // Send query to RouterOS
        $secret = $this->client->query($query)->read();

        return $secret;
    }

    public function enableService($id){
        $query = (new Query('/ppp/secret/set'))
            ->equal('.id', $id)
            ->equal('disabled', 'no');

        $response = $this->client->query($query)->read();

        return !(empty($response));
    }

    public function disableService($id){
        $query = (new Query('/ppp/secret/set'))
            ->equal('.id', $id)
            ->equal('disabled', 'yes');

        $response = $this->client->query($query)->read();

        return !(empty($response));
    }

    public function getAllPpoeserver() {
        // Build query
        $query = new Query('/interface/pppoe-server/getall');

        // Send query to RouterOS
        $response = $this->client->query($query)->read();

        return $response;
    }

    public function getPppoeIdByName($name) {
        // Build query
        $query = new Query('/interface/pppoe-server/print');
        $query->where('user', $name);

        // Send query to RouterOS
        $response = $this->client->query($query)->read();

        return $response;
    }

    public function removePppoe($id) {
        $query = (new Query('/interface/pppoe-server/remove'))
                ->equal('.id', $id);

        $response = $this->client->query($query)->read();

        return !(empty($response));
    }

    public function monitorPpoein($id) {
        // Build query
        $query = (new Query('/interface/monitor-traffic'))
        ->equal('interface', $id)
        ->equal('once');

        // Send query to RouterOS
        $response = $this->client->query($query)->read();

        if (!empty($response)) {
            $result = array(
                'tx' => $response[0]['tx-bits-per-second'],
                'rx' => $response[0]['rx-bits-per-second']
            );
        }

        return $result;
    }

    public function actPppoecon($name) {
        // Build query
        $query = (new Query('/ppp/active/print'));
        $query->where('name', $name);

        // Send query to RouterOS
        $response = $this->client->query($query)->read();

        return !(empty($response));
    }
}

?>