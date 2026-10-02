// Interactions JavaScript des pages.
//
// Les pages sont rendues en PHP. Le JavaScript ne fait que :
// - envoyer les formulaires [data-api] à l'API JSON (avec le jeton CSRF) ;
// - gérer les boutons d'action [data-api] (supprimer, favoris…) ;
// - relancer les filtres de recherche quand une liste déroulante change ;
// - afficher le message de confirmation après une redirection.
//
// Attributs utilisés sur un formulaire ou un bouton :
//   data-api="/api/missions"   URL appelée
//   data-method="POST"         méthode HTTP (POST par défaut)
//   data-confirm="…"           demande une confirmation avant l'envoi
//   data-redirect="/missions"  page suivante ({id} = id renvoyé par l'API) ;
//                              sans cet attribut, la page est rechargée
//   data-flash="…"             message affiché sur la page suivante

const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.content ?? "";

async function callApi(method, url, body) {
    const options = {
        method,
        headers: { Accept: "application/json", "X-CSRF-Token": csrfToken },
    };

    if (body !== undefined) {
        options.headers["Content-Type"] = "application/json";
        options.body = JSON.stringify(body);
    }

    const response = await fetch(url, options);

    if (response.status === 401) {
        window.location.href = "/login";
    }

    let data = null;
    try {
        data = await response.json();
    } catch {
        data = null;
    }

    return { ok: response.ok, status: response.status, data };
}

// Lit un formulaire en objet ; les champs vides deviennent null.
function readForm(form) {
    const data = {};

    for (const [name, value] of new FormData(form)) {
        const text = name === "password" ? value : value.trim();
        data[name] = text === "" ? null : text;
    }

    return data;
}

// Affiche les erreurs de l'API sous les champs concernés.
function showErrors(form, res) {
    form.querySelectorAll(".field-error").forEach((node) => node.remove());
    form.querySelectorAll(".has-error").forEach((node) =>
        node.classList.remove("has-error"),
    );

    const box = form.querySelector(".form-error");
    box.textContent = res.data?.error ?? "";

    if (res.data === null) {
        box.textContent = `Erreur inattendue (${res.status}).`;
    }

    for (const [name, message] of Object.entries(res.data?.errors ?? {})) {
        const input = form.elements[name];

        if (input) {
            const error = document.createElement("p");
            error.className = "field-error";
            error.textContent = message;
            input.classList.add("has-error");
            input.after(error);
        } else {
            box.textContent += `${message} `;
        }
    }

    form.querySelector(".has-error")?.focus();
}

// Action réussie : on garde le message puis on change de page.
function done(element, data) {
    if (element.dataset.flash) {
        try {
            sessionStorage.setItem("flash", element.dataset.flash);
        } catch {
            // Stockage indisponible (navigation privée) : pas de message.
        }
    }

    if (element.dataset.redirect) {
        window.location.href = element.dataset.redirect.replace(
            "{id}",
            data?.id ?? "",
        );
    } else {
        window.location.reload();
    }
}

document.querySelectorAll("form[data-api]").forEach((form) => {
    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const button = form.querySelector('[type="submit"]');
        button.disabled = true;

        const res = await callApi(
            form.dataset.method ?? "POST",
            form.dataset.api,
            readForm(form),
        );

        if (res.ok) {
            done(form, res.data);
            return;
        }

        button.disabled = false;
        showErrors(form, res);
    });
});

document.querySelectorAll("button[data-api]").forEach((button) => {
    button.addEventListener("click", async () => {
        if (button.dataset.confirm && !confirm(button.dataset.confirm)) {
            return;
        }

        button.disabled = true;
        const res = await callApi(
            button.dataset.method ?? "POST",
            button.dataset.api,
        );

        if (res.ok) {
            done(button, res.data);
            return;
        }

        button.disabled = false;
        const message = res.data?.error ?? "Action impossible.";
        const box = document.getElementById("action-error");

        if (box) {
            box.textContent = message;
        } else {
            alert(message);
        }
    });
});

// Filtres : une liste déroulante ou une case relance la recherche.
document.querySelectorAll("form.filters").forEach((form) => {
    form.addEventListener("change", (event) => {
        if (event.target.matches("select, [type=checkbox]")) {
            form.requestSubmit();
        }
    });
});

// Message de confirmation laissé par la page précédente.
try {
    const message = sessionStorage.getItem("flash");
    const box = document.getElementById("flash");

    if (message && box) {
        sessionStorage.removeItem("flash");
        const flash = document.createElement("p");
        flash.className = "flash";
        flash.setAttribute("role", "status");
        flash.textContent = message;
        box.append(flash);
    }
} catch {
    // Stockage indisponible : rien à afficher.
}
