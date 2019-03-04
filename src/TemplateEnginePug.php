<?php

namespace TemplateEnginePug;

use TemplateEngineFactory\TemplateEngineBase;

/**
 * Provides the Pug template engine.
 */
class TemplateEnginePug extends TemplateEngineBase
{
    const COMPILE_DIR = 'TemplateEnginePug_compile/';

    /**
     * {@inheritdoc}
     */
    public function render($template, $data = [])
    {
        $template = $this->getTemplatesRootPath() . $this->normalizeTemplate($template);
        $data = $this->getData($data);
        $options = [
          'cache_dir' => $this->wire('config')->paths->assets . 'cache/' . self::COMPILE_DIR,
          'pretty' => $this->moduleConfig['pretty'],
          'debug' => $this->moduleConfig['debug'],
          'enable_profiler' => $this->moduleConfig['profiler'],
        ];
        if($this->moduleConfig['optimizer']) {
          return \Phug\Optimizer::call('renderFile', [$template, $data], $options);
        } else {
          return \Phug::renderFile($template, $data, $options);
        }
    }

    /**
     * @param array $data
     *
     * @throws \ProcessWire\WireException
     *
     * @return array
     */
    private function getData(array $data)
    {
        if (!$this->moduleConfig['api_vars_available']) {
            return $data;
        }

        foreach ($this->wire('all') as $name => $object) {
            $data[$name] = $object;
        }

        return $data;
    }

    /**
     * Normalize the given template by adding the template files suffix.
     *
     * @param string $template
     *
     * @return string
     */
    private function normalizeTemplate($template)
    {
        $suffix = $this->moduleConfig['template_files_suffix'];

        $normalizedTemplate = ltrim($template, DIRECTORY_SEPARATOR);

        if (!preg_match("/\.${suffix}$/", $template)) {
            return $normalizedTemplate . sprintf('.%s', $suffix);
        }

        return $normalizedTemplate;
    }
}
