# Overview
This Zend Framework controller extension class allows you to quickly scaffold
an administrative interface for an application, using Zend MVC core components.
The controllers you would like to scaffold must extend this one, and you will
automatically have create, update, delete and list actions for a model. Current
scaffolding implementation is fully based on ZF MVC stack and
uses ALL the components (models, views and certainly controllers).

# Installation notes
Place the file Scaffolding.php to Zend/Controller directory to use autoloading, or simply
include it every time you want to use scaffolding. You will also have to copy views directory to
your application or module directory, and 'js' and 'css' folders' contents to the same directories
under 'public' folder.

# Features
1. Fetch, search and sort by any columns from one primary focus table and one or more related tables.
Relations between table can be:
  - 1-1
  - 1-n
  - n-n

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

 * type - field type that affects how the fields is rendered (e.g. 'checkbox')
    If you want to force certain type for multi-selection fields use 'multicheckbox' or 'multiselect'.
    For single choice fields use 'radio' or 'select'.
    For integer flag field, usually stored as TINYINT and having values 0 or 1, use 'checkbox'
    To force text field display as multi-line use 'textarea', for single line field use 'text'.
    Additionally, for text fields you may use 'richtextarea' type, and define _loadRichTextEditor method to use rich text features.
    If you want to use a JavaScript date picker use 'datepicker' as type, and define _loadDatePicker method.
    Also, if you supply a custom select object (using Zend_Db_Select) you should specify the type
    for the the searchable fields (because metadata are unavailable in this case). Available types are now
    'date' for simple date field, 'datepicker' as mentioned above, 'text' for single line text fields.

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

 * searchEmpty - use to filter by empty values of a field; an extra checkbox is created on the form next to the field

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