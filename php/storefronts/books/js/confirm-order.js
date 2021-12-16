var completeOrderForm = document.getElementById("complete-order-form");
var completeOrderButton = document.getElementById("complete-order-button");

completeOrderButton.addEventListener("click", function(e) {

	formData = new FormData(completeOrderForm);
	
	fetch('./pay', {
		method: 'POST',
		body: formData
	}).then(function(response) {
		response.json().then(populateAndDisplayReceipt);
	});

});