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
    public class Example1Model : PageModel
    {
		private readonly IHttpClientFactory _clientFactory;
		private readonly IOptions<ServerConfig> _serverConfig;
		public bool _transactionDone;

        public Example1Model(IHttpClientFactory clientFactory, IOptions<ServerConfig> serverConfig)
        {
			_clientFactory = clientFactory;
			_serverConfig = serverConfig;
        }

        public void OnGet()
        {
			var uri = HttpContext.Request.Host;
			var css = "https://" + uri.Host + ":" + uri.Port + "/css/iframe-1.css";

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
		}

		public void OnPost()
		{
			// Set up an object for running the transaction.
			// For this example, we're going to submit the sale request immediately upon receiving the ticket.
			var transaction = new Transaction(_clientFactory, _serverConfig);
			transaction.AddField("cardshieldticket", Request.Form["ticket"]);
			transaction.AddField("amount", Request.Form["amount"]);
			transaction.AddField("tax", Request.Form["tax"]);
			transaction.AddField("zip", Request.Form["zip"]);
			transaction.AddField("cardholdername", Request.Form["name"]);
			transaction.AddField("email", Request.Form["email"]);
			transaction.AddField("street", Request.Form["street"]);
			transaction.AddField("city", Request.Form["city"]);
			transaction.AddField("state", Request.Form["state"]);

			// Run the transaction and display the result.
			if (transaction.Run())
			{
				_transactionDone = true;
				this.OnGetResponse(transaction);
			}
			else
			{
				RedirectToPage("Error");
			}
		}
    }
}
