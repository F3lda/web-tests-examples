<pre id="output"></pre>

<script>
const evtSource = new EventSource("server.php");

var output = document.getElementById('output');


evtSource.onopen = function () {
	output.appendChild(document.createElement('hr'));

	return;
};

evtSource.onmessage = function(event) {
  const newElement = document.createElement("li");

  newElement.textContent = "message: " + event.data;
  output.appendChild(newElement);
}


evtSource.addEventListener("ping", function(event) {
  const newElement = document.createElement("li");
  const time = JSON.parse(event.data).time;
  newElement.textContent = "ping at " + time + " (length: "+event.data.length+")";
  output.appendChild(newElement);
});

evtSource.onerror = function(err) {
  console.error("EventSource failed:", err);
};

//evtSource.close();


if(typeof(EventSource) !== "undefined") {
  // Yes! Server-sent events support!
  // Some code.....
} else {
  // Sorry! No server-sent events support..
}
</script>

<script>/*
var xhr = new XMLHttpRequest();
xhr.open('GET', 'server.php');
xhr.seenBytes = 0;

xhr.onreadystatechange = function() {
  console.log("state change.. state: "+ xhr.readyState);

  if(xhr.readyState == 3) {
    var newData = xhr.response.substr(xhr.seenBytes);
    console.log("newData: <<" +newData+ ">>");
    document.body.innerHTML += "New data: <<" +newData+ ">><br />";

    xhr.seenBytes = xhr.responseText.length;
    console.log("seenBytes: " +xhr.seenBytes);
  }
};

xhr.addEventListener("error", function(e) {
  console.log("error: " +e);
});

console.log(xhr);
xhr.send();*/
</script>