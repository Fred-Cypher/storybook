let links = document.querySelectorAll("[data-delete]");

for (let link of links) {
    link.addEventListener("click", function (e) {
        e.preventDefault();

        // Confirmation demandée
        if (confirm("Voulez-vous supprimer cette image ?")) {
            // Envoi requête ajax
            fetch(this.getAttribute("href"), {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ "_token": this.dataset.token })
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.parentElement.remove();
                    } else {
                        alert(data.error);
                    }
                })
        }
    });
}