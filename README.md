# TemplateEnginePug

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![ProcessWire 3](https://img.shields.io/badge/ProcessWire-3.x-orange.svg)](https://github.com/processwire/processwire)

A ProcessWire module adding [Pug](https://github.com/pug-php/pug) to the [TemplateEngineFactory](https://github.com/wanze/TemplateEngineFactory).

## Requirements

* ProcessWire `3.0` or newer
* TemplateEngineFactory `2.0` or newer
* PHP `7.0` or newer
* Composer

> The `1.x` version of this module is available on the [1.x branch](https://github.com/dreerr/TemplateEnginePug/tree/1.x).
Use this version if you still use _TemplateEngineFactory_ `1.x`.

## Installation

Execute the following command in the root directory of your ProcessWire installation:

```
composer require dreerr/template-engine-pug:^2.0
```

This will install the _TemplateEnginePug_ and _TemplateEngineFactory_ modules in one step. Afterwards, don't forget
to enable Pug as engine in the _TemplateEngineFactory_ module's configuration.

## Configuration

The module offers the following configuration:

* **`Template files suffix`** The suffix of the Pug template files, defaults to `pug`.
* **`Provide ProcessWire API variables in  templates`** API variables (`$pages`, `$input`, `$config`...)
are accessible in Pug.
* **`Output indented HTML`** If checked, the output is rendered as indented HTML.
* **`Debug Output`** When an error occurs at render time, you will get a complete stack trace including line and offset in the original pug source file.
* **`Enable Profiler`** When set to true, it will output on render a timeline you can inspect in your browser to see wich token/node take longer to lex/parse/compile/render.