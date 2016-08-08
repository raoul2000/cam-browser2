# Install

```
git clone https://github.com/raoul2000/cam-browser2.git
cd cam-browser2
composer install
```

From the *@app* folder.

## create sample image folder

```
yii fake/image-folder
```

# Tests

To run test *Codeception* is required. Install if globally if it's not yet installed:

```
composer global require "codeception/codeception=2.0.*"
composer global require "codeception/specify=*"
composer global require "codeception/verify=*"
  ```

If you've never used Composer for global packages run `composer global status`. It should output:

```
Changed current directory to <directory>
```

Then add `<directory>/vendor/bin` to you `PATH` environment variable. Now we're able to use `codecept` from command
line globally.


## Create a unit test

from the *@app/tests* folder

```
> codecept generate:test unit MyTest
Test was created in <directory>\app\tests\codeception\unit\MyTest.php
```

In the test class, use function *codecept_debug($someVariable)* to output debug data to console.

## Run unit test

Use `-vvv` to display debug output.

```
codecept run unit -vvv ExplorerTest.php
```
