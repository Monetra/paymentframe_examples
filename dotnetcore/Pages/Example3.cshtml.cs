using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Options;
using System.Net.Http;
using TicketApp.Data;
using TicketApp.Models;

namespace TicketApp.Pages
{
    public class Example3Model : PageModel
    {
		private readonly IHttpClientFactory _clientFactory;
		private readonly IOptions<ServerConfig> _serverConfig;
		public bool _getPayment;
		public bool _getConfirmation;

		[TempData] public string _cardshieldticket { get; set; }
		[TempData] public string _cardholdername { get; set; }
		[TempData] public string _amount { get; set; }
		[TempData] public string _tax { get; set; }
		[TempData] public string _zip { get; set; }
		[TempData] public string _email { get; set; }
		[TempData] public string _street { get; set; }
		[TempData] public string _city { get; set; }
		[TempData] public string _state { get; set; }

        public Example3Model(IHttpClientFactory clientFactory, IOptions<ServerConfig> serverConfig)
        {
			_clientFactory = clientFactory;
			_serverConfig = serverConfig;
			_getPayment = true;
        }

        public void OnGet()
        {
			var uri = HttpContext.Request.Host;
			var css = "https://" + uri.Host + ":" + uri.Port + "/css/iframe-3.css";

			// Generate the HTML for our payment frame.
			var paymentFrame = new PaymentFrame(HttpContext, _serverConfig.Value.Username, _serverConfig.Value.Password);
			paymentFrame.SetCssUrl(css);
			paymentFrame.IncludeCardholder(false);
			paymentFrame.IncludeStreet(false);
			paymentFrame.IncludeZip(false);
			paymentFrame.SetExpdateFormat("single-text");
			paymentFrame.AutoReload(true);
			paymentFrame.AutoComplete(false);
			ViewData["PaymentFrame"] = paymentFrame.Generate();
        }

        public void OnGetConfirmation()
		{
			_getPayment = false;
			_getConfirmation = true;
		}

        public void OnGetResponse(Transaction transaction)
		{
			var responseData = transaction.Response();

			if (transaction.Approved())
			{
				ViewData["Result"] = "Order Complete";
			}
			else
			{
				ViewData["Result"] = "Unable to Complete Order: " + responseData["verbiage"];
			}

			ViewData["Receipt"] = transaction.Receipt();

			_getPayment = false;
			_getConfirmation = false;
		}

		public void OnPostPayment()
		{
			// For this example, we're going to hold on to the ticket for a bit while we ask the customer for
			// confirmation.
			_cardshieldticket = Request.Form["ticket"];
			_cardholdername = Request.Form["name"];
			_amount = Request.Form["amount"];
			_tax = Request.Form["tax"];
			_zip = Request.Form["zip"];
			_email = Request.Form["email"];
			_street = Request.Form["street"];
			_city = Request.Form["city"];
			_state = Request.Form["state"];

			// Pass some data on to the confirmation page.
			@ViewData["TxnName"] = _cardholdername;
			@ViewData["TxnEmail"] = _email;
			@ViewData["TxnStreet"] = _street;
			@ViewData["TxnCityStateZip"] = _city + ", " + _state + " " + _zip;

			// Use the cardtype to display a card brand logo.
			string cardtype = Request.Form["cardtype"];
			@ViewData["CardType"] = cardtype.ToLower();
			@ViewData["CardSrc"] = "/images/card-logos/" + @ViewData["CardType"] + ".svg";

			this.OnGetConfirmation();
		}

		public void OnPostConfirmation()
		{
			// Run the transaction and display the result.
			var transaction = new Transaction(_clientFactory, _serverConfig);
			transaction.AddField("cardshieldticket", _cardshieldticket);
			transaction.AddField("cardholdername", _cardholdername);
			transaction.AddField("amount", _amount);
			transaction.AddField("tax", _tax);
			transaction.AddField("zip", _zip);
			transaction.AddField("email", _email);
			transaction.AddField("street", _street);
			transaction.AddField("city", _city);
			transaction.AddField("state", _state);

			if (transaction.Run())
			{
				this.OnGetResponse(transaction);
			}
			else
			{
				RedirectToPage("Error");
			}
		}
    }
}
