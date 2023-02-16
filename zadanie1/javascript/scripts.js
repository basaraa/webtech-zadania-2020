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
                if ((x.textContent.toLowerCase() > y.textContent.toLowerCase())&&typ===false) {
                    shouldSwitch = true;
                    break;
                }
                if ((x.textContent.toLowerCase() < y.textContent.toLowerCase())&&typ===true) {
                    shouldSwitch = true;
                    break;
                }
            }
            if (typ_hodnoty===1){
                let p,d;
                p=x.textContent;
                d=y.textContent;
                if (!(p)){
                    p="0";
                }
                if (!(d)){
                    d="0";
                }
                if ((parseInt(p, 10) > parseInt(d, 10))&&typ===false) {
                    shouldSwitch = true;
                    break;
                }
                if ((parseInt(p, 10) < parseInt(d, 10))&&typ===true) {
                    shouldSwitch = true;
                    break;
                }
            }
            if (typ_hodnoty===2){
                let p,d;
                p=x.textContent;
                d=y.textContent;
                if (!(p)){
                    p="26 February 1970, 10:47:10";
                }
                if (!(d)){
                    d="26 February 1970, 10:47:10";
                }
                if ((new Date(p) - new Date(d)>0)&&typ===false) {
                    shouldSwitch = true;
                    break;
                }
                if ((new Date(p) - new Date(d)<0)&&typ===true) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            riadky[i].parentNode.insertBefore(riadky[i + 1], riadky[i]);
            switching = true;
        }
    }
}