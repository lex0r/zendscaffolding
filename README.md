# Overview
This Zend controller extension class allows you to quickly scaffold
an feature-rich record management interface using Zend MVC core components.
The controllers you would like to scaffold must extend this one, and you will
automatically have create, update, delete and list actions
with search, sorting and pagination. Current scaffolding implementation
is fully based on ZF MVC stack and depends on ALL the components (models, views and certainly controllers).

**Please, use 1.0-beta1 (see it under Tags).**

# Features
## Create, update, delete records
You can easily manage your data using CRUD interface, by just
providing a model. For better UX you should provide more options
to the fields and scaffolding itself. See field and scaffolding options.

## Fetch primary and related table information
Fetch, search and sort by any columns from one primary focus table and one or more related tables.
Relations between table can be:

 * 1-1 (e.g. user - user account)
 * 1-n (e.g. category - articles)
 * n-n (e.g. readers - loaned books)

You are able to see, sort and search by any field from any related table. For this you have to:

 * define `_referenceMap` and `_dependentTables` arrays for models
 * define correct field options

## Provide custom select queries
You can create any complex SQL query using standard Zend_Db_Select.
And you can easily make the result searchable and sortable. Computed fields
are now also supported.

# Installation notes
Place the file Scaffolding.php to Zend/Controller directory to use autoloading, or simply
include it every time you want to use scaffolding. You will also have to copy views directory to
your application or module directory, and 'js' and 'css' folders' contents to the same directories
under 'public' folder.

# Usage
## Basics
```php
class MyController extends Zend_Controller_Scaffolding {
    public function init() {
        // $fields and $options are all optional
        // use if you want to use advanced features
        $this->scaffold(new ZendDbTableModel(), $fields, $options);
    }
}
```
## Custom query
```php
class MyController extends Zend_Controller_Scaffolding {
    public function init() {
        $select = new Zend_Db_Select();
        $select->from(array('t1' => 'table1'), array('field'))
               ->joinLeft(array('t2' => 'table2'), 't1.fk = t2.pk', array('field'))
               ->where('t1.field = ?', 'value')
        $fields = array(
            't1.field' => array(
                'title' => 'Some title'
                'dataType' => 'text'
            ),
            't2.field' => array(
                'title' => 'Another field',
                'dataType' => 'integer'
            ),
        );
        // use if you want to use advanced features
        $this->scaffold($select, $fields);
    }
}
```
## Demo is available!
Please, give a try to the attached demo application (under tests/) for
an idea of what this cool component can do for you! Also, it contains PHPUnit tests.

## Field definitions

`$fields` is an OPTIONAL array of fields with display/search/sorting/validation options like the following:

```php
array(
    'db_field1' => array(
        'option1' => 'value1',
        'option2' => 'value2',
        ...
     )
)
```

Options:

 * title - display label in create/update form and column title in the list.

 * required - is the fields mandatory (true or false), and also 'onCreate' if not required when updating (for not changing the existing value).

 * filters - standard filtering definition used by Zend_Form (e.g. `array('StripTags'))`.

 * validators - standard validation definition used by Zend_Form (e.g `array('emailAddress'))`.

 * hide - whether you want to hide the field in listing and/or edit form,
    use true to hide everywhere, or strings 'list' and 'edit'. _Note: in case of list view, field will be selected anyway, but not displayed_

 * options - a list of predefined values that field may take; use this to override or imitate enum's behavior

```php
'options' => array(1 => 'option1', 2 => 'option2')
```

 * attribs - array of (X)HTML attributes the element has

 * searchable - use to expose the field as list filtering criterion.

 * searchOptions - choose a value from the list to filter the field by (if searchable is true)
    Additionally, use 'type' => 'radio' for radio-style options or 'type' => 'select' for select-style.

```php
'searchOptions' => array(1 => 'option1', 2 => 'option2')`.
```

 * sortable - use to enable list sorting by the field.

 * sortBy - specify default sorting order ('desc' or 'asc')

 * displayField - use only for fields that are foreign keys if you want them to be shown
    as a string from matching parent table record instead of ID. This option points to a
    column that will be used as display value. This column must be defined as reference_name.related_table_column

```php
'displayField' => 'Category.name'` (reference named Category will be used to fetch column text value from field 'name')
```
 * dataType - makes sense only in case of custom Zend_Db_Select query.
    Field type affects how the fields is treated during search.
    Possible values are: text, integer, date, datetime.

 * fieldType - affects how the field is rendered in search/edit forms.
    Edit form case:
      If you want to force certain type for multi-selection fields use 'multicheckbox' or 'multiselect'.

    All cases:
      For single choice fields use 'radio' or 'select'.
      For integer flag field, usually stored as TINYINT and having values 0 or 1, use 'checkbox'
      To force text field display as multi-line use 'textarea', for single line field use 'text' (default).

    Additionally, for text fields you may use 'richtextarea' type, and define `loadRichTextEditor` method to use rich text features.
    If you want to use a JavaScript date picker use 'jsPicker' as type, and define `loadDatePicker` method.

 * translate - set to true if you want the field's value to be translated

 * listModifier - function to be called when field value is displayed in lists (index action);
     the function should take one argument and returns modified field value

```php
'listModifier' => 'functionName'`
```

 * saveModifier - function to be called before saving the field value to database;
     the function should take one argument and returns modified field value

```php
'saveModifier' => 'functionName'`
```

 * loadModifier - function to be called before loading the field value in edit form (create/update actions);
    the function should take one argument and returns modified field value

```php
'loadModifier' => 'functionName'`
```

 * size - size attribute for input elements

```php
'size'  => 15`
```

 * maxlength - maximum string length for input elements.
    If not set it will be determined from database field length

```php
'maxlength' => 25`
```

 * cols - number of columns (for fields of type textarea only)

```php
'cols' => 20`
```

 * rows - number of rows (for fields of type textarea only)

```php
'rows' => 5,`
```

## Options definitions

`$options` is an OPTIONAL array with any of the following keys:

 * pkEditable - true or false - whether you are allowed to edit primary key

 * viewFolder - sometimes you want to use different views for scaffolding in one project. Use
    this variable and copy the scaffolding folder for each component you want to
    change the view basics for.

```php
'viewFolder' => 'path_to_view_folder'`
```

 * entityTitle - used instead of abstract 'entity' in Add entity and delete confirmation

```php
'entityTitle' => 'entity name'`
```

 * createEntityText - used instead of abstract 'New entity' as add button text

```php
'newEntityText' => 'Create new super-duper record'`
```

 * updateEntityText - used instead of abstract 'Update entity' as edit page header

```php
'updateEntityText' => 'Update super-duper record'`
```

 * deleteEntityText - used as confirmation dialog message when deleting a record.

```php
'deleteEntityText' => 'Delete super-duper record'`
```

 * actionParams - an array of additional parameters that will be appended to the action links

```php
'actionParams' => array(self::ACTION_CREATE => array('param' => 'value'))`
```

 * editLayout - layout script name for add/edit entity

 * viewLayout - layout script name for viewing entity

 * disabledActions - which actions must be disabled

```php
'disabledActions'  => array(self::ACTION_UPDATE),`
```

 * createButtons - which buttons to display in creation form
    By default three buttons are displayed: Save, Save and continue editing,
    Save and create new one

```php
'createButtons' => array(self::BUTTON_SAVE)`
```

 * csrfProtected - generate or not CSRF protection form element (of type 'hash').
    Defaults to true

```php
'csrfProtected' => false`
```

 * useIndexAction - set to true if you want the current action (where scaffolding is initialized)
    to be treated as record listing action. Index template will be used for it.
    _Note: you will still need to call parent::indexAction() after scaffolding is initialized with `scaffold`._

 * customMessenger - set to true to use own mechanism of displaying messages upon operation completion.

 * translator - set to a Zend_Translate object for localization