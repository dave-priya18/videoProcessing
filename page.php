
<!DOCTYPE html>
<html>
<head>
	<title> AutoBrand </title>
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
		</ul>	
		<button id="signin" class="btn btn-secondary">Sign In</button>		

	</nav>

	<hr>
	<div class="container">
		<form method="post" enctype="multipart/form-data">
			<div class="videoBrowser">
				<label class="label label-default">Select video file from your Directory</label><br><br>
				<label for="file">Filename:</label>  
				<input type="file" name="file" id="file"></input><br />  
				<input type="submit" name="submit" value="upload" onclick="Submitpress(); onGoPressed(); onpost();"></input>  
			</div>
		</form>


		<?php
		$ex = '';
		$data = [];
		if(isset($_FILES['file'])){
			$file = $_FILES['file'];
			//print_r($file);

//file properties

			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];


//work out the file extension
			$file_ext = explode('.',$file_name);
			$file_ext = strtolower(end($file_ext));

//$allowed = array('txt', 'jpg', 'mp4','mp3');
//Uploading file to /home/bite-five/Documents/Videos/{VideoName_Folder}

			if($file_error === 0) {
				if($file_size <= 209715299 * 99990000000000000000000000000000){
					$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);

					$file_path = '/home/bite-five/Documents/Videos/'.$withoutExt.'/';

					mkdir($file_path);
					$file_name_new = $file['name'];
					$file_destination = $file_path . $file_name_new;
					if(move_uploaded_file($file_tmp, $file_destination)){

						echo "File is Uploaded";

					}
				}
			}

//conversion of .mp4 to .flac using ffmpeg

			$file_flac = $withoutExt .'.flac';

			$file_flac_destination = $file_path.$file_flac;

			shell_exec("ffmpeg -i $file_destination -c:a flac $file_flac_destination");

//taking snap from .mp4 using ffmpeg
//Picture resolution size
			$size = "1080x720";
			for($num = 1; $num <= 10; $num++)
			{
				$file_image = $withoutExt.$num . '.jpg';

				$file_image_destination =$file_path.$file_image;
				$interval = $num * 10;

				shell_exec("ffmpeg -i $file_destination -an -ss $interval -s $size $file_image_destination");

			}
//converting audio file to text file.

			$ex = shell_exec('curl -X POST -u USERNAME:PASSWORD --header "Content-Type: audio/flac" --header "Transfer-Encoding: chunked" --data-binary @'.$file_flac_destination.' "https://stream.watsonplatform.net/speech-to-text/api/v1/recognize"');

//saving the transcript file.

			$file_txt = $file_path.$withoutExt.'.txt';
			$fh = fopen($file_txt, 'w') or die("can't open file");
			fwrite($fh, $ex);
			fclose($fh);

//fetching the brand names from localstorage and mapping them to transcript

			$brand = file_get_contents('BRANDS.json');
			$json = json_decode($brand, true);
			// print_r((array) json_decode($brand));  //prints the decoded data
			foreach ($json as $key => $item){
				if ($item['Names']) {
					$data[] = $item['Names'];
				}
			}	
		}
		?>

	<div id="inputText">
		<form  action="page.php" method='POST'>
			<h3><label class="label label-default">Input your text</label></h3>
			<div>
				<textarea  class="textarea_input" name="t1" rows="3" placeholder="input text here..">
					<?php
					
						echo "$ex";
					?> 
				</textarea>
			</div>
				
		</form>
	</div>

	<div id="brand">
		<h4><label class="label label-primary">Brand Names</label></h4>
			<?php
				if(isset($_POST['t1']))
					$ex = $_POST['t1'];
			?>
			<textarea id="mytext" disabled="disable" readonly="" 
			class="textarea_brand" name="t2" rows="3">
				<?php
					for($i=0;$i<sizeof($data);$i++){
						if(substr_count($ex, $data[$i])>0){	
							echo $data[$i]."\n";
						}	
					}	
				?> 
			</textarea>  
	</div>		  
	<script>
		function onGoPressed()
		{

			var newValue = document.getElementById("mytext").value;
    		if(!localStorage.getItem("brands1")){
        		localStorage.setItem("brands1","[]");
    		}
    		var list = JSON.parse(localStorage.getItem("brands1"));
    		var exist = false;
    		for(var i = 0;i<list.length;i++){
        		if(list[i] == newValue){
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
	</div>
</body>
</html>
