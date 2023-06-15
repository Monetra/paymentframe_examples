var errorMessage = document.getElementById("error-message");
var customerDataForm = document.getElementById('customer-data-form');
var submitButton = document.getElementById('submit-button');
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
		
		fetch('./pay-3ds', {
			method: 'POST',
			body: formData
		}).then(
			function(response) {
				response.json().then(populateAndDisplayReceipt);
				submitButton.disabled = false;
			}
		);
		
	} else {

		errorMessage.innerText = "Unable to process payment information. Please double-check all payment fields.";
		errorMessage.classList.remove('hidden');
		submitButton.disabled = false;

	}
});

document.querySelector('input[name="cardholdername"]').addEventListener('input', function(event) {
	paymentFrame.add3dsData({ cardholdername: event.target.value });
});
document.querySelector('input[name="email"]').addEventListener('input', function(event) {
	paymentFrame.add3dsData({ email: event.target.value });
});
document.querySelector('input[name="street"]').addEventListener('input', function(event) {
	paymentFrame.add3dsData({ bill_addr_line_1: event.target.value });
});
document.querySelector('input[name="city"]').addEventListener('input', function(event) {
	paymentFrame.add3dsData({ bill_addr_city: event.target.value });
});
document.querySelector('input[name="state"]').addEventListener('input', function(event) {
	paymentFrame.add3dsData({ bill_addr_state: event.target.value });
});
document.querySelector('input[name="zip"]').addEventListener('input', function(event) {
	paymentFrame.add3dsData({ bill_addr_post_code: event.target.value });
});
document.querySelector('input[name="country"]').addEventListener('input', function(event) {
	paymentFrame.add3dsData({ bill_addr_country: event.target.value });
});

submitButton.addEventListener("click", function(e) {
	submitButton.disabled = true;
	paymentFrame.submitPaymentData();
});

paymentFrame.request();
