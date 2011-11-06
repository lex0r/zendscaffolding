***** Overview *****
This Zend Framework controller extension class allows you to quickly scaffold
an administrative interface for an application, using Zend MVC core components.
The controllers you would like to scaffold must extend this one, and you will
automatically have create, update, delete and list actions for a model. Current
scaffolding implementation is fully based on ZF MVC stack and
uses ALL the components (models, views and certainly controllers).

***** Installation notes *****
Place the file Scaffolding.php to Zend/Controller directory to use autoloading, or simply
include it every time you want to use scaffolding. You will also have to copy views directory to
your application or module directory, and 'js' and 'css' folders' contents to the same directories
under 'public' folder.

***** Usage notes *****
class MyController extends Zend_Controller_Scaffolding {
    public function init() {
        // $fields and $options are all optional
        // use if you want to use advanced features
        $this->initScaffolding(new ZendDbTableModel(), $fields, $options);
    }
}

*** Basic use cases ***
1. Fetch, search and sort by any columns from one primary focus table and one or more related tables.
Relations between table can be:
  - 1-1
  - 1-n
  - n-n

***** Field definitions *****

$fields is an OPTIONAL array of fields with display/search/sorting/validation options like the following:
array(
    'db_field1' => array(
        'option1' => 'value1',
        'option2' => 'value2',
        ...
     )
)

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

 * filters - standard filtering definition used by Zend_Form (e.g. array('StripTags')).

 * validators - standard validation definition used by Zend_Form (e.g array('emailAddress')).

 * skip - whether you want to hide the field in listing and/or edit form,
    use true to hide everywhere, or strings 'list' and 'edit'.

 * pk - only use this option with true value in case if Zend_Db_Select is used
    instead of Zend_Db_Table derived class, to hint scaffolding which field
    is PK and will be used as update action parameter.

 * options - a list of predefined values that field may take; use this to override or imitate enum's behavior
    E.g 'options' => array(1 => 'option1', 2 => 'option2').

 * attribs - array of (X)HTML attributes the element has

 * searchable - use to expose the field as list filtering criterion.

 * searchEmpty - use to filter by empty values of a field; an extra checkbox is created on the form next to the field

 * searchOptions - choose a value from the list to filter the field by (if searchable is true)
    Additionally, use 'type' => 'radio' for radio-style options or 'type' => 'select' for select-style.
    E.g 'searchOptions' => array(1 => 'option1', 2 => 'option2').

 * sortable - use to enable list sorting by the field.

 * sortBy - specify default sorting order ('desc' or 'asc')

 * dependentTable - an instance of Zend_Db_Table descendant model with $_referenceMap property set.
    Use for many-to-many fields ONLY.
    E.g 'dependentTable' => new Application_Model_Table()

 * asText - use only for fields that are foreign keys if you want them to be shown
    as a string from matching parent table record instead of ID.
    E.g 'asText' => true

 * asTextColumn - use only for fields that are foreign keys if you want them to be shown
    as a string from matching parent table record instead of ID. This option points to a
    column that will be used as display value
    E.g 'asTextColumn' => 'category_name'

 * order - set the field display order in the list
    if not set fields will be displayed as they come in the table definition
    E.g 'order' => 1

 * translate - set to true if you want the field's value to be translated

 * listModifier - function to be called when field value is displayed in lists (index action);
     the function should take one argument and returns modified field value
     E.g   'listModifier' => 'functionName'

 * saveModifier - function to be called before saving the field value to database;
     the function should take one argument and returns modified field value
     E.g   'saveModifier' => 'functionName'

 * loadModifier - function to be called before loading the field value in edit form (create/update actions);
    the function should take one argument and returns modified field value
    E.g   'loadModifier' => 'functionName'

 * size - size attribute for input elements
    E.g   'size'  => 15

 * maxlength - maximum string length for input elements.
    If not set it will be determined from database field length
    E.g  'maxlength' => 25

 * cols - number of columns (for fields of type textarea only)
    E.g 'cols' => 20

 * rows - number of rows (for fields of type textarea only)
    E.g. 'rows' => 5,

***** Options definitions *****

$options is an OPTIONAL array with any of the following keys:

 * pkEditable - true or false - whether you are allowed to edit primary key

 * fetchSpecifiedFields - true or false - whether you want to fetch only the fields
    provided as second argument to initScaffolding, or you need all the fields from
    the table being queried. Use instead of multiple ('skip' => true) settings.

 * viewFolder - sometimes you want to use different views for scaffolding in one project. Use
    this variable and copy the scaffolding folder for each component you want to
    change the view basics for.
    E.g 'viewFolder' => 'path_to_view_folder'

 * entityTitle - used instead of abstract 'entity' in Add entity and delete confirmation
   E.g 'entityTitle' => 'entity name'

 * createEntityText - used instead of abstract 'New entity' as add button text
   E.g 'newEntityText' => 'Create new super-duper record'

 * updateEntityText - used instead of abstract 'Update entity' as edit page header
   E.g 'updateEntityText' => 'Update super-duper record'

 * deleteEntityText - used as confirmation dialog message when deleting a record.
   E.g 'deleteEntityText' => 'Delete super-duper record'

 * actionParams - an array of additional parameters that will be appended to the action links
   E.g. 'actionParams' => array(self::ACTION_CREATE => array('param' => 'value'))

 * editLayout - layout script name for add/edit entity

 * viewLayout - layout script name for viewing entity

 * disabledActions - which actions must be disabled
    E.g 'disabledActions'  => array(self::ACTION_UPDATE),

 * createButtons - which buttons to display in creation form
    By default three buttons are displayed: Save, Save and continue editing,
    Save and create new one
    E.g 'createButtons' => array(self::BUTTON_SAVE)

 * csrfProtected - generate or not CSRF protection form element (of type 'hash').
    Defaults to true
    E.g 'csrfProtected' => false

 * useIndexAction - set to true if you want the current action (where scaffolding is initialized)
    to be treated as record listing action. Index template will be used for it.
    Note: you will still need to call parent::indexAction() after scaffolding is initialized with initScaffolding.

 * customMessenger - set to true to use own mechanism of displaying messages upon operation completion.

 * translator - set to a Zend_Translate object for localization