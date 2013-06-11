<?php
/*
 * Date: 2011/06/27
 * Time: 4:33 PM
 */
Twig_Autoloader::register();
class template {
	private $config = array(), $vars = array();

	function __construct($template, $folder = "", $strictfolder = false) {
		$this->f3 = Base::instance();
		$this->config['cache_dir'] = $this->f3->get('TEMP');

		$this->vars['folder'] = $folder;
		$this->config['strictfolder'] = $strictfolder;

		$this->template = $template;






	}
	function __destruct(){
		$page = $this->template;
		//test_array($page);


	}

	public function __get($name) {
		return $this->vars[$name];
	}

	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	private function default_vars(){







	}


	public function load() {






		return $this->render_template();




	}

	public function render_template() {
		$this->default_vars();
		if (is_array($this->vars['folder'])){
			$folder = $this->vars['folder'];
		} else {
			$folder = array(
				"ui/",
				$this->vars['folder']
			);
		}

		if (isset($this->vars['page'])){
			if (isset($this->vars['page']['template'])){

				$folders = $folder;
				$tfile = $this->vars['page']['template'];
				$tfile = explode(".", $tfile);
				$tfile = $tfile[0];

				foreach ($folders as $f) {

					if (file_exists('' . $f . '' . $tfile . '.tmpl')) {
						if (file_exists('' . $f . '_js/' . $tfile . '.js')) {
							$this->vars['page']['template_js'] = '/' . $f . '_js/' . $tfile . '.js';
						}
						if (file_exists('' . $f . '_css/' . $tfile . '.css')) {
							$this->vars['page']['template_css'] = '/' . $f . '_css/' . $tfile . '.css';
						}
						if (file_exists('' . $f . 'templates/' . $tfile . '.jtmpl')) {
							$this->vars['page']['template_jtmpl'] = '/'  . 'templates/' . $tfile . '.jtmpl';
						}
						break;


					}








				}
			}
			//test_array($this->vars['page']);
		}



		if ($this->config['strictfolder']){
			$folder = $this->vars['folder'];
		}

		$loader = new Twig_Loader_Filesystem($folder);

		$options = array();

		$options['debug'] = true;

		$base64_encode = new Twig_SimpleFilter('base64_encode', function ($string) {
			return base64_encode($string);
		});
		$base64_decode = new Twig_SimpleFilter('base64_decode', function ($string) {
			return base64_decode($string);
		});
		$url_decode = new Twig_SimpleFilter('url_decode', function ($string) {
			return urldecode($string);
		});



		$twig = new Twig_Environment($loader, $options);
		$twig->addFilter($base64_encode);
		$twig->addFilter($base64_decode);
		$twig->addFilter($url_decode);
		$twig->addExtension(new Twig_Extension_StringLoader());
		$twig->addExtension(new Twig_Extension_Debug());


		//test_array($this->vars);

		return $twig->render($this->template, $this->vars);


	}

	public function render_string() {
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader);

		return $twig->render($this->vars['template'], $this->vars);
	}


	public function output() {
		$this->f3->set("__runTemplate", true);
		echo $this->load();

	}

}
