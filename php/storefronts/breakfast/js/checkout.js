var errorMessage = document.getElementById("error-message");
var customerDataForm = document.getElementById('customer-data-form');
var externalSubmitButton = document.getElementById("external-submit-button");
var paymentFrame;

paymentFrame = new PaymentFrame(
	"iframe",
	"https://test.transafe.com:8665"
);

paymentFrame.setPaymentSubmittedCallback(function(response) {

	var formData;

	errorMessage.classList.add('hidden');
	if (response.code === 'AUTH') {
		
		formData = new FormData(customerDataForm);
		formData.append('ticket', response.ticket);
		
		fetch('./pay', {
			method: 'POST',
			body: formData
		}).then(
			function(response) {
				response.json().then(populateAndDisplayReceipt);
			}
		);
		
	} else {

		errorMessage.innerText = "Unable to process payment information. Please double-check all payment fields.";
		errorMessage.classList.remove('hidden');

	}
});

paymentFrame.setFormSubmissionInvalidCallback(function() {
	externalSubmitButton.disabled = false;
});

externalSubmitButton.addEventListener("click", function() {
	externalSubmitButton.disabled = true;
	paymentFrame.submitPaymentData();
});

paymentFrame.request();
