# jellycurrency
Technical test of Jellyfish

Hello everyone ! So this was the Jellyfish technical test. 

To launch it, you have to :
- have an APACHE (I use wampserver64)
- clone this repository in the local files of the apache (usually www)
- import the database in a sql server (for exemple PHPMYADMIN as I used for this project and it is all local, you will find the database, with some data as tests, in the folder "db"), make sure to put the same name so the database connection wouldn't be affected
- then in the localhost (usually localhost:3000), you can launch the project
- final step, enjoy ;)

Here are the test accounts (email, name, password): 
- john@gmail.com, jd, jd
- sample@gmail.com, sam, sam

Some awares : 
- unfortunately, I have a free API key, so my number of request is limited, sometimes the app is making too many requests, the display will be filled by errors, because it couldn't store the API values :/
- responsivity : please use the app on a computer or laptop, I didn't handled the responsive 🥲
- to not overhelm the app, I desactivated the local cron, go to js/main.js to uncomment it to test it
