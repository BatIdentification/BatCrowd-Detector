<?php

  if(isset($_POST['num_calls'])){

    $fi = new FilesystemIterator(getcwd() . '/audiofiles', FilesystemIterator::SKIP_DOTS);
    $count = iterator_count($fi);
    echo('{"num_calls":' . $count . '}');

  }

?>
