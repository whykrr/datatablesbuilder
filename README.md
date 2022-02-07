# Datatables Builder

builder response datatables serverside processing for Codeigniter 4

## Requirements

-   PHP 7+
-   Codeigniter 4.0.0+

## Installation

Installation is best done via Composer. Assuming Composer is installed globally, you may use the following command:

```sh
composer require whykrr/datatables-builder
```

## Manual Installation

Should you choose not to use Composer to install, you can clone or download this repo and then enable it by editing app/Config/Autoload.php and adding the Myth\Auth namespace to the $psr4 array. For example, if you copied it into app/ThirdParty:

```php
    $psr4 = [
        'Config'            => APPPATH . 'Config',
        APP_NAMESPACE       => APPPATH,
        'App'               => APPPATH,
        'DatatablesBuilder' => APPPATH .'ThirdParty/datatablesbuilder/src',
    ];
```

## Features

-   Serverside data datatables with pagination
-   Helper for initiation datatables
-   Customize search datatables
-   Customize data with helper function

## Configuration

Once installed you need to configure the framework to use the **DatatablesBuilder** library.
In your application, perform the following setup

Publish configuration file and JavaScript file with following command

```sh
php spark dtbuilder:publish
```

You can easily add configuration on **App/Config/Datatables.php**
Setup your table with `$tableSetting` variable:

```php
    // Example configuration
    public $tableSetting = [
        'users' => [
            'col_title' => 'First Name,Last Name,Adress,Status',
            'col_data' => 'first_name,last_name,address,status',
            'helpers' => [
                'status' => ['formatStatus', '{status}'],
            ],
            'numbering' => true,
            'action_button' => 'edit,delete',
        ],
    ];
```

##### Key **'users'**

'users' key must be unique, as it is used to define the table identity and API url.
then the access url for table users is `{base_url}/datatables/users`

##### Key **'col_title'**

'col_title' key is used to configure column title in the table

##### Key **'col_data'**

'col_title' key is used to configure column data in the table

##### Key **'helpers'**

'helpers' key is used for which configuration data will be overwritten with the return of the helper function, the configuration format see the code as follows

```php
    'helpers' => [
        'key_name_tobe_overwrite' => ['function_name', 'arguments'],
    ],
```

if argument is more than one then add `|` as a separator between the arguments, add `{key}` if the argument contains the row of data to be displayed. **Example : `'{status}|users'`**

##### Key **'numbering'**

'numbering' key is used to configure row data with numbering on first column

##### Key **'action_button'**

'action_button' key is used to configure row data with button or links on last column

**The configuration above will produce the following display**
No | Fisrt Name | Last Name | Adress | Status | Action Button
--- | --- | --- | --- |--- |---
{no} | {fist_name} | {last_name} | {adress} | {status} | {action_btn}
