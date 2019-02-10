<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapseable">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">BatPi</a>
    </div>
    <div class="collapse navbar-collapse" id="collapseable">
      <?php if(isset($audio)): ?>
      <ul class="nav navbar-nav side-nav">
        <h4>Audio files</h3>
        <?php
          $files = scandir(getcwd() . '/audiofiles', SCANDIR_SORT_DESCENDING);
          foreach($files as $key => $value){
            if(strpos($value, ".wav") !== false && $value != "liveSpec.wav"){
              echo("<li><a class='audiofile' href='audiofiles/{$value}' download>{$value}</a></li>");
            }
          }
        ?>
      </ul>
      <?php endif; ?>
      <ul class="nav navbar-nav">
        <li><a id="shutdown">Shutdown</a></li>
        <li><a id="settings" href="settings.php">Settings</a></li>
        <li><a href="batidentification.php">BatIdentification</a></li>
      </ul>
    </div>
  </div>
</nav>
