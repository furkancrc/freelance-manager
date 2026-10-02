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

function readForm(form) {
    const data = {};

    for (const [name, value] of new FormData(form)) {
        const text = name === "password" ? value : value.trim();
        data[name] = text === "" ? null : text;
    }

    return data;
}

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

function done(element, data) {
    if (element.dataset.flash) {
        try {
            sessionStorage.setItem("flash", element.dataset.flash);
        } catch {}
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

document.querySelectorAll("form.filters").forEach((form) => {
    form.addEventListener("change", (event) => {
        if (event.target.matches("select, [type=checkbox]")) {
            form.requestSubmit();
        }
    });
});

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
} catch {}
