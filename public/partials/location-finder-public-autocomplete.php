<?php
$options = get_option('liw_settings');
?>

<script>
    var input = document.getElementById('searchInput');
    var typingTimer;
    var doneTypingInterval = 700;
    var resultsListContainer = document.getElementById("resultsList");
    var currentLI = null;
    var listItems = null;

    input.addEventListener('keyup', (event) => {
        if (event.keyCode !== 37 && event.keyCode !== 38 && event.keyCode !== 39 && event.keyCode !== 40) {
            if (event.keyCode === 13 && listItems && listItems.length > 0 && currentLI !== null) {
                selectPrediction(listItems[currentLI]);
            } else if(event.keyCode === 27 && listItems && listItems.length > 0){
                removeSuggestions();
            } else {
                clearTimeout(typingTimer);
                if (input.value) {
                    typingTimer = setTimeout(initAutocomplete, doneTypingInterval);
                }
            }
        }

    });

    input.addEventListener('keydown', function(event) {
        if (document.getElementById('resultsList').children.length > 0) {

            switch (event.keyCode) {
                case 38: // Up arrow
                    if (currentLI === null) {
                        currentLI = listItems.length - 1;
                    } else {
                        listItems[currentLI].classList.remove("active");
                        if (currentLI == 0) {
                            currentLI = listItems.length - 1;
                        } else {
                            currentLI = currentLI > 0 ? --currentLI : 0; // Decrease the counter
                        }
                    }
                    // Remove the highlighting from the previous element
                    listItems[currentLI].classList.add("active"); // Highlight the new element
                    break;
                case 40: // Down arrow
                    if (currentLI === null) {
                        currentLI = 0;
                    } else {
                        // Remove the highlighting from the previous element
                        listItems[currentLI].classList.remove("active");
                        if (currentLI == listItems.length - 1) {
                            currentLI = 0;
                        } else {
                            currentLI = currentLI < listItems.length - 1 ? ++currentLI : listItems.length - 1; // Increase counter
                        }
                    }
                    listItems[currentLI].classList.add("active"); // Highlight the new element
                    break;
            }
        }

    });

    input.addEventListener('blur', removeSuggestions);

    function removeSuggestions() {
        resultsListContainer.innerHTML = '';
    }

    const displaySuggestions = function(predictions, status) {
        document.getElementById("resultsList").innerHTML = '';

        if (status != google.maps.places.PlacesServiceStatus.OK) {
            const li = document.createElement("li");
            li.classList.add("list-group-item");
            li.appendChild(document.createTextNode('Keine Übereinstimmung gefunden.'));
            resultsListContainer.appendChild(li);
            return;
        }

        predictions.forEach(prediction => {
            const li = document.createElement("li");
            li.classList.add("list-group-item");
            li.classList.add("list-group-item-action");
            if (prediction.types[1] === 'sublocality') {
                li.setAttribute('searchValue', prediction.terms[0].value + ' ' + prediction.terms[1].value);
            } else {
                li.setAttribute('searchValue', prediction.terms[0].value);
            }

            li.onmousedown = function() {
                selectPrediction(li)
            };
            li.innerHTML= '<span class="text-black-50 mr-2"><i class="fas fa-map-marker-alt"></i></span>'
            li.appendChild(document.createTextNode(prediction.description));
            resultsListContainer.appendChild(li);
        });

        listItems = document.getElementById('resultsList').children;

    }

    function selectPrediction(el) {
        input.value = el.getAttribute("searchvalue");
        document.getElementById('searchform').submit();
    }


    function initAutocomplete() {

        currentLI = null;
        listItems = null;

        service = new google.maps.places.AutocompleteService();
        google.maps.places.ComponentRestrictions
        service.getPlacePredictions({
                input: input.value,
                types: ['(regions)'],
                componentRestrictions: {
                    country: <?php
                    if (strlen($options['autocomplete_countries']) > 0) {

                        function mapCountries($c)
                        {
                            return "'" . trim($c) . "'";
                        }

                        $mappedCountries = array_map('mapCountries', explode(',', $options['autocomplete_countries']));

                        $comma_separated = implode(",", $mappedCountries);

                        echo('[' . $comma_separated . ']');


                    } else {
                        echo("['de']");
                    }
                    ?>
                }
            },
            displaySuggestions)
    }

</script>

