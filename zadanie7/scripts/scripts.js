function kliknutie(codee,countryy){
    $.ajax({
        type: "post",
        url: "country_info.php",
        data:{
            code:codee,
            country:countryy,
        },
        success:function (data) {
            document.getElementById("modal_background2").style.display="block";
            document.getElementsByClassName("modal_div2")[0].style.display="flex";
            $('#modal_vrstva2').html(data);
        }

    });
}

function nonaccept(){
    document.getElementById('modal_vrstva').innerHTML="Nemôžete pokračovať v zobrazení obsahu stránky";
}

function zobraz_vrstvu(){
    document.getElementById("modal_background2").style.display="none";
    document.getElementsByClassName("modal_div2")[0].style.display="none";
}



