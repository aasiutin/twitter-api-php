<?php

namespace TwitterAPIPHP\Utils;

/**
 * This trait is used fo convenient managing object configuration
 * options. It allows to retrieve and setup single and multiple
 * options, delete option. It's assumed that options are grouped
 * by string keys
 *
 * @example this is example of how can we use Options trait
 *  class A
 * {
        use Options;
 *
 *      public function __construct($options)
 *      {
            $this->setOptions($this->getDefaultOptions());
 *          $this->setOptions($options);
 *      }
 *
 *      public function doSomething()
 *      {
            $url = $this->getOption('url');
 *          //do something with url
 *      }
 *
 *      protected function getDefaultOptions()
 *      {
            return array(
                'url' => 'example.com'
 *          );
 *      }
 * }
 *
 * @file Options trait for managing class configuration options
 * @author lexicus <sutok85@gmail.com>
 * @version 1.0.0
 */


/**
 * Class Options
 * @package TwitterAPIPHP\Utils
 */
trait Options
{
    /**
     * @var array property for options storing
     */
    protected $options = array();

    /**
     * return objects options
     *
     * @return array object options array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * set new options, will ability to keep old ones or delete them
     *
     * @param array $options new set of options
     * @param bool $reset if true, old options are deleted, otherwise
     * new options are merged with old ones
     *
     * @return void
     */
    public function setOptions(array $options, $reset = false)
    {
        if ($reset) {
            $this->options = $options;
            return;
        }
        $this->options = $options + $this->options;
    }

    /**
     * get option by its key
     *
     * @param string $optionKey option key
     * @return mixed option value
     */
    public function getOption($optionKey)
    {
        if (array_key_exists($optionKey, $this->option)) {
            return $this->options[$optionKey];
        }

        return null;
    }

    /**
     * set option by its key and value
     *
     * @param string $optionKey option key
     * @param mixed $optionValue value to store
     *
     * @return void
     */
    public function setOption($optionKey, $optionValue)
    {
        $this->options[$optionKey] = $optionValue;
    }

    /**
     * remove option by its key
     *
     * @param string $optionKey key of the option to be removed
     *
     * @return void
     */
    public function removeOption($optionKey) {
        unset($this->options[$optionKey]);
    }

    /**
     * get default options set.
     *
     * if class provides default options set, this method
     * should be overridden in that class.
     * @example
     *  public function __construct(array $options)
     *  {
     *      $this->setOptions($this->getDefaultOptions());
     *      $this->setOptions($options);
     *  }
     *
     * @return array default options set
     */
    protected function getDefaultOptions()
    {
        return array();
    }
}
