<?php

  class Templater{

    function __construct($file){

      $this->template_file = $file;

    }

    function render($variables){

      ob_start();

      include $this->template_file;
	    $template = ob_get_contents();
	    @ob_end_clean();

      foreach($variables as $key => $value){

        $template = str_replace("{{ ${key} }}", $value, $template);

      }

      echo($template);

    }

  }

?>
