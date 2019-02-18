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
     * @var \Pug
     */
    protected $pug;

    /**
     * {@inheritdoc}
     */
    public function render($template, $data = [])
    {
        $template = $this->getTemplatesRootPath() . $this->normalizeTemplate($template);
        $data = $this->getData($data);

        try {
          return $this->getPug()->renderFile($template, $data);
        } catch (Exception $e) {
          throw new WireException($e->getMessage());
        }
    }

    /**
     * @throws \ProcessWire\WireException
     *
     * @return \Pug
     */
    protected function getPug()
    {
        if ($this->pug === null) {
            return $this->buildPug();
        }

        return $this->pug;
    }

    /**
     * @throws \ProcessWire\WireException
     *
     * @return \Pug
     */
    protected function buildPug()
    {
      $this->pug = new \Pug([
        'cache' => $this->wire('config')->paths->assets . 'cache/' . self::COMPILE_DIR,
        'pretty' => $this->moduleConfig['pretty'],
      ]);

      $this->initPug($this->pug);

      return $this->pug;
    }

    /**
     * Hookable method called after Pug has been initialized.
     *
     * Use this method to customize the passed $pug instance,
     * e.g. adding functions and filters.
     *
     * @param \Pug $pug
     */
    protected function ___initPug(\Pug $pug)
    {
    }

    private function isDebug()
    {
        if ($this->moduleConfig['debug'] === 'config') {
            return $this->wire('config')->debug;
        }

        return (bool) $this->moduleConfig['debug'];
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
