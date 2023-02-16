function zoradenie(stlpec,typ,typ_hodnoty) {
    let tabulka, riadky, switching, i, x, y, shouldSwitch, r1, r2;
    tabulka = document.getElementById("tabulka");
    switching = true;
    while (switching) {
        switching = false;
        riadky = tabulka.rows;
        for (i = 1; i < (riadky.length - 1); i++) {
            shouldSwitch = false;
            x = riadky[i].getElementsByTagName("td")[stlpec];
            y = riadky[i + 1].getElementsByTagName("td")[stlpec];
            if (typ_hodnoty===0||typ_hodnoty===2){
                if ((x.textContent.toLowerCase() > y.textContent.toLowerCase())&&typ===false) {
                    shouldSwitch = true;
                    break;
                }
                else if ((x.textContent.toLowerCase() < y.textContent.toLowerCase())&&typ===true) {
                    shouldSwitch = true;
                    break;
                }
                else if ((x.textContent.toLowerCase() === y.textContent.toLowerCase())){
                    if (typ_hodnoty===2){
                        r1 = riadky[i].getElementsByTagName("td")[2];
                        r2 = riadky[i + 1].getElementsByTagName("td")[2];
                        if ((parseInt(r1.textContent, 10) > parseInt(r2.textContent, 10))&&typ===false) {
                            shouldSwitch = true;
                            break;
                        }
                        if ((parseInt(r1.textContent, 10) < parseInt(r2.textContent, 10))&&typ===true) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
            }
            if (typ_hodnoty===1){
                let p,d;
                p=x.textContent;
                d=y.textContent;
                if ((parseInt(p, 10) > parseInt(d, 10))&&typ===false) {
                    shouldSwitch = true;
                    break;
                }
                if ((parseInt(p, 10) < parseInt(d, 10))&&typ===true) {
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
function zobraz_vrstvu(skrytost){
    document.getElementById("modal_background").style.display=skrytost? "none": "block";
    document.getElementsByClassName("modal_div")[0].style.display=skrytost? "none": "flex";
}
function zobraz_vrstvu2(skrytost){
    document.getElementById("modal_background2").style.display=skrytost? "none": "block";
    document.getElementsByClassName("modal_div2")[0].style.display=skrytost? "none": "flex";
}