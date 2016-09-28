This is a work in progress

File browser built on top of [Flysystem](https://flysystem.thephpleague.com/).

# Install

```
git clone https://github.com/raoul2000/cam-browser2.git
cd cam-browser2
composer install
```


# Testing

The `tests\codeception` directory contains various tests for the basic application. These tests are developed with [Codeception PHP Testing Framework](http://codeception.com/).

After creating the basic application, follow these steps to prepare for the tests:

## Using the *vendor* Codeception

When you install the application, the *codeception* dependency is included so you just have to navigate to the `tests` subfolder and run :

1. Install and start the local FTP server:
```
npm install
npm start
```

2. Build and run tests
```
..\vendor\bin\codecept build
..\vendor\bin\codecept run unit
```

## Install Codeception globally

Another option is to install Codeception globally.

1. Install Codeception if it's not yet installed:

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

2. Build the test suites:

   ```
   codecept build
   ```
4. Install and start the local FTP server:

```
npm install
npm start
```

3. Now you can run the tests with the following commands:

 ```
 # run unit tests
 codecept run unit
 ```

Please refer to [Codeception tutorial](http://codeception.com/docs/01-Introduction) for
more details about writing and running acceptance, functional and unit tests.
