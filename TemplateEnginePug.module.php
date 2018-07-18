<?php
/**
 * TemplateEnginePug
 *
 * @author Diktus Dreibholz <dreerr@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License, version 2
 * @version 1.0.8
 *
 * ProcessWire 2.x
 * Copyright (C) 2014 by Ryan Cramer
 * Licensed under GNU/GPL v2, see LICENSE.TXT
 *
 * http://processwire.com
 *
 */


require_once(dirname(__DIR__) . '/TemplateEngineFactory/TemplateEngine.php');
require_once (__DIR__ . '/vendor/autoload.php' /*NoCompile*/);

class TemplateEnginePug extends TemplateEngine implements Module, ConfigurableModule
{
  const COMPILE_DIR = 'TemplateEnginePug_compile/';

    /**
     * @var Pug_Environment
     */
    protected $pug;


    /**
     * Stores variables and values set with TemplateEnginePug::set(). Passed to Pug when rendering the template.
     *
     * @var array
     */
    protected $variables = array();


    /**
     * @param string $filename
     */
    public function __construct($filename = '')
    {
      parent::__construct($filename);
    }


    /**
     * Setup Pug
     */
    public function initEngine()
    {
      $cachePath = $this->wire('config')->paths->assets . 'cache/' . self::COMPILE_DIR;
      $options =
      $this->pug = new Pug(array(
        'cache' => ($this->getConfig('use_cache') ? $cachePath : null),
        'keepBaseName' => true,
        'prettyprint' => $this->getConfig('prettyprint'),
        'stream' => '.' . $this->getConfig('stream')
        ));
      if ($this->getConfig('api_vars_available')) {
        foreach (Wire::getFuel() as $name => $object) {
          if ($name == $this->factory->get('api_var')) continue;
          $this->variables[$name] = $object;
        }
      }
      $this->initPug($this->pug);
    }


    /**
     * @return array
     */
    public static function getDefaultConfig()
    {
      $config = parent::getDefaultConfig();
      return array_merge($config, array(
        'template_files_suffix' => 'pug',
        'prettyprint' => 0,
        'stream' => 'pug.stream',
        'api_vars_available' => 1
        ));
    }


    /**
     * Set a key/value pair to the template
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
      $this->variables[$key] = $value;
    }


    /**
     * Render markup from template file
     *
     * @throws WireException
     * @return mixed
     */
    public function render()
    {
      try {
        $path = $this->getTemplatesPath() . $this->getFilename();
        return $this->pug->renderFile($path, $this->variables);
      } catch (Exception $e) {
        throw new WireException($e->getMessage());
      }
    }


    /**
     * Clear all variables passed
     */
    public function clearVariables()
    {
      $this->variables = array();
    }


    /**
     * Clear cache when uninstalling
     */
    public function uninstall()
    {
      parent::uninstall();
      wireRmdir($this->wire('config')->paths->assets . 'cache/' . self::COMPILE_DIR, true);
    }


    /**
     * Hookable method called after pug instance is created.
     * Allows for customizing Pug, e.g. add filters
     *
     * @param Pug_Environment $pug
     */
    protected function ___initPug(Pug $pug) {}



    /**
     * Methods per interfaces Module, ConfigurableModule
     *
     */


    /**
     * @return array
     */
    public static function getModuleInfo()
    {
      return array(
        'title' => 'Template Engine Pug',
        'summary' => 'Pug/Jade templates for the TemplateEngineFactory',
        'version' => 108,
        'author' => 'Diktus Dreibholz (dreerr)',
        'href' => 'https://processwire.com/talk/topic/11386-module-jade-for-the-templateenginefactory/',
        'singular' => false,
        'autoload' => false,
        'requires' => array('TemplateEngineFactory'),
        );
    }


    /**
     * Return an InputfieldWrapper of Inputfields used to configure the class
     *
     * @param array $data Array of config values indexed by field name
     * @return InputfieldWrapper
     *
     */
    public static function getModuleConfigInputfields(array $data)
    {
      /** @var Modules $modules */
      $data = array_merge(self::getDefaultConfig(), $data);
      $wrapper = parent::getModuleConfigInputfields($data);
      $modules = wire('modules');
      $wrapper->remove('global_template');
      $wrapper->remove('template_files_suffix');

      /** @var InputfieldCheckbox $f */
      $f = $modules->get('InputfieldCheckbox');
      $f->label = __('Import ProcessWire API variables in Pug templates');
      $f->description = __('All API variables (page, input, config etc.) are accessible in Pug, e.g. page for $page');
      $f->name = 'api_vars_available';
      if ($data['api_vars_available']) $f->checked = 1;
      $wrapper->append($f);

      /** @var InputfieldCheckbox $f */
      $f = $modules->get('InputfieldCheckbox');
      $f->label = __('Use Cache');
      $f->description = __('Templates are cached and will be recompiled only when the source code changes.');
      $f->name = 'use_cache';
      if ($data['use_cache']) $f->checked = 1;
      $wrapper->append($f);

      /** @var InputfieldCheckbox $f */
      $f = $modules->get('InputfieldCheckbox');
      $f->label = __('Output indented HTML');
      $f->description = __('Output rendered templates as indented HTML');
      $f->name = 'prettyprint';
      if ($data['prettyprint']) $f->checked = 1;
      $wrapper->append($f);

      /** @var InputfieldText $f */
      $f = $modules->get('InputfieldText');
      $f->label = __('Stream Wrapper Protocol');
      $f->description = __('Enter the name of the protocol under which `Pug\Stream\Template` class is [registered](http://php.net/manual/function.stream-wrapper-register.php)');
      $f->notes = __('You might want to change this if [Suhosin](https://suhosin.org/) is enabled and *suhosin.executor.include.whitelist* is set');
      $f->name = 'stream';
      $f->value = $data['stream'];
      $f->collapsed = 1;
      $f->required = 1;

      $wrapper->append($f);

      return $wrapper;
    }
  }
