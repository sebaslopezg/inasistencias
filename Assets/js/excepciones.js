let tablaExcepciones = document.querySelector("#tblExcepciones");
let btnCrearExcepcion = document.querySelector("#btnCrearExcepcion");
let fichaOpciones = document.querySelector(".ficha");
let slcts = document.querySelector("#slcts");
let divFicha = document.querySelector("#divFicha");
let divAprendiz = document.querySelector("#divAprendiz");
let AprendizOpciones = document.querySelector(".aprendiz");
let AprendizCheckboxContainer = document.getElementById("checkboxAprendices");

let frmCrearExcepcion = document.querySelector("#frmCrearExcepcion");
let selectExepcion = document.querySelector("#selectExepcion");
let Aprendiz = document.querySelector("#aprendiz");
let txtFicha = document.querySelector("#ficha");
let txtMotivo = document.querySelector("#txtMotivo");
let txtExcepcionId = document.querySelector("#txtExcepcionId");

cargarTabla();
function cargarTabla() {
  tablaExcepciones = $("#tblExcepciones").dataTable({
    language: {
      url: `${base_url}/Assets/vendor/datatables/dataTables_es.json`
    },
    ajax: {
      url: `${base_url}/excepciones/getExcepciones`,
      dataSrc: ""
    },
    columns: [
      { data: "motivo" }, // Antes: descripcion
      { data: "fechaInicio" },
      { data: "fechaFin" },
      { data: "aprendices" }, // Antes: nombreUsu
      { data: "nombreFicha" },
      { data: "tipoExcepcion" },
      { data: "status" },
      { data: "action" }
    ],
    responsive: true,
    iDisplayLength: 10,
    order: [[0, "asc"]]
  });
}

flatpickr.localize(flatpickr.l10ns.es);

const opcionesFlat = {
  enableTime: true,
  time_24hr: false,
  altInput: true,
  altFormat: "d-m-Y h:i K",
  dateFormat: "Y-m-d H:i:S",
  minDate: "today", // minDate: new Date()
  locale: "es"
};

const fpInicio = flatpickr("#txtFechaInicio", opcionesFlat);
const fpFin = flatpickr("#txtFechaFin", opcionesFlat);

btnCrearExcepcion.addEventListener("click", async () => {
  clearForm();
  await cargarFicha();

  mostrarInputs();
  $("#crearExcepcionModal").modal("show");
});

function MostrarNoti() {
  let ulNotificacion = document.querySelector("#ulNotificacion");
  let spanNoti = document.querySelector("#spanNoti");
  let headerLi = document.querySelector("#headerLi");
  fetch(base_url + "/excusas/getNotificaciones")
    .then((res) => res.json())
    .then((data) => {
      document.querySelectorAll(".notification-item, .dropdown-divider").forEach((el) => el.remove());
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

document.addEventListener("click", (e) => {
  try {
    let action = e.target.closest("button").getAttribute("data-action");
    let id = e.target.closest("button").getAttribute("data-id");
    if (action == "delete") {
      Swal.fire({
        title: "Eliminar excepcion",
        text: "¿Está seguro de eliminar la excepcion?",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: "Sí",
        denyButtonText: `Cancelar`
      }).then((result) => {
        if (result.isConfirmed) {
          let frmData = new FormData();
          frmData.append("idExcepcion", id);
          fetch(base_url + "/excepciones/deleteExcepciones", {
            method: "POST",
            body: frmData
          })
            .then((res) => res.json())
            .then((data) => {
              Swal.fire({
                title: data.status ? "Correcto" : "Error",
                text: data.msg,
                icon: data.status ? "success" : "error",
                timer: 2000,
                showConfirmButton: false
              });

              tablaExcepciones.api().ajax.reload(function () {});
            });
        }
      });
    }

    if (action == "editar") {
      fetch(base_url + "/excepciones/getExcepcionById/" + id)
        .then((res) => res.json())
        .then(async (data) => {
          if (data.status) {
            data = data.data;
            clearForm();

            txtExcepcionId.value = data.idExcepciones;
            selectExepcion.value = data.tipoExcepcion;
            fpInicio.setDate(data.fechaInicio, true, "Y-m-d H:i:S");
            fpFin.setDate(data.fechaFin, true, "Y-m-d H:i:S");
            txtMotivo.value = data.motivo;

            if (data.tipoExcepcion === 1) {
              await cargarFicha();
              txtFicha.value = data.fichaId;

              slcts.style.display = "flex";
              mostrarInputs();

              await mostrarAprendices();
              data.aprendices.forEach((idApr) => {
                const chk = document.querySelector(`input[name="aprendices[]"][value="${idApr}"]`);
                if (chk) chk.checked = true;
              });

              $("#crearExcepcionModal").modal("show");
            } else if (data.tipoExcepcion === 2) {
              await cargarFicha();
              txtFicha.value = data.fichaId;

              slcts.style.display = "flex";
              mostrarInputs();
              $("#crearExcepcionModal").modal("show");
            } else if (data.tipoExcepcion === 3) {
              slcts.style.display = "none";
              mostrarInputs();
              $("#crearExcepcionModal").modal("show");
            }
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error"
            });
            tablaExcepciones.api().ajax.reload();
          }
        });
    }
  } catch {}
});

function clearForm() {
  selectExepcion.value = "";
  mostrarInputs();

  fichaOpciones.innerHTML = '<option value="" disabled selected>Seleccione la Ficha</option>';
  txtFicha.setAttribute("disabled", "disabled");
  txtFicha.removeAttribute("required");

  AprendizCheckboxContainer.innerHTML = "";
  divAprendiz.style.display = "none";

  fpInicio.value = "";
  fpFin.value = "";
  txtMotivo.value = "";

  txtExcepcionId.value = 0;
}

async function cargarFicha() {
  fichaOpciones.innerHTML = "";

  const placeholder = document.createElement("option");
  placeholder.value = "";
  placeholder.disabled = true;
  placeholder.selected = true;
  placeholder.textContent = "Seleccione la Ficha";
  fichaOpciones.appendChild(placeholder);

  let response = await fetch(base_url + "/excepciones/getFicha");
  let data = await response.json();

  data.forEach((ficha) => {
    const opt = document.createElement("option");
    opt.value = ficha.idFicha;
    opt.textContent = `${ficha.nombre} - ${ficha.numeroFicha}`;
    fichaOpciones.appendChild(opt);
  });
}

async function mostrarAprendices() {
  if (selectExepcion.value !== "1") {
    divAprendiz.style.display = "none";
    return;
  }

  AprendizCheckboxContainer.innerHTML = "";

  if (!txtFicha.value) {
    AprendizCheckboxContainer.innerHTML = "<p>Debe seleccionar una ficha primero.</p>";
    return;
  }

  try {
    let response = await fetch(base_url + "/excepciones/selectAprendisById/" + txtFicha.value);
    if (!response.ok) throw new Error("Error en la petición al servidor");
    let data = await response.json();

    if (!data || !data.data || data.data.length === 0) {
      AprendizCheckboxContainer.innerHTML = "<p>No se encontraron aprendices.</p>";
      return;
    }

    data.data.forEach((aprendiz) => {
      const colDiv = document.createElement("div");
      colDiv.className = "col-4 mb-2";

      const wrapper = document.createElement("div");
      wrapper.className = "form-check";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.className = "form-check-input";
      checkbox.name = "aprendices[]";
      checkbox.value = aprendiz.usuario_idUsuarios;
      checkbox.id = `aprendiz_${aprendiz.usuario_idUsuarios}`;

      const label = document.createElement("label");
      label.className = "form-check-label";
      label.htmlFor = checkbox.id;
      label.textContent = `${aprendiz.nombre} - ${aprendiz.documento}`;

      wrapper.appendChild(checkbox);
      wrapper.appendChild(label);
      colDiv.appendChild(wrapper);

      AprendizCheckboxContainer.appendChild(colDiv);
    });

    divAprendiz.style.display = "block";
  } catch (error) {
    AprendizCheckboxContainer.innerHTML = "<p>Error cargando aprendices.</p>";
    console.error(error);
  }
}

txtFicha.addEventListener("change", mostrarAprendices);

function mostrarInputs() {
  slcts.style.display = "none";
  divFicha.style.display = "none";
  divAprendiz.style.display = "none";

  txtFicha.removeAttribute("required");

  AprendizCheckboxContainer.innerHTML = "";

  if (selectExepcion.value == "1") {
    slcts.style.display = "flex";
    divFicha.style.display = "block";
    divAprendiz.style.display = "block";

    txtFicha.removeAttribute("disabled");
    txtFicha.setAttribute("required", "true");
  } else if (selectExepcion.value == "2") {
    slcts.style.display = "flex";
    divFicha.style.display = "block";

    txtFicha.removeAttribute("disabled");
    txtFicha.setAttribute("required", "true");

    divFicha.classList.remove("col-6");
    divFicha.classList.add("col-12");
  } else {
    txtFicha.setAttribute("disabled", "disabled");
  }
}

selectExepcion.addEventListener("change", mostrarInputs);

frmCrearExcepcion.addEventListener("submit", (e) => {
  e.preventDefault();
  let frmExcepcion = new FormData(frmCrearExcepcion);

  fetch(base_url + "/excepciones/setExcepciones", {
    method: "POST",
    body: frmExcepcion
  })
    .then((res) => {
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }

      return res.text();
    })
    .then((text) => {
      let data;
      try {
        data = JSON.parse(text);
      } catch (err) {
        console.error("Respuesta no es JSON válido:", text);
        throw new Error("La respuesta del servidor no es JSON.");
      }

      if (data.status) {
        Swal.fire({
          title: "Registro Excepción",
          text: data.msg,
          icon: "success"
        });
        tablaExcepciones.api().ajax.reload();
        $("#crearExcepcionModal").modal("hide");
        clearForm();
      } else {
        Swal.fire({
          title: "Error",
          text: data.msg,
          icon: "error"
        });
      }
    })
    .catch((error) => {
      console.error("Error en fetch o JSON.parse:", error);
      Swal.fire({
        title: "Error inesperado",
        text: "Ocurrió un problema al procesar la respuesta del servidor.",
        icon: "error"
      });
    });
});
