namespace TicketApp.Models
{
	public class ServerConfig
	{
		// These are used to connect to the payment server (either Monetra or TranSafe).
		public string Host { get; set; }
		public string Port { get; set; }

		// These are the credentials of the user who will request the iframe and create the
		// CardShield ticket.
		public string Username { get; set; }
		public string Password { get; set; }
	}
}
