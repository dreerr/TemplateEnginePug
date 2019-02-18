<?php

namespace ProcessWire;

use TemplateEnginePug\TemplateEnginePug as PugEngine;

/**
 * Adds Pug templates to the TemplateEngineFactory module.
 */
class TemplateEnginePug extends WireData implements Module, ConfigurableModule
{
    /**
     * @var array
     */
    private static $defaultConfig = [
        'template_files_suffix' => 'pug',
        'api_vars_available' => 1,
        'pretty' => 1,
    ];

    public function __construct()
    {
        parent::__construct();

        $this->wire('classLoader')->addNamespace('TemplateEnginePug', __DIR__ . '/src');
        $this->setDefaultConfig();
    }

    /**
     * @return array
     */
    public static function getModuleInfo()
    {
        return [
            'title' => 'Template Engine Pug',
            'summary' => 'Pug templates for the TemplateEngineFactory',
            'version' => 200,
            'author' => 'Diktus Dreibholz',
            'href' => 'https://github.com/dreerr/TemplateEnginePug/',
            'singular' => true,
            'autoload' => true,
            'requires' => [
                'TemplateEngineFactory>=2.0.0',
                'PHP>=7.0',
                'ProcessWire>=3.0',
            ],
        ];
    }

    public function init()
    {
        /** @var \ProcessWire\TemplateEngineFactory $factory */
        $factory = $this->wire('modules')->get('TemplateEngineFactory');

        $factory->registerEngine('Pug', new PugEngine($factory->getArray(), $this->getArray()));
    }

    private function setDefaultConfig()
    {
        foreach (self::$defaultConfig as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param array $data
     *
     * @throws \ProcessWire\WireException
     * @throws \ProcessWire\WirePermissionException
     *
     * @return \ProcessWire\InputfieldWrapper
     */
    public static function getModuleConfigInputfields(array $data)
    {
        /** @var Modules $modules */
        $data = array_merge(self::$defaultConfig, $data);
        $wrapper = new InputfieldWrapper();
        $modules = wire('modules');

        /** @var \ProcessWire\InputfieldText $field */
        $field = $modules->get('InputfieldText');
        $field->label = __('Template files suffix');
        $field->name = 'template_files_suffix';
        $field->value = $data['template_files_suffix'];
        $field->required = 1;
        $wrapper->append($field);

        $field = $modules->get('InputfieldCheckbox');
        $field->label = __('Provide ProcessWire API variables in Pug templates');
        $field->description = __('API variables (`$pages`, `$input`, `$config`...) are accessible in Pug, e.g. `{{ config }}` for the config API variable.');
        $field->name = 'api_vars_available';
        $field->checked = (bool) $data['api_vars_available'];
        $wrapper->append($field);

        $field = $modules->get('InputfieldCheckbox');
        $field->label = __('Output indented HTML');
        $field->description = __('Output rendered templates as indented HTML');
        $field->name = 'pretty';
        $field->checked = (bool) $data['pretty'];
        $wrapper->append($field);
        return $wrapper;
    }
}
