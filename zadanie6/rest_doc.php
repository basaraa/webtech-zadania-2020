<div>
    <h2 class="greenc midd">Dokumentácia k RESTu</h2>
    <p class="coll">Hlavička:</p> Content-Type: application/json
    <p class="coll">Na základe zadaného dátumu získať informáciu, kto má v daný deň meniny:</p>

    <ul>
        <li>Základné informácie:
            <ul>
                <li>Reaguje na metódu GET.</li>
                <li>Je nutné zadať parametre pre typ(datum), datum(nejaký platný dátum) a kód krajiny(SK, CZ, PL, HU, AT).</li>
            </ul>
        </li>
        <li>Príklad príkazu curl:
            <ul>
                <li>curl -X GET 'https://wt116.fei.stuba.sk/WebTt2zadania/zadanie6/api/?typ=datum&datum=11.11.&kod=CZ'</li>
            </ul>
        </li>
        <li> Odpoveď:
            <ul>
                <li>pri úspešnom nájdení:
                    <ul>
                        <li>
                            [{<br>
                            "Meno": "Martin",<br>
                            "Country": "Česko",<br>
                            "Datum": "11.11."<br>
                            }]<br>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li>ak záznam neexistuje:
                    <ul>
                        <li>
                            [{<br>
                            "Status": "failed",<br>
                            "status_message": "Nenájdený žiaden záznam so zadaným dátumom."<br>
                            }]<br>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
    <p class="coll">Na základe uvedeného mena získať informáciu, kedy má osoba s týmto menom meniny:</p>
    <ul>
        <li>Základné informácie:
            <ul>
                <li>Reaguje na metódu GET.</li>
                <li>Je nutné zadať parametre pre typ(meno), meno(nejaké meno) a kód krajiny(SK, CZ, PL, HU, AT).</li>
            </ul>
        </li>
        <li>Príklad príkazu curl:
            <ul>
                <li>curl -X GET 'https://wt116.fei.stuba.sk/WebTt2zadania/zadanie6/api/?typ=meno&meno=Martin&kod=SK'</li>
            </ul>
        </li>
        <li> Odpoveď:
            <ul>
                <li>pri úspešnom nájdení:
                    <ul>
                        <li>
                            [{<br>
                            "Meno": "Martin",<br>
                            "Country": "Slovensko",<br>
                            "Datum": "11.11."<br>
                            }]<br>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li>ak záznam neexistuje:
                    <ul>
                        <li>
                            [{<br>
                            "Status": "failed",<br>
                            "status_message": "Nenájdený žiaden záznam so zadaným menom."<br>
                            }]<br>
                        </li>
                    </ul>
                </li>
            </ul>

        </li>
    </ul>
    <p class="coll">Získať zoznam všetkých sviatkov na Slovensku:</p>
    <ul>
        <li>Základné informácie:
            <ul>
                <li>Reaguje na metódu GET.</li>
                <li>Je nutné zadať parameter pre typ(SKsviatky).</li>
            </ul>
        </li>
        <li>Príklad príkazu curl:
            <ul>
                <li>curl -X GET https://wt116.fei.stuba.sk/WebTt2zadania/zadanie6/api/?typ=SKsviatky</li>
            </ul>
        </li>
        <li> Odpoveď:
            <ul>
                <li>
                    [{<br>
                    "Sviatok": "Názov sviatku",<br>
                    "Country": "Slovensko",<br>
                    "Datum": "Dátum na ktorý sviatok pripadá"<br>
                    }]<br>
                </li>
            </ul>
        </li>
    </ul>
    <p class="coll">Získať zoznam všetkých sviatkov v Českej republike:</p>
    <ul>
        <li>Základné informácie:
            <ul>
                <li>Reaguje na metódu GET.</li>
                <li>Je nutné zadať parameter pre typ(CZsviatky).</li>
            </ul>
        </li>
        <li>Príklad príkazu curl:
            <ul>
                <li>curl -X GET https://wt116.fei.stuba.sk/WebTt2zadania/zadanie6/api/?typ=CZsviatky</li>
            </ul>
        </li>
        <li> Odpoveď:
            <ul>
                <li>
                    [{<br>
                    "Sviatok": "Názov sviatku",<br>
                    "Country": "Česko",<br>
                    "Datum": "Dátum na ktorý sviatok pripadá"<br>
                    }]<br>
                </li>
            </ul>
        </li>
    </ul>
    <p class="coll">Získať zoznam všetkých pamätných dní na Slovensku:</p>
    <ul>
        <li>Základné informácie:
            <ul>
                <li>Reaguje na metódu GET.</li>
                <li>Je nutné zadať parameter pre typ(SKdni).</li>
            </ul>
        </li>
        <li>Príklad príkazu curl:
            <ul>
                <li>curl -X GET https://wt116.fei.stuba.sk/WebTt2zadania/zadanie6/api/?typ=SKdni</li>
            </ul>
        </li>
        <li> Odpoveď:
            <ul>
                <li>
                    [{<br>
                    "Nazov": "Názov pamätného dňa",<br>
                    "Country": "Slovensko",<br>
                    "Datum": "Dátum na ktorý pamätný deň pripadá"<br>
                    }]<br>
                </li>
            </ul>
        </li>
    </ul>
    <p class="coll">Vložiť nové meno do kalendára k určitému dňu:</p>
    <ul>
        <li>Základné informácie:
            <ul>
                <li>Reaguje na metódu POST.</li>
                <li>Je nutné zadať parametre pre meno(nejaké meno ktoré chcete pridať) a datum(platný dátum kedy bude mať meniny) .</li>
            </ul>
        </li>
        <li>Príklad príkazu curl:
            <ul>
                <li>curl -X POST https://wt116.fei.stuba.sk/WebTt2zadania/zadanie6/api/ -d meno=Basara -d datum=11.11.</li>
            </ul>
        </li>
        <li> Odpoveď:
            <ul>
                <li>pri úspešnom pridaní:
                    <ul>
                        <li>
                            [{<br>
                            "Status": "success",<br>
                            "Country": "Úspešne pridané meno."<br>
                            }]<br>
                        </li>
                    </ul>
                </li>
                <li>pri neúspešnom pridaní:
                    <ul>
                        <li>
                            [{<br>
                            "Status": "failed",<br>
                            "Country": "Zlyhanie pridania nového mena."<br>
                            }]<br>
                        </li>
                    </ul>
                </li>
                <li>ak chýba nejaký parameter:
                    <ul>
                        <li>
                            [{<br>
                            "Status": "failed",<br>
                            "Country": "Nebol zadaný nejaký parameter."<br>
                            }]<br>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>

</div>
