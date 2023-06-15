var receiptModalContainer = document.getElementById("receipt-modal-container");
var receiptModal = document.getElementById("receipt-modal");
var receiptContainer = document.getElementById("receipt-container");
var receiptModalClose = document.getElementById("receipt-modal-close");
var infoModalContainer = document.getElementById("info-modal-container");
var infoModalClose = document.getElementById("info-modal-close");
var infoModal = document.getElementById("info-modal");
var infoModalOpen = document.getElementById("info-modal-open");
var iframeElement = document.getElementById("iframe");
var contentContainer = document.getElementById("content-container");

infoModalContainer.addEventListener('click', function(e) {
	infoModalContainer.classList.add('hidden');
});
infoModalClose.addEventListener('click', function(e) {
	infoModalContainer.classList.add('hidden');
});
infoModal.addEventListener('click', function(e) {
	e.stopPropagation();
});
infoModalOpen.addEventListener('click', function(e) {
	infoModalContainer.classList.remove('hidden');
});

receiptModalContainer.addEventListener('click', function(e) {
	receiptModalContainer.classList.add('hidden');
	contentContainer.classList.remove('obscured');
});
receiptModalClose.addEventListener('click', function(e) {
	receiptModalContainer.classList.add('hidden');
	contentContainer.classList.remove('obscured');
});
receiptModal.addEventListener('click', function(e) {
	e.stopPropagation();
});

function populateAndDisplayReceipt(responseJson) {

	var orderCompletionStatus = document.getElementById("order-completion-status");
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
	contentContainer.classList.add('obscured');

}

window.addEventListener("beforeunload", function(e) {
	iframeElement.parentElement.removeChild(iframeElement);
});
