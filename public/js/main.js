document.querySelector("body").style.height = (window.innerHeight - 64) + "px";

function resize() {
    document.querySelector("body").style.height = (window.innerHeight - 64) + "px";
    console.log("resized");
}

function displayPopup() {
    console.log("displayed");
    document.getElementById('overlay').style.display = "block"
    document.getElementById('popup').style.display = "block"
}

function hidePopup() {
    console.log("hidden")
    document.getElementById('overlay').style.display = "none"
    document.getElementById('popup').style.display = "none"
}

function getCronData() {
    return fetch('http://localhost/projects/TestTech/Jellyfish_Test/jellyfish/cron/cron.php', {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(response => response.json());
}

function getCoinApiData(currency) {
    const url = `https://rest.coinapi.io/v1/exchangerate/BTC/${currency}`;

    return fetch(url, {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CoinAPI-Key': 'D8E98BD2-920A-40F7-BAA3-6892C418A306',
        },
    }).then(response => response.json());
}

function cron() {
    getCronData()
        .then(alerts => {
            // Traitement des données de l'API cron.php
            for (let i = 0; i < alerts.length; i++) {
                let currency = "";
                let limit = "";
                let type = "";
                currency = alerts[i]['currency'];
                limit = alerts[i]['limit'];
                type = alerts[i]['type'];

                // Appeler getCoinApiData avec la currency dynamique
                getCoinApiData(currency)
                    .then(apiData => {
                        // Traitement des données de l'API CoinAPI
                        const theApiValue = apiData['rate'];
                        if (type == "above") {
                            if (theApiValue < limit) {
                                console.log(currency + " is now above " + limit + " !");
                            }
                        } else {
                            if (theApiValue > limit) {
                                console.log(currency + " is now below " + limit + " !");
                            }
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        })
        .catch(error => {
            console.error(error);
        });
}


setInterval(() => {
    cron();
}, 15000);
