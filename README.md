#Introduction
**Zend Framework** now misses an important functionality - quick application scaffolding and record management.
The project aims at filling this gap with a set of useful components.
You may track the project development through the official ZF wiki here:
http://framework.zend.com/wiki/display/ZFPROP/Zend_Controller_Scaffolding+-+Alex+Oroshchuk

# Overview
This Zend controller extension class allows you to quickly scaffold
an feature-rich record management interface using Zend MVC core components.
The controllers you would like to scaffold must extend this one, and you will
automatically have create, update, delete and list actions
with search, sorting and pagination. Current scaffolding implementation
is fully based on ZF MVC stack and depends on ALL the components (models, views and certainly controllers).

**Please, use the latest beta/RC (see it under Tags).**

# Features
* Provides typical scaffolding actions like CReate, Update and Delete, as well as record listing (data grid).
* Supports **ANY** relationships between entities - 1-1, 1-n, n-n!
* Provides sorting of record set by **ANY** native field or field from related table.
* Provides record search by **ANY** native field or field from related table.
* Provides pagination.
* Supports relationships when editing/searching/displaying related (1-n, n-n) entities.
* This component allows to restrict all or certain CRUD actions.
* Fully changeable notification messages and field titles.

# Is it extensible?
* This component is fully  extensible and allows to easily mix scaffolding and custom actions.
* This component allows to use custom views for scaffolding actions by setting a view path.
* This component provides several callbacks (through overriding of protected methods) to customize edit and search forms and enhance user experience.
* This component provides before- and after- event handlers of create, update and delete operations to ensure additional actions.
* This component supports custom select statements (Zend_Db_Select or Zend_Db_Table_Select) for fully controllable and highly customizeable record listing.
* and some more not listed here!

# Is it stable?
About 15 functional tests (60+ assertions) are available now, and new tests are added periodically.
It's really easy to use.
The only code you have to write to have basic CRUD is the one that extends your controller from Zend_Controller_Scaffolding class and calls

`$this->scaffold(new Custom_Model_Extending_Zend_Db_Table())`

from its `init` or any other relevant method.

# How to start using?
You should take a look at `docs` folder, but if you are fond of learning y example
**a demo application showing all major features is available under `tests` folder**
Use this app for demo/quick start purposes, or by PHPUnit for testing own changes.