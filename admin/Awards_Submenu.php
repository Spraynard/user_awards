<?
	class Awards_Submenu {
		private $view_main;
		private $view_routes;
		public $db;

		function __construct( $view_main, $view_hidden_routes, $serializer, $deserializer ) {
			$this->view_main = $view_main;
			$this->view_hidden_routes = $view_hidden_routes;
			$this->serializer = $serializer;
			$this->deserializer = $deserializer;
		}

		// Outputs our HTML based on the view file to
		public function render() {
			// Obtaining the submenu page to render from our "page" value on the admin window.
			$section = $_GET['section'];

			if ( empty( $section ) )
			{
				$viewfile = $this->view_main;
			}
			else
			{
				$viewfile = $this->view_hidden_routes[$section];
			}

			require_once( dirname(__FILE__) . '/views/' . $viewfile );
		}
	}
?>