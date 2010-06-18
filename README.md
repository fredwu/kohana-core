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

 * Make sure you have the [unittest](http://github.com/kohana/unittest) module, and put it one level up from this directory, and name it unittest (or you can modify the test_bootstrap.php file to point to it).

Known failing tests
---

 * If any other tests fail for your system, please [file a bug](http://dev.kohanaframework.org/projects/kohana3/issues/new)
