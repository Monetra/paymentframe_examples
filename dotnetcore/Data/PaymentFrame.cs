using System;
using System.Collections.Generic;
using Microsoft.AspNetCore.Http;
using System.Text;
using System.Security.Cryptography;
using Microsoft.AspNetCore.Mvc.Rendering;
using Microsoft.AspNetCore.Html;
using System.IO;

namespace TicketApp.Data
{
	public class PaymentFrame
	{
		private Dictionary<string, string> _attributes;
		private byte[] _key;

		public PaymentFrame(HttpContext context, string username, string password)
		{
			// Initialize the object with some required values. We'll generate the other required values later.
			_attributes = new Dictionary<string, string>();

			var uri = context.Request.Host;
			_attributes["data-hmac-domain"] = "https://" + uri.Host + ":" + uri.Port;

			_attributes["data-hmac-username"] = username;

			_key = Encoding.UTF8.GetBytes(password);
		}

		// Generate the <iframe> element for inserting into the page.
		public HtmlString Generate()
		{
			// Record the current timestamp.
			_attributes["data-hmac-timestamp"] = DateTimeOffset.UtcNow.ToUnixTimeSeconds().ToString();

			// Generate a unique ID for this iframe.
			var rand = new Random();
			_attributes["data-hmac-sequence"] = rand.Next().ToString();

			// Generate and add the HMAC.
			_attributes["data-hmac-hmacsha256"] = genHmac();

			// Build the iframe with all of the attributes.
			var iframe = new TagBuilder("iframe");
			iframe.GenerateId("iframe", "");
			foreach (KeyValuePair<string, string> entry in _attributes)
			{
				iframe.MergeAttribute(entry.Key, entry.Value);
			}

			// Write out the iframe.
			using (var writer = new StringWriter())
			{
				iframe.WriteTo(writer, System.Text.Encodings.Web.HtmlEncoder.Default);
				return new HtmlString(writer.ToString());
			}
		}

		// Check that the HMAC returned in "monetra_resp_hmacsha256" matches an HMAC created with the ticket data.
		public bool ValidateTicket(string ticket, string respHmac)
		{
			var items = new StringBuilder("", 200);

			// We need to add these items in this exact order.
			items.Append(_attributes["data-hmac-username"]);
			items.Append(_attributes["data-hmac-timestamp"]);
			items.Append(_attributes["data-hmac-sequence"]);
			items.Append(ticket);

			// Generate the HMAC, using the merchant's password as the key.
			HMACSHA256 hmac = new HMACSHA256(_key);
			byte[] input = Encoding.UTF8.GetBytes(items.ToString());
			byte[] output = hmac.ComputeHash(input);

			// Convert the HMAC into a hexadecimal string.
			string hex = BitConverter.ToString(output).Replace("-", "");

			// Compare the generated HMAC with the one in the response to make sure they match.
			return String.Compare(hex, respHmac, true) == 0;
		}

		public void SetCssUrl(string path)
		{
			_attributes["data-hmac-css-url"] = path;
		}

		public void IncludeCardholder(bool b)
		{
			setBool("data-hmac-include-cardholdername", b);
		}

		public void IncludeStreet(bool b)
		{
			setBool("data-hmac-include-street", b);
		}

		public void IncludeZip(bool b)
		{
			setBool("data-hmac-include-zip", b);
		}

		public void SetExpdateFormat(string format)
		{
			_attributes["data-hmac-expdate-format"] = format;
		}

		public void SetCardholderFormat(string format)
		{
			_attributes["data-hmac-cardholdername-format"] = format;
		}

		public void AutoReload(bool b)
		{
			setBool("data-hmac-auto-reload", b);
		}

		public void AutoComplete(bool b)
		{
			setBool("data-hmac-autocomplete", b);
		}

		public void IncludeSubmitButton(bool b)
		{
			setBool("data-hmac-include-submit-button", b);
		}


		// Generate the HMAC attribute.
		private string genHmac()
		{
			var items = new StringBuilder("", 200);

			// We need to add the values for these items in this exact order.
			List<string> keys = new List<string>()
			{
				"data-hmac-timestamp",
				"data-hmac-domain",
				"data-hmac-sequence",
				"data-hmac-username",
				"data-hmac-css-url",
				"data-hmac-include-cardholdername",
				"data-hmac-include-street",
				"data-hmac-include-zip",
				"data-hmac-expdate-format",
				"data-hmac-cardholdername-format",
				"data-hmac-auto-reload",
				"data-hmac-autocomplete",
				"data-hmac-include-submit-button"
			};

			// Put together all the values for calculating the HMAC.
			foreach (string key in keys)
			{
				if (_attributes.ContainsKey(key))
				{
					items.Append(_attributes[key]);
				}
			}

			// Generate the HMAC, using the merchant's password as the key.
			HMACSHA256 hmac = new HMACSHA256(_key);
			byte[] input = Encoding.UTF8.GetBytes(items.ToString());
			byte[] output = hmac.ComputeHash(input);

			// Output the HMAC as a hexadecimal string.
			return BitConverter.ToString(output).Replace("-", "");
		}

		private void setBool(string key, bool b)
		{
			if (b)
			{
				_attributes[key] = "yes";
			}
			else
			{
				_attributes[key] = "no";
			}
		}
	}
}
