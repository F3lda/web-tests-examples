<!doctype html>
<html>
    <head>
		<title>Facebook curl images feed</title>
		<script type="text/javascript">
		var addEvent = function(object, type, callback) {
			if (object == null || typeof(object) == 'undefined') return;
			if (object.addEventListener) {
				object.addEventListener(type, callback, false);
			} else if (object.attachEvent) {
				object.attachEvent("on" + type, callback);
			} else {
				object["on"+type] = callback;
			}
		};
		
		addEvent(window, "load", function(event) {
			console.log('load');
			//document.getElementById("loadIframe").src = "./FBimages/";
			
			/*setTimeout(function() {
			}, 1000);*/
			
			document.getElementById("loadImages").onclick = function() {
				document.getElementById("loadIframe").src = "api.php";
			};
		});
		
		addEvent(window, "message", function(event) {
			console.log('message');
			console.log(location.origin);
			console.log(event.origin);
			//if(event.origin === location.origin){ //check origin of message for security reasons
				var FBimages = event.data.split("<;;;>");
				document.getElementById("image").src = FBimages[0];
				alert(FBimages[0]);
				alert(event.data);
			/*} else {
				alert("Wrong origin!");
			}*/
		});
		</script>
	</head>
	<body>
		<button id="loadImages">load</button>
		<iframe id="loadIframe" src="" style="display: none;"></iframe><!-- style="display: none;" -->
		Loading...<br>
		<img id="image" src="">
    </body>	
</html>
