// var paymentDomain = "https://test.transafe.com";
var paymentDomain = paymentServerHost + ":" + paymentServerPort;

// Pop-up info box
var infoModalContainer = document.getElementById("info-modal-container");
var infoModal = document.getElementById("info-modal");
var infoModalOpen = document.getElementById("info-modal-open");
if (infoModalContainer !== null) {
	infoModalContainer.addEventListener('click', function(e) {
		infoModalContainer.classList.add('hidden');
	});
	if (infoModal !== null) {
		infoModal.addEventListener('click', function(e) {
			e.stopPropagation();
		});
	}
	if (infoModalOpen !== null) {
		infoModalOpen.addEventListener('click', function(e) {
			infoModalContainer.classList.remove('hidden');
		});
	}
}

// Rendered payment frame
var iframeElement = document.getElementById("iframe");
if (iframeElement !== null) {
	window.addEventListener("beforeunload", function(e) {
		iframeElement.parentElement.removeChild(iframeElement);
	});

	// External submit button
	var submitButton = document.getElementById("submit-button");
	if (submitButton !== null) {
		submitButton.addEventListener("click", function(e) {
			iframe.contentWindow.postMessage(
				JSON.stringify({ type: "submitPaymentData" }),
				paymentDomain
			);
		});
	}
}

// Error message
var errorMessage = document.getElementById("error-message");
if (errorMessage !== null) {
	errorMessage.classList.add('hidden');
}

// Form containing data supplied by the customer
var customerDataForm = document.getElementById("customer-data-form");
if (customerDataForm !== null) {
	/* Instantiate the PaymentFrame object. The constructor accepts two parameters:
	 * (iframeElementId) The ID of the iframe element on your page that will contain the PaymentFrame
	 * (iframeURL) The URL of the payment server you are using to generate the PaymentFrame
	 */
	var paymentFrame = new PaymentFrame("iframe", paymentDomain);
	if (paymentFrame !== null) {
		if (typeof paymentFrame !== 'undefined') {
			/* You can use the "setPaymentSubmittedCallback" method of the PaymentFrame object to set a callback
			 * function that will be executed once the payment form has been submitted. This function will receive a
			 * "response" object containing details about the payment form submission. This won't include any sensitive
			 * data.
			 */
			paymentFrame.setPaymentSubmittedCallback(function(response) {
				if (response.code === 'AUTH') {
					/* If the response code is "AUTH" (meaning the ticket request was successful), the response object
					 * will contain the CardShield ticket, which can be used in place of card data for the payment
					 * transaction. At this point, you would use the ticket to continue your checkout/payment flow.
					 */
					var zipField = document.querySelector('input[name="zip"]');
					if (zipField !== null) {
						zipField.addEventListener("focus", function(e) {
							zipField.classList.remove("invalid-field");
						})

						if (!zipField.checkValidity()) {
							zipField.classList.add("invalid-field");
							return;
						}
					}

					/* Add the ticket to our form. */
					ticketInput = document.createElement("input");
					ticketInput.setAttribute("type", "hidden");
					ticketInput.setAttribute("name", "ticket");
					ticketInput.setAttribute("value", response.ticket);
					customerDataForm.appendChild(ticketInput);

					/* Add the card type to our form */
					cardtypeInput = document.createElement("input");
					cardtypeInput.setAttribute("type", "hidden");
					cardtypeInput.setAttribute("name", "cardtype");
					cardtypeInput.setAttribute("value", response.cardtype);
					customerDataForm.appendChild(cardtypeInput);

					/* Post the form. */
					customerDataForm.submit();
				} else {
					/* If the response code is "DENY", there was a problem generating the ticket. In this case, the
					 * response object will contain a "verbiage" property with a brief error message.
					 */
					if (errorMessage !== null) {
						errorMessage.innerText = "Unable to process payment information. Please double-check all payment fields.";
						errorMessage.classList.remove('hidden');
					}
				}
			});
		}

		paymentFrame.request();
	}
}
