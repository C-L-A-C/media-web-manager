<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupPulseaudioConf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:pulse {users_dir=/home : The directory where the web server home will be created} {http_user=http : The web server user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up pulseaudio configuation. Needs root privileges.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $usersDir = $this->argument("users_dir");
        $httpUser = $this->argument("http_user");
        $this->setupHttpHome($usersDir, $httpUser);
        $this->setupPAConfiguration($httpUser);
        $this->grantPrivileges($httpUser);

        echo "Configuration done. You need to restart pulseaudio server to take these changes into account.\n";
        return 0;
    }

    public function setupHttpHome(string $baseDir, string $user)
    {
        $home = "$baseDir/$user";
        $configDir = "$home/.config/pulse";
        $configFile = "$configDir/client.conf";

        if (! is_dir($home)) {
            if (! @mkdir($home, 0755))
                throw new \Exception("Cannot create home directory : $home");
        }

        if (! is_dir($configDir)) {
            if (! @mkdir($configDir, 0755, true))
                throw new \Exception("Cannot create configuration directory : $configDir");
        }

        if (! is_file($configFile)) {
            if (! @file_put_contents($configFile, "\ndefault-server = unix:/tmp/pulse-socket", FILE_APPEND))
                throw new \Exception("Cannot write configuration to file : $configFile");
        }

        if (system("chown -R $user:$user $home", $return) === false || $return)
            throw new \Exception("Cannot change user ownership of $configDir to $user (code $return)");
    }

    public function setupPAConfiguration(string $group)
    {
        $configFile = "/etc/pulse/default.pa";
        $configFileClient = "/etc/pulse/client.conf";
        //Or auth-group=$group
        //Grants access to PA to all users
        $command = "load-module module-native-protocol-unix auth-anonymous=1 socket=/tmp/pulse-socket";
        $commandClient = "ndefault-server = unix:/tmp/pulse-socket";

        if (! is_writable($configFile))
            throw new \Exception("Cannot access pulseaudio configuration file $configFile. Please run as root");

        if (strpos(file_get_contents($configFile), $command) === false) {
            if (! @file_put_contents($configFile, "\n$command", FILE_APPEND))
                throw new \Exception("Cannot write configuration to file : $configFile");
        }
        if (strpos(file_get_contents($configFileClient), $commandClient) === false) {
            if (! @file_put_contents($configFileClient, "\n$commandClient", FILE_APPEND))
                throw new \Exception("Cannot write configuration to file : $configFileClient");
        }

    }

    public function grantPrivileges(string $httpUser)
    {
        /*$command = "usermod -a -G audio $httpUser";
        if (system($command, $return) === false || $return)
            throw new \Exception("Cannot add $httpUser to group $httpUser : return code is $return");*/
    }
}
