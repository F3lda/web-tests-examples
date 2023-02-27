<!doctype html>
<html>
<head>
	<meta charset='UTF-8' />
	<title>WebSocket chat</title>
	<style>
		input, textarea {border:1px solid #CCC;margin:0px;padding:0px}

		#body {max-width: 800px; margin: auto; text-align: center; font-family: "Open Sans";}
		#log {height: 400px; width: calc(100% - 2px);}
		#send {margin-left: 0px;}
		.row-line {
			display: flex;
			width: 100%;
			padding: 0em 0em;
			margin: 0 0 1em 0;
		}
		.row-line-maxwidth {
			flex-grow: 1;
			line-height: 20px
		}
		.row-line-normal {
			margin-left: 10px;
			padding: 0 1em;
		}
		
		#webSocketSupp {
			width: calc(100% - 64px);
			display: none;
			border: 2px solid #060;
			background-color: #F0FFF0;
			margin: 20px;
			margin-top: 5px;
			padding: 10px;
			font-size: 16px;
			color: #0d8900;
		}
		
		#noWebSocketSupp {
			width: calc(100% - 64px);
			display: none;
			border: 2px solid #cb0000;
			background-color: #FFE1E1;
			margin: 20px;
			margin-top: 5px;
			padding: 10px;
			font-size: 16px;
			color: #800000;
		}
		
		#loaderWebSocketSupp {
			width: calc(100% - 64px);
			display: table;
			border: 2px solid orange;
			background-color: #ffdda0;
			margin: 20px;
			margin-top: 5px;
			padding: 10px;
			font-size: 16px;
			color: orange;
		}
		
		.loader {
			border: 6px solid #f3f3f3;
			border-radius: 50%;
			border-top: 6px solid red;
			border-right: 6px solid orange;
			border-bottom: 6px solid green;
			width: 30px;
			height: 30px;
			-webkit-animation: spin 1s linear infinite; /* Safari */
			animation: spin 1s linear infinite;
		}

		/* Safari */
		@-webkit-keyframes spin {
			0% { -webkit-transform: rotate(0deg); }
			100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>
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
		
		addEvent(window, "keyup", function(event) {
			if(document.getElementById("message") === document.activeElement && event.keyCode == 13){
				console.log("send");
				sendMessage();
			}
		});
		
		addEvent(window, "load", function(event) {
			console.log('load');
			document.getElementById("message").focus();
			
			supportsWebSockets = 'WebSocket' in window || 'MozWebSocket' in window;
			if (supportsWebSockets) {
				document.getElementById("loaderWebSocketSupp").style.display = "none";
				document.getElementById("webSocketSupp").style.display = "table";
			} else {
				document.getElementById("loaderWebSocketSupp").style.display = "none";
				document.getElementById("noWebSocketSupp").style.display = "table";
			}
			
			document.getElementById("startServerButton").onclick = function() {
				if (socket == null) {
					addLog("Starting server...");
					serverAttempts = 0;
					serverStart = true;
					
					document.getElementById("startServerFrame").src = "./server.php";

					setTimeout(function() {
						connect();
					}, 1000);
				}
			};
			
			document.getElementById("stopServerButton").onclick = function() {
				try {
					if(socket != null){
						addLog("Stopping server...");
						socket.send("<serverMessage>stop_server");
					}
				} catch(ex) { 
					console.log(ex); 
				}
			};
			
			document.getElementById("connectChatButton").onclick = function() {
				if (socket == null) {
					addLog("Connecting...");
					connect();
				}
			};
			
			document.getElementById("quitChatButton").onclick = function() {
				if (socket != null) {
					addLog("Goodbye!");
					socket.close();
					socket = null;
					addLog("Disconnected.");
				}
			};
			
			document.getElementById("send").onclick = function() {
				sendMessage();
			};
		});
		
		serverStart = false;
		serverAttempts = 0;
		
		socket = null;
		serverIP = "ws://<?php echo getHostByName(getHostName()); ?>/ws";
		
		function connect(){
			try {
				socket = new WebSocket(serverIP);
				socket.onopen = function(msg) { 
					console.log("Welcome - status "+this.readyState);
					if(serverStart){
						document.getElementById("startServerFrame").src = "/";
						addLog("Server started!");
						serverStart = false;
					}
					document.getElementById("message").focus();
					addLog("Connected.");
					addLog("Welcome!");
				};
				socket.onmessage = function(msg) {
					var messageStart = (msg.data).indexOf('"');
					if((msg.data).indexOf("<message>") == messageStart+1){
						addLog((msg.data).replace("<message>",""));
					} else {
						addLog(msg.data);
					}
				};
				socket.onclose = function(msg) { 
					console.log("Disconnected - status "+this.readyState);
					if(serverStart){
						if(serverAttempts < 5){
							setTimeout(function() {
								connect();
							}, 1000);
							serverAttempts++;
						} else {
							addLog("Server not responding!");
							serverStart = false;
						}
					}
					if(socket != null){
						addLog("Connection lost!");
						addLog("Disconnected.");
					}
					socket = null;
				};
				socket.onerror = function(msg) { 
					console.log("Error - status "+this.readyState);
					if(socket != null) {
						addLog("Can't connect to server!");
					}
					socket.close();
					socket = null;
				};
			}catch(ex){
				console.log("ERROR: "+ex); 
			}
		}
		
		function sendMessage() {
			try {
				if(socket != null && document.getElementById("message").value != ""){
					socket.send("<message>"+document.getElementById("message").value);
					addLog("Sent: "+document.getElementById("message").value);
					document.getElementById("message").value = "";
					document.getElementById("message").focus();
				}
			} catch(ex) { 
				console.log(ex); 
			}
		}
		
		function addLog(value){
			if(document.getElementById("log").value == ""){
				document.getElementById("log").value += value;
			} else {
				document.getElementById("log").value += "\n"+value;
			}
			document.getElementById("log").scrollTop = document.getElementById("log").scrollHeight - document.getElementById("log").clientHeight;
		}
	</script>
</head>
<body>
	<div id='body'>
		<h1>Websocket chat - <?php echo getHostByName(getHostName()); ?></h1>
		<textarea id='log' readonly='readonly'></textarea>
		<div class="row-line">
			<input class="row-line-maxwidth" id='message' type="text">
			<button id='send'>send</button>
			<button class="row-line-normal" id="connectChatButton">connect</button>
			<button class="row-line-normal" id="quitChatButton">quit</button>
			<button class="row-line-normal" id="startServerButton">Start server</button>
			<button class="row-line-normal" id="stopServerButton">Stop server</button>
		</div>
		<div id="webSocketSupp">
			<div style="float: left;">
				<img src="img/check.png" width="36" height="36" align="absmiddle">
			</div>
			<div style="float: left; width: 600px; margin-left: 10px; position:relative; top: 8px;">
				This browser supports WebSocket.
			</div>
			<div class="clearfix"></div>
        </div>
		<div id="noWebSocketSupp">
			<div style="float: left;">
				<img src="img/cross.png" width="36" height="36" align="absmiddle">
			</div>
			<div style="float: left; width: 600px; margin-left: 10px;">
				<p>Uh-oh, the browser you're using doesn't have native support for WebSocket. That means you can't run this demo.</p>
				<p>The following link lists the browsers that support WebSocket:</p>
				<p><a href="http://caniuse.com/#feat=websockets">http://caniuse.com/#feat=websockets</a></p>
			</div>
			<div class="clearfix"></div>
		</div>
		<div id="loaderWebSocketSupp">
			<div style="float: left;">
				<div class="loader"></div>
			</div>
			<div style="float: left; width: 600px; margin-left: 10px; position:relative; top: 8px;">
				Checking WebSocket support...
			</div>
			<div class="clearfix"></div>
		</div>
		<iframe id="startServerFrame" src="/" style="display: none;"></iframe>
	</div>
</body>
</html>