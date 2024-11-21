document.addEventListener('DOMContentLoaded', (event) => {
  fetch('timepass.json')
    .then(response => response.json())
    .then(data => {
      const container = document.querySelector('.row');
      data.destinations.forEach(destination => {
        const card = `
          <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card">
              <img src="${destination.image}" class="card-img-top" alt="${destination.name}">
              <div class="card-body">
                <h5 class="card-title">${destination.name}</h5>
                <p class="card-text">${destination.description}</p>
                <!-- Update the link to point to template.html with the destination query parameter -->
                <a href="template.html?destination=${encodeURIComponent(destination.name)}" class="btn btn-primary">Explore ${destination.name}</a>
              </div>
            </div>
          </div>
        `;
        container.innerHTML += card;
      });
    });
});




