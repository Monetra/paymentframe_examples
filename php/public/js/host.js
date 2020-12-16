var receiptModalContainer = document.getElementById("receipt-modal-container");
var receiptModal = document.getElementById("receipt-modal");
var receiptContainer = document.getElementById("receipt-container");
var errorMessage = document.getElementById("error-message");
var infoModalContainer = document.getElementById("info-modal-container");
var infoModal = document.getElementById("info-modal");
var infoModalOpen = document.getElementById("info-modal-open");
var orderCompletionStatus = document.getElementById("order-completion-status");
var customerDataForm = document.getElementById('customer-data-form');
var formAction;
var paymentFrame;
var iframeElement = document.getElementById("iframe");

if (customerDataForm !== null) {
	formAction = customerDataForm.dataset.action;
	infoModalContainer.addEventListener('click', function(e) {
		infoModalContainer.classList.add('hidden');
	});
	infoModal.addEventListener('click', function(e) {
		e.stopPropagation();
	});
	infoModalOpen.addEventListener('click', function(e) {
		infoModalContainer.classList.remove('hidden');
	});
	paymentFrame = new PaymentFrame(
		"iframe",
		"https://test.transafe.com:8665"
	);
}
var zipField = document.querySelector('input[name="zip"]');
if (zipField !== null) {
	zipField.addEventListener("focus", function(e) {
		zipField.classList.remove("invalid-field");
	})
}
var completeOrderForm = document.getElementById("complete-order-form");
var completeOrderButton = document.getElementById("complete-order-button");

receiptModalContainer.addEventListener('click', function(e) {
	receiptModalContainer.classList.add('hidden');
});
receiptModal.addEventListener('click', function(e) {
	e.stopPropagation();
});

function submitOrder(formData) {
	fetch('./transaction.php', {
		method: 'POST',
		body: formData
	}).then(function(response) {
		response.json().then(function(responseJson) {
			var receiptHTML = [];
			if (responseJson.code === 'AUTH') {
				orderCompletionStatus.innerText = 'Order Complete';
			} else {
				orderCompletionStatus.innerText = 'Unable to Complete Order';
			}
			receiptHTML.push(responseJson.rcpt_cust_merch_info);
			receiptHTML.push(responseJson.rcpt_cust_reference);
			receiptHTML.push(responseJson.rcpt_cust_money);
			receiptHTML.push(responseJson.rcpt_cust_disposition);
			receiptHTML.push(responseJson.rcpt_cust_notice);
			receiptContainer.innerHTML = receiptHTML.join("");
			receiptModalContainer.classList.remove('hidden');
			window.scrollTo(0, 0);
		});
	});
}

if (typeof paymentFrame !== 'undefined') {
	paymentFrame.setPaymentSubmittedCallback(function(response) {
		var formData;
		var zipFieldValid;
		var ticketInput;
		errorMessage.classList.add('hidden');
		if (response.code === 'AUTH') {
			if (zipField !== null) {
				zipFieldValid = zipField.checkValidity();
				if (!zipFieldValid) {
					zipField.classList.add("invalid-field");
					return;
				}
			}
			if (formAction === 'transaction') {
				formData = new FormData(customerDataForm);
				formData.append('ticket', response.ticket);
				submitOrder(formData);
			} else if (formAction === 'confirmation') {
				ticketInput = document.createElement("input");
				ticketInput.setAttribute("type", "hidden");
				ticketInput.setAttribute("name", "ticket");
				ticketInput.setAttribute("value", response.ticket);
				customerDataForm.appendChild(ticketInput);
				customerDataForm.setAttribute("action", "/confirmation.php");
				customerDataForm.submit();
			}
		} else {
			errorMessage.innerText = "Unable to process payment information. Please double-check all payment fields.";
			errorMessage.classList.remove('hidden');
		}
	});
}

if (completeOrderButton !== null) {
	completeOrderButton.addEventListener("click", function(e) {
		formData = new FormData(completeOrderForm);
		submitOrder(formData);
	});
}

if (typeof paymentFrame !== "undefined") {
	paymentFrame.request();
}

window.addEventListener("beforeunload", function(e) {
	iframeElement.parentElement.removeChild(iframeElement);
});
