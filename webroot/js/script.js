$(document).ready(function() {
    var searchElement = document.getElementById("search");
    if (searchElement)
        searchElement.addEventListener("search", function(event) {
            if (!event.target.value)
                window.location.replace(location.protocol + '//' + location.host + location.pathname);
        });
});