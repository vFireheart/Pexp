<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Pexp - Very simple and small single PHP file browser">
    <meta name="author" content="Vryand Fireheart Monteybarra">
    <link rel="icon" href="./favicon.ico">

    <title>Pexp</title>
<?php
error_reporting(E_ERROR | E_PARSE);
$dir = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$dir = htmlspecialchars($_REQUEST['path']);
}else{
	$dir = getcwd();
}
$dir = ($dir != "") ? $dir : getcwd() ;

function folders($dir) {
   $result = array();
   try {
   	  $cdir = scandir($dir);
   } catch (Exception $e) {
	  return array($e->getMessage());
   }
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[] = $value;
         } 
      }
   }
   return $result;
}
function files($dir) {
   $result = array();
   try {
   	  $cdir = scandir($dir);
   } catch (Exception $e) {
	  return array($e->getMessage());
   }
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (!is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[] = $value;
         } 
      }
   }
   return $result;
}
function path_link($val) {
	return preg_replace('/([\\\])/', '${1}${1}', $val);
}
function sizeBytes($bytes, $precision = 2) { 
    $units = array('Bs', 'Kb', 'MB', 'GB', 'TB'); 
   
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
   
    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 
	
	$ret = number_format(round($bytes, $precision),0,",",",") ." ". $units[$pow];
   
    return ($ret != "0 Bs") ? $ret : "" ;
} 
function file_date($filename) {
	$ret = date("F d Y H:i:s", filemtime($filename));
	return ($ret != "January 01 1970 01:00:00") ? $ret : "" ;
}
$folders = folders($dir);
$files = files($dir);
?>

    <!-- Bootstrap core CSS -->
    <style type="text/css">
body {
  font-size: .875rem;
}

.feather {
  width: 16px;
  height: 16px;
  vertical-align: text-bottom;
}

/*
 * Sidebar
 */

.sidebar {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  z-index: 100; /* Behind the navbar */
  padding: 0;
  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar-sticky {
  position: -webkit-sticky;
  position: sticky;
  top: 48px; /* Height of navbar */
  height: calc(100vh - 48px);
  padding-top: .5rem;
  overflow-x: hidden;
  overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
}

.sidebar .nav-link {
  font-weight: 500;
  color: #333;
  text-overflow: ellipsis;
  overflow: hidden; 
  width: 160px; 
  white-space: nowrap;
}

.sidebar .nav-link .feather {
  margin-right: 4px;
  color: #999;
}

.sidebar .nav-link.active {
  color: #007bff;
}

.sidebar .nav-link:hover .feather,
.sidebar .nav-link.active .feather {
  color: inherit;
}

.sidebar-heading {
  font-size: .75rem;
  text-transform: uppercase;
}

/*
 * Navbar
 */

.navbar-brand {
  padding-top: .75rem;
  padding-bottom: .75rem;
  font-size: 1rem;
  background-color: rgba(0, 0, 0, .25);
  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
}

.navbar .form-control {
  padding: .75rem 1rem;
  border-width: 0;
  border-radius: 0;
  border-bottom: 1px solid;
  border-color: rgba(255, 255, 255, .1);
}

.form-control-dark {
  color: #fff;
  background-color: rgba(255, 255, 255, .1);
  border-color: rgba(255, 255, 255, .1);
}

.form-control-dark:focus {
  border-color: transparent;
  box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
}

/*
 * Utilities
 */

.border-top { border-top: 1px solid #e5e5e5; }
.border-bottom { border-bottom: 1px solid #e5e5e5; }
	</style>
    <script>
function show_path(val) {
	document.getElementById('path').value = val;
	document.getElementById('f').submit();
}
    </script>
    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Pexp</a>
      <form id="f" name="f" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="form-control form-control-dark w-100" style="margin:0;padding:0;" onSubmit="alert('go');">
      <input id="path" name="path" class="form-control form-control-dark w-100" type="text" placeholder="Path" aria-label="Path" value="<?php echo $dir; ?>">
      </form>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="https://github.com/vFireheart/Pexp/" target="_blank">.</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);">
                  <span></span>
                  Folders <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" onclick="show_path('<?php echo path_link(dirname($dir)); ?>');">
                  <span data-feather="folder"></span>
                  ..
                </a>
              </li>
<?php if(false){ ?>
              <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);">
                  <span data-feather="layers"></span>
                  Drives
                </a>
              </li>
<?php } ?>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>SubFolders</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <?php 
			  //print_r($folders);
			  foreach ($folders as $key => $value)
			  {
			  ?>
              <li class="nav-item" title="<?php echo $value; ?>">
                <a class="nav-link" href="javascript:void(0);" onclick="show_path('<?php echo path_link($dir . DIRECTORY_SEPARATOR . $value); ?>');">
                  <span data-feather="folder"></span>
                  <?php echo $value; ?>
                </a>
              </li>
              <?php 
			  }
			  ?>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
<?php if(false){ ?>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Main</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Share</button>
                <button class="btn btn-sm btn-outline-secondary">Export</button>
              </div>
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
              </button>
            </div>
          </div>

          <canvas class="my-4" id="main" width="0" height="0"></canvas>
<?php } ?>

          <h2>Files</h2>
<?php //print_r($_POST); ?>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>File</th>
                  <th>Type</th>
                  <th align="center">Size</th>
                  <th align="center">Date</th>
                </tr>
              </thead>
              <tbody>
              <?php 
			  //print_r($files);
			  foreach ($files as $key => $value)
			  {
				  //$file = explode(".", $dir . DIRECTORY_SEPARATOR . $value);
			  ?>
                <tr>
                  <td><span data-feather="file"></span></td>
                  <td><?php echo pathinfo($dir . DIRECTORY_SEPARATOR . $value, PATHINFO_FILENAME) ?></td>
                  <td><?php echo pathinfo($dir . DIRECTORY_SEPARATOR . $value, PATHINFO_EXTENSION); ?></td>
                  <td align="right"><?php 
   $fsize = 0;
   try{ 
   	  $fsize = filesize($dir . DIRECTORY_SEPARATOR . $value); 
   } catch (Exception $e) {
	  echo $e->getMessage();
   }
   echo sizeBytes($fsize); 
				   ?></td>
                  <td align="right"><?php 
   $fdate = "";
   try{ 
   	  $fdate = file_date($dir . DIRECTORY_SEPARATOR . $value);
   } catch (Exception $e) {
	  $fdate = $e->getMessage();
   } 
   echo $fdate;
   ?></td>
                </tr>
              <?php 
			  }
			  ?>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace();
    </script>
  </body>
</html>