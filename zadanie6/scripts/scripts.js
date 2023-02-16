function zobraz_vrstvu(skrytost){
    document.getElementById("modal_background").style.display=skrytost? "none": "block";
    document.getElementsByClassName("modal_div")[0].style.display=skrytost? "none": "flex";
}
function zobraz_vrstvu2(skrytost){
    document.getElementById("modal_background").style.display=skrytost? "none": "block";
    document.getElementsByClassName("modal_div2")[0].style.display=skrytost? "none": "flex";
}
function zobraz_vrstvu3(skrytost){
    document.getElementById("modal_background").style.display=skrytost? "none": "block";
    document.getElementsByClassName("modal_div3")[0].style.display=skrytost? "none": "flex";
}

