/*README*/


# Freedom Services # [![Build Status](https://travis-ci.org/perseusl/freedom-api-client.svg?branch=master)](https://travis-ci.org/perseusl/freedom-api-client)

For testing Purposes:

-Set access token on BaseTest.php(can be valid or not).
		-invalid access token will immediatly stop the test.
		-valid access token will allow/continue to test the services provided.


*Running the tests
	-type on your CLI vendor/bin/phpunit

*Running a specific test
	-type on your CLI vendor/bin/phpunit --bootstrap tests/bootstrap.php [testfile]e.g.'tests/Freedom/Service/UserTest.php'