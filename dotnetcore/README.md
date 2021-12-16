# .NET Core Example
These examples use .NET Core with Razor Pages to show how the payment frame can be used in three different ways.

### Example 1
* Immediate sale upon receiving the ticket

### Example 2
* Immediate sale upon receiving the ticket
* Cardholder name field in payment frame
* Zip field in payment frame
* Drop down selections for expiration date
* External submit button

### Example 3
* Delay between receiving ticket and performing sale with ticket
* Confirmation page


## How to run
To run these examples, you need to have the .NET Core SDK installed. Instructions on installing the SDK can be found [here](https://dotnet.microsoft.com/download/dotnet-core).

1. With the SDK installed, navigate into the main project directory (`paymentframe_examples/dotnetcore/`) and run this command:
```
	dotnet run
```
2. The SDK will spin up an engine that serves files on the port(s) specified in `Properties/launchSettings.json`. By convention (and default), this is port 5001.
3. You can then browse to https://localhost:5001 (with the appropriate port) in a web browser to interact with the example pages.


## Configuration
By default, these examples use the public test account at test.transafe.com. To change the account used or to use a different Monetra instance, you need to update the Monetra settings in `PaymentServerConfig.json` in the project root.


## Version
This sample was created with `.NET Core SDK 3.1.401`.
