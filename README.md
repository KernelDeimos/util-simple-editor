DubéDev Simple Editor for PHP Projects
======================================

**Currently in development!**

This is a very simple PHP package which will generate an
edit page for a specified plaintext file, or list of files.

This is useful for allowing somebody to make changes to
text or configuration on a framework-based web project
without needing to use any technical tools to access the
filesystem.

Installation
------------
This package is not yet on packagist. To install it, add this as a
VCS repository in your composer.json file, like this:

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/KernelDeimos/util-simple-editor"
        }
    ],
    "require": {
        "dubedev/util-simple-editor": "dev-master"
    }
    
Don't forget to run "composer update" in your development environment and "composer install" when you push it to production.

Usage
-----
If you want to get a quick-and-easy editor running, this package contains a default implementation that does the following:
- Creates a map of files in a given folder (folder path passed into constructor)
- Checks for POST data from editor
  - If present
    - Perform operation and return JSON data
  - If not present
    - Display the complete HTML document for the editor

If you want to use this implementation, create an instance of the EditorApplication class and run it as exemplified below:

    use \DubeDev\Util\SimpleEditor\EditorApplication;
    $app = new EditorApplication('path/to/some/folder');
    $app->run();

You should do a login check and make sure an administrative user is accessing the page. It is not recommended you allow public access to plaintext files on your server in this way.

Things to Do
------------
- Improve architecture of example editor's JavaScript code
- Clean up PHP code
- Document PHP code
