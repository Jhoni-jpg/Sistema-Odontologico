// Crear el contenedor si no existe
(function () {
    if (!document.getElementById("toast-container")) {
        const container = document.createElement("div");
        container.id = "toast-container";
        document.body.appendChild(container);
    }
})();

function showToast(message, color = 'warning', duration = 5000) {
    const toastExists = document.querySelector('.flash-container');

    if (toastExists) {
        toastExists.remove();
    }

    // Crear nuevo toast
    const toast = document.createElement("div");
    toast.className = "flash-container";

    const toastChild = document.createElement('div');
    toast.appendChild(toastChild);
    toastChild.className = `alert alert-${color} alert-dismissible fade show m-3`;
    toastChild.setAttribute('role', 'alert');

    if (Array.isArray(message)) {
        if (message.length === 1) {
            toastChild.textContent = message[0];
        } else {
            const ul = document.createElement("ul");
            ul.style.margin = "0";
            ul.style.padding = "5px";
            ul.style.listStyle = "none";

            message.forEach(item => {
                const li = document.createElement("li");
                li.style.marginBottom = "5px";
                li.textContent = '⚠️ ' + item;
                ul.appendChild(li);
            });

            toastChild.appendChild(ul);
        }
    } else {
        toastChild.textContent = message;
    }

    console.log(toast)

    document.body.appendChild(toast);

    // Mostrar animación
    setTimeout(() => toast.classList.add("show"), 100);

    // Ocultar y eliminar después de duración
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, duration);
    });

}
