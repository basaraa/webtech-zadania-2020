function insertt(){
    document.getElementById("modal_background2").style.display= "block";
    document.getElementsByClassName("modal_div2")[0].style.display="flex";
    fetch('insertDB.php').then(
        function (odpoved){
            if (odpoved.status!==200){
                console.log('Nastala chyba:'+odpoved.status);
                return;
            }
            odpoved.json().then(function(data){
                location.reload();
                return false;
            });
        })
        .catch(function(err){
            console.log('Fetch Error',err);
         })

}
function zoradenie(stlpec,typ,typ_hodnoty) {
    let tabulka, riadky, switching, i, x, y, shouldSwitch;
    tabulka = document.getElementById("tabulka");
    switching = true;
    while (switching) {
        switching = false;
        riadky = tabulka.rows;
        for (i = 1; i < (riadky.length - 1); i++) {
            shouldSwitch = false;
            x = riadky[i].getElementsByTagName("td")[stlpec];
            y = riadky[i + 1].getElementsByTagName("td")[stlpec];
            if (typ_hodnoty===0){
                shouldSwitch=namesorts(x,y,typ);
                if (shouldSwitch)
                    break;
            }
            if (typ_hodnoty===1){
                let p,d,pp,dd;
                p=riadky[i].getElementsByTagName("td")[0];
                d=riadky[i + 1].getElementsByTagName("td")[0];
                pp=x.textContent.toLowerCase();
                dd=y.textContent.toLowerCase();
                if ((parseInt(pp, 10) > parseInt(dd, 10))&&typ===false) {
                    shouldSwitch = true;
                    break;
                }
                if ((parseInt(pp, 10) < parseInt(dd, 10))&&typ===true) {
                    shouldSwitch = true;
                    break;
                }
                if ((parseInt(pp, 10) === parseInt(dd, 10))){
                    shouldSwitch=namesorts(p,d,typ);
                    if (shouldSwitch)
                        break;
                }
             }
        }
        if (shouldSwitch) {
            riadky[i].parentNode.insertBefore(riadky[i + 1], riadky[i]);
            switching = true;
        }
    }
    function namesorts(x,y,typ){
        let priezvisko1=x.textContent.toLowerCase().split(" ");
        let l1=priezvisko1.length-1;
        let priezvisko2=y.textContent.toLowerCase().split(" ");
        let l2=priezvisko2.length-1;
        if ((priezvisko1[l1] > priezvisko2[l2])&&typ===false) {
            shouldSwitch = true;
        }
        else if ((priezvisko1[l1] < priezvisko2[l2])&&typ===true) {
            shouldSwitch = true;
        }
        else
            shouldSwitch=false;
        return shouldSwitch;
    }
}
function kliknutie(id,menoo){
    $.ajax({
        type: "post",
        url: "informacie.php",
        data:{
            idcko:id,
            meno:menoo
        },
        success:function (data) {
            document.getElementById("modal_background").style.display="block";
            document.getElementsByClassName("modal_div")[0].style.display="flex";
            $('#modal_vrstva').html(data);
        }

    });
}
function zobraz_vrstvu(){
    document.getElementById("modal_background").style.display="none";
    document.getElementsByClassName("modal_div")[0].style.display="none";
}




