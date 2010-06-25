Kohana Testing
===

This branch is for testing Kohana 3. Use it to ensure that changes in Kohana do not break it's api.

Guidelines for writing unit tests
---

 * Use @covers - This helps provide proper code coverage
 * Use providers - This helps keep your tests simple and makes it easy to add new test cases.
 * When a new feature of bug fix is applied, create a test for it. This may only consist of adding a provider.

How to use the tests
---

1. Make sure that your `test_bootstrap.php` file is correct.  
   You should make sure you have the [unittest](http://github.com/kohana/unittest) module - put it one level up in the modules directory and name it unittest (or you can modify `test_bootstrap.php` to alter its location).

2. cd into this folder (i.e. system)

3. Run all the kohana tests by executing `phpunit` without any arguments. 
   
   If everything goes ok phpunit should print a series of dots (each dot represents a test that's passed) followed by something along the lines of `OK (520 tests, 1939 assertions)`

4. If you want to view code coverage then open cache/coverage/index.html in your browser
   
Known failing tests
---

 * If any other tests fail for your system, please [file a bug](http://dev.kohanaframework.org/projects/kohana3/issues/new)
