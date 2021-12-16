var errorMessage = document.getElementById("error-message");
var customerDataForm = document.getElementById('customer-data-form');
var externalSubmitButton = document.getElementById("external-submit-button");
var paymentFrame;

paymentFrame = new PaymentFrame(
	"iframe",
	"https://test.transafe.com:8665"
);

paymentFrame.setPaymentSubmittedCallback(function(response) {

	var ticketInput;

	errorMessage.classList.add('hidden');

	if (response.code === 'AUTH') {
		
		ticketInput = document.createElement("input");
		ticketInput.setAttribute("type", "hidden");
		ticketInput.setAttribute("name", "ticket");
		ticketInput.setAttribute("value", response.ticket);
		customerDataForm.appendChild(ticketInput);
		customerDataForm.submit();
		
	} else {

		errorMessage.innerText = "Unable to process payment information. Please double-check all payment fields.";
		errorMessage.classList.remove('hidden');

	}
});

externalSubmitButton.addEventListener("click", function() {
	paymentFrame.submitPaymentData();
});

paymentFrame.request();
