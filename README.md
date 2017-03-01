TemplateEnginePug
==================

ProcessWire module adding Pug templates (formerly known as Jade) to the TemplateEngineFactory. It uses Pug.php from https://github.com/pug-php/pug .

## Installation
Install the module just like any other ProcessWire module. Check out the following guide: http://modules.processwire.com/install-uninstall/

This module requires TemplateEngineFactory: https://github.com/wanze/TemplateEngineFactory

After installing, don't forget to enable Pug as engine in the TemplateEngineFactory module's settings.

## Configuration
* **Path to templates** Path to folder where you want to store your Pug template files.
* **Import ProcessWire API variables in Pug template** If checked, any API variable is accessible inside the Pug templates, for example *page* refers to the current page.
* **Cache** If checked, templates are cached and will be recompiled only when the source code changes.
* **Output indented HTML** If checked, the output is rendered as indented HTML.
* **Stream Wrapper Protocol** Set the name of the protocol for registering the class (you might want to edit this if [Suhosin](https://suhosin.org/) is enabled).