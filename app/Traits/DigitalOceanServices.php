<?php

namespace App\Traits;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait DigitalOceanServices {

    public function DB()
    {
        $instancies = collect();

        $response = Http::withToken('f6d25e1514f8f1506adcf0a4a70186cd4b6c2d200feca92f470589ccc8f62580')
        ->get('https://api.digitalocean.com/v2/databases');

        if ($response->status() == 200) {
            $instancies = collect($response->collect()['databases']);

            $instancies = $instancies->filter(function ($item) {
                return count($item['db_names']) <= 5;
            });
        }

        return $instancies->count() ? [
            'uuid' => $instancies[0]['id'],
            'host' => $instancies[0]['connection']['host'],
            'port' => $instancies[0]['connection']['port'],
            'user' => $instancies[0]['connection']['user'],
            'password' => $instancies[0]['connection']['password'],

        ] : false;
    }

    public function createCluster()
    {
        $instance = collect();

        $data = [
            "name" => "dbquipusbasicnodo" . date_format(now(), 'Hisu'),
            "engine" => "mysql",
            "version" => "8",
            "region" => "nyc1",
            "size" => "db-s-1vcpu-1gb",
            "num_nodes" => 1,
            "tags" => ["production"]
        ];

        $response = Http::withToken('f6d25e1514f8f1506adcf0a4a70186cd4b6c2d200feca92f470589ccc8f62580')
        ->post('https://api.digitalocean.com/v2/databases', $data);

        if ($response->status() == 200) {
            $instance = collect($response->collect()['database']);
        }


        return $instance->count() ? [
            'uuid' => $instance['id'],
            'host' => $instance['connection']['host'],
            'port' => $instance['connection']['port'],
            'user' => $instance['connection']['user'],
            'password' => $instance['connection']['password'],

        ] : false;
    }

    public function createDataBase($name, $uuid)
    {
        $database = null;

        $data = [
            "name" => 'qs_' . $name,
        ];

        $response = Http::withToken('f6d25e1514f8f1506adcf0a4a70186cd4b6c2d200feca92f470589ccc8f62580')
        ->post('https://api.digitalocean.com/v2/databases/'. $uuid .'/dbs', $data);

        if ($response->status() == 201) {
            $database = $response->collect()['db']['name'];
        }

        return $database?: false;
    }

    public function createUserDB($name, $instance, $database)
    {

        $user = "qs-dan-" . $name;
        $password = Str::random(8);

        $command = "mysql -u " . $instance['user'] . " -h " . $instance['host'] . " -P " . $instance['port'] . " -p" . $instance['password'];

        $argument = "-e \"CREATE USER '". $user ."'@'%' IDENTIFIED WITH mysql_native_password BY '" . $password . "';\"";
        exec($command . " " . $argument);
        sleep(45);

        $argument = "-e \"GRANT EXECUTE, SHOW VIEW, ALTER, ALTER ROUTINE, CREATE TEMPORARY TABLES, CREATE ROUTINE, DROP, CREATE VIEW, INDEX, EVENT, REFERENCES, TRIGGER, CREATE, INSERT, SELECT, UPDATE ON " . $database . ".* TO '" . $user . "'@'%';\"";
        exec($command . " " . $argument);
        sleep(45);

        $argument = "-e \"FLUSH PRIVILEGES;\"";
        exec($command . " " . $argument);
        sleep(45);

        sleep(30);
    }
}
