function MostrarNoti() {
  let ulNotificacion = document.querySelector("#ulNotificacion");
  let spanNoti = document.querySelector("#spanNoti");
  let headerLi = document.querySelector("#headerLi");
  fetch(base_url + "/excusas/getNotificaciones")
    .then((res) => res.json())
    .then((data) => {
      document
        .querySelectorAll(".notification-item, .dropdown-divider")
        .forEach((el) => el.remove());
      if (!headerLi) {
        headerLi = document.createElement("li");
        headerLi.id = "headerLi";
        headerLi.classList.add("dropdown-header");
        ulNotificacion.prepend(headerLi);
      }

      let numNotifica = data.length;
      spanNoti.innerHTML = numNotifica;

      function tiempoTranscurrido(fechaCompleta) {
        const fechaNoti = new Date(fechaCompleta);
        const ahora = new Date();
        const diferencia = Math.floor((ahora - fechaNoti) / 1000);

        if (diferencia < 60) {
          return `Hace ${diferencia} segundos`;
        } else if (diferencia < 3600) {
          return `Hace ${Math.floor(diferencia / 60)} minutos`;
        } else if (diferencia < 86400) {
          return `Hace ${Math.floor(diferencia / 3600)} horas `;
        } else {
          return `Hace ${Math.floor(diferencia / 86400)} días`;
        }
      }

      if (Array.isArray(data) && data.length > 0) {
        let liHeader = `
          Tienes ${data.length} notificaciones nuevas
          <span class="badge rounded-pill bg-primary p-2 ms-2"><i class="bi bi-envelope"></i></span>
       `;
        headerLi.innerHTML = liHeader;

        data.map((noti) => {
          const fechaCompletaNoti = `${noti.fecha}T${noti.hora}`;

          const tiempoNoti = tiempoTranscurrido(fechaCompletaNoti);

          let li = `
            <li>
              <hr class="dropdown-divider">
            </li>
            
            <li class="notification-item">
            ${noti.icono}
            <div>
              <h4>
                <a href="${noti.link}" style="color: black; text-decoration: none;">
                  ${noti.tipoNovedad}
                </a>
              </h4>
              <div style="display: flex; align-items: center;">
                <p style="margin: 0;">${noti.mensaje}</p>
                <button class="btn btn-sm rounded-circle" id="btnDelete" data-action="deleteNoti" data-id="${noti.action}"
                  style="background-color: transparent;width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; padding: 0;">
                  <i class="bi bi-trash-fill text-primary" style="font-size: 13px; margin: 0 auto;padding: 6px;"></i>
                </button>
              </div>
              <p>${tiempoNoti}</p>        
            </div>
          </li>
          `;

          ulNotificacion.innerHTML += li;
        });
      } else {
        let liHeader = `
              Tienes ${data.length} notificaciones nuevas
              <span class="badge rounded-pill bg-primary p-2 ms-2"><i class="bi bi-envelope"></i></span>
           `;
        headerLi.innerHTML = liHeader;
      }
    });
}
MostrarNoti();
