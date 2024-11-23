document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const destinationName = urlParams.get('destination');

    fetch('timepass.json')
        .then(response => response.json())
        .then(data => {
            const destination = data.destinations.find(dest => dest.name === destinationName);

            if (destination) {
                document.title = `Explore ${destination.name} - VVP Travel`;
                document.getElementById('destination-title').textContent = `Explore ${destination.name}`;
                document.getElementById('destination-image').src = destination.image;
                document.getElementById('destination-description').textContent = destination.description;

                const bookingLinks = document.getElementById('booking-links');
                for (let key in destination.booking_links) {
                    const listItem = document.createElement('li');
                    listItem.className = "list-group-item";
                    listItem.innerHTML = `<a href="${destination.booking_links[key]}">Book ${key.charAt(0).toUpperCase() + key.slice(1)}</a>`;
                    bookingLinks.appendChild(listItem);
                }

                const attractionsDiv = document.getElementById('attractions');
                destination.attractions.forEach(attraction => {
                    const attractionHTML = `
                        <h2>${attraction.name}:</h2>
                        <p>${attraction.description}</p>
                        <p><strong>Entry Fees:</strong> ${attraction.entry_fees || ''}</p>
                        <p><strong>Tips:</strong> ${attraction.tips}</p>
                    `;
                    attractionsDiv.innerHTML += attractionHTML;
                });
            } else {
                document.getElementById('destination-content').innerHTML = `<p>Destination not found!</p>`;
            }
        })
        .catch(error => console.error('Error fetching data:', error));
});
