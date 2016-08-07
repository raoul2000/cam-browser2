# Various

## install

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

To run test *Codeception* is required.

from the *@app/tests* folder

# create a unit test

```
> codecept generate:test unit MyTest
Test was created in D:\dev\cam-browser\app\tests\codeception\unit\MyTest.php
```

In the test class, use function *codecept_debug($someVariable)* to output debug data to console.

## run single unit test

Use `-vvv` to display debug output.

```
codecept run unit -vvv ExplorerTest.php
```
