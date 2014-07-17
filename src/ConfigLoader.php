<?php
/**
 * @author: gareth
 */

namespace Garoevans\ConfigLoader;

class ConfigLoader
{
    /**
     * @var string location of the config file
     */
    private $configDirectory;

    /**
     * @var string name of the config file
     */
    private $configFile;

    /**
     * @var array Array of already loaded configs
     */
    private $loaded = array();

    /**
     * @var array Array of loaded config key/values
     */
    private $config = array();

    /**
     * Initiate the config loader with a directory and file name.
     *
     * @param string $configDirectory
     * @param string $configFile
     */
    public function __construct($configDirectory, $configFile = '.config.ini')
    {
        $this->configDirectory = (string)$configDirectory;
        $this->configFile      = (string)$configFile;
    }

    /**
     * Get the config associated with $name or return $default.
     *
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $names = explode("/", $name);

        if ($names) {
            $return = $this->config;

            foreach ($names as $name) {
                if (isset($return[$name])) {
                    $return = $return[$name];
                } else {
                    $return = $default;
                    break;
                }
            }
        }

        return isset($return) ? $return : $default;
    }

    /**
     * Load the default config file, or overload config values with other configs.
     *
     * @param string $configFile
     * @param string $configDirectory
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     */
    public function load($configFile = '', $configDirectory = '')
    {
        $config = $this->getAbsoluteFileName($configFile, $configDirectory);

        if (isset($this->loaded[$config])) {
            throw new \BadMethodCallException(sprintf("Already loaded [%s]", $config));
        }

        if (!file_exists($config)) {
            throw new \InvalidArgumentException(sprintf("Unable to find [%s]", $config));
        }

        // Error suppressed as checks on following line throw if necessary
        $configArray = @parse_ini_file($config, true);

        if (!$configArray) {
            throw new \InvalidArgumentException(sprintf("Unable to load [%s]", $config));
        }

        $this->loaded[$config] = true;
        $this->config = array_merge($this->config, $configArray);
    }

    /**
     * Build the full path to the filename, including the filename. If empty strings are passed the initiated values are
     * used.
     *
     * @param string $configFile
     * @param string $configDirectory
     * @return string
     */
    private function getAbsoluteFileName($configFile = '', $configDirectory = '')
    {
        $configDirectory = $configDirectory ? : $this->configDirectory;
        $configDirectory = rtrim($configDirectory, '/') . '/';
        $configFile      = $configFile ? : $this->configFile;

        return $configDirectory . $configFile;
    }
}
 