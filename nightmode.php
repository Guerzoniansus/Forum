<?php
/*
TO ADD TO PAGE:

<?php include($_SERVER['DOCUMENT_ROOT']).'/nightmode.php'; ?>

*/
?>

<p style="font-size: 32px; margin-bottom: 5px;"><a href="/index.php">Index</a></p>
<button type="button" style="margin-bottom: 30px" onclick="toggleNight()">Night mode</button>

<script>
    if (localStorage.getItem("nightmode") == null || undefined) {
        localStorage.setItem("nightmode", false);
    }

    // Change the color on page load after reading session storage
    setTheme();

    // Toggle the variable
    function toggleNight() {
        localStorage.setItem("nightmode", localStorage.getItem("nightmode") == "true" ? false : true );
        setTheme();

    }

    // Actually change the colors
    function setTheme() {
        if (localStorage.getItem("nightmode") == "true") {
            document.getElementsByTagName("BODY")[0].setAttribute("style", "background-color: dimgrey; color: white;");
        }
        else {
            document.getElementsByTagName("BODY")[0].setAttribute("style", "background-color: white; color: black;");
        }
    }

</script>