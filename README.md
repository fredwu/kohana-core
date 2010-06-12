Kohana Testing
===

This branch is for testing Kohana 3. Use it to ensure that changes in Kohana do not break it's api.

Guidelines for writing unit tests
---

 * Use @covers - This helps provide proper code coverage
 * Use providers - This helps keep your tests simple and makes it easy to add new test cases.
 * When a new feature of bug fix is applied, create a test for it. This may only consist of adding a provider.