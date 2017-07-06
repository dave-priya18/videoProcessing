<!DOCTYPE html>
<html>
<head>
  <title>Page</title>
  <link rel="stylesheet" type="text/css" href="bootstrap.css">
  <link rel="stylesheet" type="text/css" href="page.css">
</head>
<body>  
      <nav class="navbar navbar-toggleable-md navbar-light bg-faded navbar-fixed-top" >
        <img class="logo" src="image/logo.png" >
        <button id="signup" class="btn btn-secondary">Sign Up</button>
        <ul class="nav navbar-nav navigation">
          <li id="li">
            <a id="hello" href="">How it works</a>
          </li>
          <li id="li">
            <a id="hello" href="">Demo</a>
          </li>
        <li id="li">
          <a id="hello" href="">Testimonials</a>
        </li>   
        <li id="li">
          <a id="hello" href="">FAQs</a>
        </li>     
        <button id="signin" class="btn btn-secondary">Sign In</button>    
      </ul>
    </div>
    </nav>
    <hr>
  <div class="container">
      <form method="post" enctype="multipart/form-data">
    <div class="videoBrowser">
    <label class="label label-default">Select video file from your Directory</label>
    <br><br>
    <label for="file">Filename:</label>  
    <input type="file" name="file" id="file"></input><br />  
    <input type="submit" name="submit" value="upload" onclick="Submitpress(); onGoPressed(); onpost();" ></input>  
    </form>
  </div>
<?php
    if(isset($_FILES['file'])){
        $file = $_FILES['file'];
        // print_r($file);
    //file properties
    $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
    //work out the file extension
        $file_ext = explode('.',$file_name);
        $file_ext = strtolower(end($file_ext));
    $allowed = array('txt', 'jpg', 'mp4','flac');
    if(in_array($file_ext, $allowed)){
            if($file_error === 0) {
                if($file_size <= 209715299 * 9999){
          echo $file_name_new = $file['name'];
                  $file_destination = 'upload/' . $file_name_new;
          if(move_uploaded_file($file_tmp, $file_destination)){
            echo $file_destination;
                    }
                }
            }
        }

    }
?>
    
     <?php
          // echo shell_exec("ffmpeg -i /home/bite-three/Documents/Videos/11.mp4 -c:a flac /home/bite-three/Documents/Videos/11.flac" );
    ?>
  <br>
  <br>
  <div id="inputText">
    <form  action="page1.php" method='POST'>
    <h3><label class="label label-default">Input your text</label></h3>
      <div id="aa">
        <textarea id="inputTextt" class="textarea_input" name="t1" rows="3" placeholder="input text here..">
        <?php
        $ex = shell_exec(' curl -X POST -u 72966abf-5ba2-4182-811d-b8035d49b9fd:Nu7mAx04Bmtz --header "Content-Type: audio/flac" --header "Transfer-Encoding: chunked" --data-binary @/home/bite-three/Documents/Videos/11.flac "https://stream.watsonplatform.net/speech-to-text/api/v1/recognize" ');
          echo "$ex";
        ?>        
        </textarea>
      </div>
      <!-- <button onclick="onGoPressed()" class="btn btn-go btn-sm">GO</button> -->
<!-- <script type="text/javascript">
        function onGopressed(){
        var name = $('#inputTextt').value;
        $post('bluemix.php', {postname:name}
        function(data){
        $('#aa').html(data);
            });
        }
      </script> -->
    </form>
    </div>

    <div id="brand">
    <h4><label class="label label-primary">Brand Names</label></h4>
      <?php
          $name = "";
          if(isset($_POST['t1']))
          $ex = $_POST['t1'];
      ?>
      <textarea id="mytext" disabled="disable" readonly="" 
      class="textarea_brand" name="t2" rows="3">
      <?php
      $brand = file_get_contents('BRANDS.json');
      $json = json_decode($brand, true);
      // print_r((array) json_decode($brand));  //prints the decoded data
      foreach ($json as $key => $item)
    {
        if ($item['Names']) 
        {
             $data[] = $item['Names'];
        }
    }
    
    // for($i=0;$i<sizeof($data);$i++)
          // {
            if(in_array($ex, $data))
              { 
                echo $data."\n";
              } 
          // }
  
    ?> 
     <?php
      echo "$ex";
  ?> 
      </textarea>  
      </div>    
</body>
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
    crossorigin="anonymous"></script>
    <script>
    function onGoPressed()
    {        
      var newValue = document.getElementById("mytext").value;
        if(!localStorage.getItem("brands1"))
        {
            localStorage.setItem("brands1","[]");
        }
      var list = JSON.parse(localStorage.getItem("brands1"));
      var exist = false;
        for(var i = 0;i<list.length;i++)
            if(list[i] == newValue)
            {
                exist=true;
                break;
            }
          if(!exist)list.push(newValue);
          else{
              console.log("EXIST");
          }
    
         localStorage.setItem("brands1",JSON.stringify(list));      
    }
    </script>
</html>