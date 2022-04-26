const app = {
    apiRootUrl: 'http://localhost:8080/',
    /**
     * Méthode init
     */
    init: function() {
        console.log("init");
        const startLogElement = document.getElementById('startLog');
        startLogElement.addEventListener('click', app.handleStartLog);

        const startLunchElement = document.getElementById('startLunch');
        startLunchElement.addEventListener('click', app.handleStartLunch);

        const endLunchElement = document.getElementById('endLunch');
        endLunchElement.addEventListener('click', app.handleEndLunch);

        const endLogElement = document.getElementById('endLog');
        endLogElement.addEventListener('click', app.handleEndLog);
        
    },
    
    // handle START Log
    handleStartLog: function() {

        console.log('bouton cliqué - début journée');

        const httpHeaders = new Headers();
        httpHeaders.append("Content-Type", "application/json");

        const fetchOptions = {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            headers: httpHeaders,
            body: ''
        };

        fetch(app.apiRootUrl + 'log/startlog' , fetchOptions)
        .then(
            function(response) {
               
                // Si HTTP status code à 200 => OK avec reload de page
                if (response.status == 200) {     
                    location.reload();
                    return response;                    
                }
                else {
                    alert('La modification a échoué');
                }
            }
        )
    },

     // handle START LUNCH
     handleStartLunch: function() {

        console.log('bouton cliqué - début repas');

        const httpHeaders = new Headers();
        httpHeaders.append("Content-Type", "application/json");

        const fetchOptions = {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            headers: httpHeaders,
            body: ''
        };

        fetch(app.apiRootUrl + 'log/startlunch' , fetchOptions)
        .then(
            function(response) {
               
                // Si HTTP status code à 200 => OK avec reload de page
                if (response.status == 200) {     
                    location.reload();
                    return response;                    
                }
                else {
                    alert('La modification a échoué');
                }
            }
        )
    },

     // handle END LUNCH
     handleEndLunch: function() {

        console.log('bouton cliqué - fin repas');

        const httpHeaders = new Headers();
        httpHeaders.append("Content-Type", "application/json");

        const fetchOptions = {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            headers: httpHeaders,
            body: ''
        };

        fetch(app.apiRootUrl + 'log/endlunch' , fetchOptions)
        .then(
            function(response) {
               
                // Si HTTP status code à 200 => OK avec reload de page
                if (response.status == 200) {     
                    location.reload();
                    return response;                    
                }
                else {
                    alert('La modification a échoué');
                }
            }
        )
    },

     // handle END Log
     handleEndLog: function() {

        console.log('bouton cliqué - fin journée');

        const httpHeaders = new Headers();
        httpHeaders.append("Content-Type", "application/json");

        const fetchOptions = {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            headers: httpHeaders,
            body: ''
        };

        fetch(app.apiRootUrl + 'log/endlog' , fetchOptions)
        .then(
            function(response) {
               
                // Si HTTP status code à 200 => OK avec reload de page
                if (response.status == 200) {     
                    location.reload();
                    return response;                    
                }
                else {
                    alert('La modification a échoué');
                }
            }
        )
    },

};
// On veut exécuter la méthode init de l'objet app au chargement de la page
document.addEventListener('DOMContentLoaded', app.init);