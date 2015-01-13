<?php namespace Zizaco\Entrust;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ConfigCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'entrust:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a config following the Entrust especifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $message = "Zizaco/entrust package config";
        $this->comment( $message );

        if ( $this->confirm("Proceed with the config creation? [Yes|no]") ) {

            $this->line('');

            $this->info( "Creating config..." );
            if ( $this->createConfig() ) {

                $this->info( "Config successfully created!" );
            } else {
                $this->error(
                    "Coudn't create config.\n Check the write permissions".
                    " within the config/packages directory."
                );
            }

            $this->line('');

        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

    /**
     * Create the config.
     *
     * @return bool
     */
    protected function createConfig()
    {
        $config_dir = base_path()."/config/packages/zizaco/entrust";
        $config_file = $config_dir."/config.php";
        $config_template = substr(__DIR__,0,-8)."config/config.php";

        if (!is_dir($config_dir) && mkdir($config_dir, 0775, true)) {
            if (!file_exists($config_file) && copy($config_template, $config_file)) {
                return true;
            }
        }
        return false;
    }
}
