// function to resize the body 

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

// retreive all the alerts of the user, Ajax method to get php values

function getCronData() {
    return fetch('http://localhost/projects/TestTech/Jellyfish_Test/jellyfish/cron/cron.php', {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(response => response.json());
}

// api request to get the current rate of the currency

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

// my cron : will first get the alerts from the user, then make an api request on the coinapi with the currency from one alert (for each alerts, one api request is done)

function cron() {
    getCronData()
        .then(alerts => {
            for (let i = 0; i < alerts.length; i++) { // loop to check one by one the rate of this currency
                let currency = "";
                let limit = "";
                let type = "";
                currency = alerts[i]['currency'];
                limit = alerts[i]['limit'];
                type = alerts[i]['type'];

                getCoinApiData(currency)
                    .then(apiData => {
                        const theApiValue = apiData['rate'];
                        if (type == "above") {
                            if (theApiValue < limit) {
                                console.log(currency + " is now above " + limit + " !"); // display in the console the message 
                                // or alert(currency + " is now above " + limit + " !");
                            }
                        } else {
                            if (theApiValue > limit) {
                                console.log(currency + " is now below " + limit + " !"); // display in the console the message 
                                // or alert(currency + " is now below " + limit + " !");
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

// check each currency rates every 15sec so the user can be alerted
// setInterval(() => {
//     cron();
// }, 15000);
