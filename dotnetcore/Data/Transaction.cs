using System;
using System.Collections.Generic;
using System.Text;
using Microsoft.AspNetCore.Html;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text.Json;
using Microsoft.Extensions.Options;
using TicketApp.Models;

namespace TicketApp.Data
{
	public class Transaction
	{
		private readonly HttpClient _client;
		private readonly HttpRequestMessage _request;
		private readonly List<KeyValuePair<string, string>> _fields;
		private Dictionary<string, string> _response;

		public Transaction(IHttpClientFactory clientFactory, IOptions<ServerConfig> serverConfig)
		{
			// Set up the HTTP client.
			_client = clientFactory.CreateClient();
			_client.BaseAddress = new Uri(serverConfig.Value.Host + ":" + serverConfig.Value.Port);
			_client.DefaultRequestHeaders.Clear();
			_client.DefaultRequestHeaders.ConnectionClose = true;

			// Establish the POST request.
			_request = new HttpRequestMessage(HttpMethod.Post, "/api/v1/transaction/purchase");

			// Add the Basic Authentication header.
			var authString = serverConfig.Value.Username.Replace(":", "|") + ":" + serverConfig.Value.Password;
			var b64 = Convert.ToBase64String(System.Text.ASCIIEncoding.ASCII.GetBytes(authString));
			_request.Headers.Authorization = new AuthenticationHeaderValue("Basic", b64);

			// Initialize the body content.
			_fields = new List<KeyValuePair<string, string>>();

			// Add some generic values.
			this.AddField("laneid", "1");
			this.AddField("nsf", "yes");
			this.AddField("rcpt", "type=html");
			this.AddField("ordernum", DateTimeOffset.UtcNow.ToUnixTimeSeconds().ToString());
		}

		public void AddField(string key, string value)
		{
			_fields.Add(new KeyValuePair<string, string>(key, value));
		}

		public string GetField(string key)
		{
			foreach (KeyValuePair<string, string> field in _fields)
			{
				if (key == field.Key)
				{
					return field.Value;
				}
			}

			return null;
		}

		public bool Run()
		{
			// Add the body content.
			_request.Content = new FormUrlEncodedContent(_fields);

			// Run the transaction.
			var task = _client.SendAsync(_request, HttpCompletionOption.ResponseContentRead);

			// Save the response.
			var result = task.Result;
			var responseBody = result.Content.ReadAsStringAsync().Result;
			_response = JsonSerializer.Deserialize<Dictionary<string, string>>(responseBody);

			return result.IsSuccessStatusCode;
		}

		public bool Approved()
		{
			return String.Compare(_response["code"], "AUTH", true) == 0;
		}

		public Dictionary<string, string> Response()
		{
			return _response;
		}

		public HtmlString Receipt()
		{
			var html = new StringBuilder("", 400);

			// We want to add these fields to our receipt.
			List<string> keys = new List<string>()
			{
				"rcpt_cust_merch_info",
				"rcpt_cust_reference",
				"rcpt_cust_money",
				"rcpt_cust_disposition",
				"rcpt_cust_notice",
			};

			// Put together all the values for generating the HTML block.
			foreach (string key in keys)
			{
				if (_response.ContainsKey(key))
				{
					html.Append(_response[key]);
				}
			}

			return new HtmlString(html.ToString());
		}
	}
}
