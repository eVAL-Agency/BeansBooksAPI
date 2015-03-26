# BeansBooks API for PHP

Simple BeansBooks API written for use in PHP frameworks.

This library is designed to be embedded in other frameworks, such as [Core Plus](http://corepl.us), [Kohana](http://kohanaframework.org/), [Laravel](http://laravel.com), and others.

This project currently does not have an autoloader, (as the framework generally handles that task).

This is version 1.0.0 of the API, feel free to submit patches and feature requests!

## Usage

Copy the lib/BeansBooks into the library directory for your destination project.  (Core Plus component available out of the box.)

Copy config.example.php to config.php and set with your BeansBooks site settings.

## Example Code

    try{
        $customersearch = new \BeansBooks\CustomerSearch();
        $customersearch->searchName = 'smith';
        //$customersearch->execute();
         
        foreach($customersearch as $c){
            var_dump($c);
        }
    }
    catch(Exception $e){
        die($e->getMessage());
    }
    
## Current Functionality

### Account Search
### Customer Sale Search
### Customer Search
### Tax Search
### Vendor Search
### Account lookup/create/update
### Customer lookup/create/update
### Customer Address lookup/create/update
### Tax lookup/create/update
### Vendor lookup/create/update
### Vendor Address lookup/create/update
### Vendor Expense lookup/create/update

## BeansBooks Compatibility

This library is built to work with BeansBooks v1.3.3.