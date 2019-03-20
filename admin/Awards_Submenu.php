<?
	class Awards_Submenu {
		private $view_main;
		private $view_routes;

		function __construct( $view_main, $view_hidden_routes, $serializer, $deserializer ) {
			$this->view_main = $view_main;
			$this->view_hidden_routes = $view_hidden_routes;
			$this->serializer = $serializer;
			$this->deserializer = $deserializer;
		}

		// Outputs our HTML based on the view file to
		public function render() {

			/**
			 * If we don't have a section specified in our url
			 * as to what page we'd like to display, then default to our $view_main
			 */

			if ( empty( $_GET['section'] ) )
			{
				$viewfile = $this->view_main;
			}
			else
			{
				echo $_GET['section'];
				$viewfile = $this->view_hidden_routes[$_GET['section']];
			}

			// Display our page.
			require_once( dirname(__FILE__) . '/views/' . $viewfile );
		}
	}
?>