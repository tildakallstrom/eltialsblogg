"use strict";

//get buttons
const readMore = document.getElementsByClassName("readMore");

//eventlistener
for (let i = 0; i < readMore.length; i++) {

    readMore[i].addEventListener("click", function () {
        let textExpand = this.nextElementSibling;

        if (textExpand.style.display === "block") {
            textExpand.style.display = "none";

            this.setAttribute("aria-expanded", "false");

        } else {
            textExpand.style.display = "block";
            this.setAttribute("aria-expanded", "true");

        }

    })

}