var scrollCurrentPosition = 0;
var scrollTimeout = null;

document.addEventListener("wheel", function(event) {

    
    var currentdate = new Date(); 
var datetime = "Last Sync: " + currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds() + ":"
                + currentdate.getMilliseconds();

    console.log(datetime + " - " +event.deltaY);
    //console.log(event);

    if (Math.sign(event.deltaY) == 1) {// down
        if (scrollCurrentPosition < document.getElementsByClassName("content_block").length-1) {
            scrollCurrentPosition++;
        } else {
            scrollCurrentPosition = document.getElementsByClassName("content_block").length-1;
        }
    } else {// up
        if (scrollCurrentPosition > 0) {
            scrollCurrentPosition--;
        } else {
            scrollCurrentPosition = 0;
        }
    }
    document.getElementsByClassName("content_block")[scrollCurrentPosition].scrollIntoView({behavior: "smooth"});

    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(function() {
        // scroll ended - finish scrolling
        document.getElementsByClassName("content_block")[scrollCurrentPosition].scrollIntoView({behavior: "smooth"});
    }, 100);

    // disable default scroll
    event.preventDefault();
    event.stopImmediatePropagation();

},{ passive: false }); // passive false to use preventDefault();
