let tablaExcusas = document.querySelector("#tablaExcusas");
let frmCrearExcusa = document.querySelector("#frmCrearExcusa");
let frmCrearObservacion = document.querySelector("#frmObservacion");
let txtIdExcusa = document.querySelector("#txtIdExcusa");
let IdExcusa = document.querySelector("#IdExcusa");
let txtIdInasistencia = document.querySelector("#txtIdInasistencia");
let txtIdUsuario = document.querySelector("#txtIdUsuario");
let txtIdInstructor = document.querySelector("#txtIdInstructor");
let txtArchivo = document.querySelector("#txtArchivo");
let txtEstado = document.querySelector("#txtEstado");
let txtObservacion = document.querySelector("#txtObservacion");
let txtobservacionApre = document.querySelector("#observacionApre");

let rol;

fetch(base_url + "/excusas/getUsuarioById")
  .then((res) => res.json())
  .then((data) => {
    if (data.status) {
      data = data.data;
      rol = data[0].rol;

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

      if (rol === "INSTRUCTOR") {
        let thead = document.querySelector("#tablaExcusas thead tr");

        thead.children[0].textContent = "Fecha Excusa";
        thead.children[2].textContent = "Nombre de Ficha";
        thead.children[3].textContent = "Ficha";
        thead.children[4].textContent = "Inasistencia";
        const CeldaExc = document.createElement("th");
        CeldaExc.textContent = "Excusa";
        const CeldaAcci = document.createElement("th");
        CeldaAcci.textContent = "Accion";
        const CeldaSta = document.createElement("th");
        CeldaSta.textContent = "Estado";
        thead.appendChild(CeldaSta);
        thead.appendChild(CeldaExc);
        thead.appendChild(CeldaAcci);

        cargarTabla();
        function cargarTabla() {
          tablaExcusas = $("#tablaExcusas").dataTable({
            language: {
              url: `${base_url}/Assets/vendor/datatables/dataTables_es.json`
            },
            ajax: {
              url: " " + base_url + "/excusas/getExcusas",
              dataSrc: ""
            },
            columns: [
              { data: "feExc" },
              { data: "nombreCompleto" },
              { data: "ficha" },
              { data: "numeroFicha" },
              { data: "feIna" },
              { data: "status" },
              { data: "fileExc" },
              { data: "action" }
            ],
            responsive: "true",
            iDisplayLength: 10,
            order: [[0, "asc"]]
          });
        }

        document.addEventListener("click", (e) => {
          try {
            let id = e.target.closest("button").getAttribute("data-id");
            let action = e.target.closest("button").getAttribute("data-action");
            console.log(id);
            if (action == "descargar") {
              fetch(base_url + "/excusas/descargarArchivo?download=" + id)
                .then((response) => {
                  if (response) {
                    // Si la respuesta es exitosa (archivo encontrado), procesar la descarga
                    return response.blob(); // Convertimos la respuesta en un Blob (archivo)
                  } else {
                    throw new Error("No se pudo encontrar el archivo");
                  }
                })
                .then((blob) => {
                  // Crear un enlace temporal para forzar la descarga
                  const link = document.createElement("a");
                  const url = window.URL.createObjectURL(blob);
                  link.href = url;
                  link.download = "Excusa.pdf"; // Puedes asignar el nombre del archivo que deseas descargar
                  link.click(); // Simula el clic en el enlace para iniciar la descarga
                  window.URL.revokeObjectURL(url); // Limpiar la URL del objeto después de la descarga
                })
                .catch((error) => {
                  Swal.fire({
                    title: "Error",
                    text: error.message,
                    icon: "error"
                  });
                });
            }
            if (action == "aprobar") {
              Swal.fire({
                title: "Aprobar Excusa",
                text: "¿Está seguro de Aprobar la excusa?",
                icon: "question",
                showDenyButton: true,
                confirmButtonText: "Sí",
                denyButtonText: `Cancelar`
              }).then((result) => {
                if (result.isConfirmed) {
                  console.log(id);
                  let frmData = new FormData();
                  frmData.append("txtIdInasistencia", id);
                  fetch(base_url + "/excusas/aceptarExcusas", {
                    method: "POST",
                    body: frmData
                  })
                    .then((res) => res.json())
                    .then((data) => {
                      Swal.fire({
                        title: data.status ? "Correcto" : "Error",
                        text: data.msg,
                        icon: data.status ? "success" : "error"
                      });
                      tablaExcusas.api().ajax.reload(function () {});
                    });
                }
              });
            }

            if (action == "denegar") {
              Swal.fire({
                title: "denegar Excusa",
                text: "¿Está seguro de denegar la excusa?",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: "Sí",
                denyButtonText: `Cancelar`
              }).then((result) => {
                if (result.isConfirmed) {
                  console.log(id);
                  let frmData = new FormData();
                  frmData.append("txtIdInasistencia", id);
                  fetch(base_url + "/excusas/denegarExcusas", {
                    method: "POST",
                    body: frmData
                  })
                    .then((res) => res.json())
                    .then((data) => {
                      Swal.fire({
                        title: data.status ? "Correcto" : "Error",
                        text: data.msg,
                        icon: data.status ? "success" : "error"
                      });
                      tablaExcusas.api().ajax.reload(function () {});
                    });
                }
              });
            }

            if (action == "deleteNoti") {
              let frmData = new FormData();
              frmData.append("idNoti", id);
              fetch(base_url + "/excusas/eliminarNoti", {
                method: "POST",
                body: frmData
              })
                .then((res) => res.json())
                .then((data) => {
                  MostrarNoti();
                });
            }

            if (action == "agrObservacion") {
              fetch(base_url + "/excusas/selectExcusaId/" + id)
                .then((res) => res.json())
                .then((data) => {
                  if (data.status) {
                    console.log(data);
                    data = data.data;
                    txtObservacion.value = data.observacion;
                    IdExcusa.value = data.idExcusas;

                    $("#modalObsevaciones").modal("show");
                    tablaExcusas.api().ajax.reload(function () {});
                  } else {
                    Swal.fire({
                      title: "Error",
                      text: data.msg,
                      icon: "error"
                    });
                  }
                });
            }
          } catch {}
        });

        frmCrearObservacion.addEventListener("submit", (e) => {
          e.preventDefault();
          let frmObservacion = new FormData(frmCrearObservacion);
          fetch(base_url + "/excusas/agregarObservacion", {
            method: "POST",
            body: frmObservacion
          })
            .then((res) => res.json())
            .then((data) => {
              console.log(data);
              if (data.status) {
                Swal.fire({
                  title: "Registro Observacion",
                  text: data.msg,
                  icon: "success"
                });
                $("#modalObsevaciones").modal("hide");
                clearForm();
                tablaExcusas.api().ajax.reload(function () {});
              } else {
                Swal.fire({
                  title: "Error",
                  text: data.msg,
                  icon: "error"
                });
              }
            });
        });
        function clearForm() {
          IdExcusa.value = "0";
          txtObservacion.value = "";
        }
      } else if (rol === "APRENDIZ") {
        let thead = document.querySelector("#tablaExcusas thead tr");
        thead.removeChild(thead.children[1]);
        cargarTabla();
        document.addEventListener("click", (e) => {
          try {
            let id = e.target.closest("button").getAttribute("data-id");
            let action = e.target.closest("button").getAttribute("data-action");

            if (action == "agregar") {
              fetch(base_url + "/excusas/getInasistenciaById/" + id)
                .then((res) => res.json())
                .then((data) => {
                  if (data.status) {
                    console.log(data);
                    console.log(txtEstado.value);
                    data = data.data;
                    txtIdInasistencia.value = data.idIna;
                    txtIdInstructor.value = data.idInstructor;
                    txtIdUsuario.value = data.idUsu;
                    txtEstado.value = data.estado;

                    $("#crearExcusaModal").modal("show");
                    tablaExcusas.api().ajax.reload(function () {});
                  } else {
                    Swal.fire({
                      title: "Error",
                      text: data.msg,
                      icon: "error"
                    });
                  }
                });
            }

            if (action == "editar") {
              fetch(base_url + "/excusas/selectExcusaId/" + id)
                .then((res) => res.json())
                .then((data) => {
                  if (data.status) {
                    data = data.data;
                    txtIdInasistencia.value = data.inasistencias_idInasistencias;
                    txtIdInstructor.value = data.idInstructor;
                    txtIdUsuario.value = data.usuario_idUsuarios;
                    txtIdExcusa.value = data.idExcusas;
                    $("#crearExcusaModal").modal("show");
                    tablaExcusas.api().ajax.reload(function () {});
                  } else {
                    Swal.fire({
                      title: "Error",
                      text: data.msg,
                      icon: "error"
                    });
                  }
                });
            }

            if (action == "deleteNoti") {
              let frmData = new FormData();
              frmData.append("idNoti", id);
              fetch(base_url + "/excusas/eliminarNoti", {
                method: "POST",
                body: frmData
              })
                .then((res) => res.json())
                .then((data) => {
                  MostrarNoti();
                });
            }

            if (action == "observacion") {
              fetch(base_url + "/excusas/selectExcusaId/" + id)
                .then((res) => res.json())
                .then((data) => {
                  if (data.status) {
                    data = data.data;
                    txtobservacionApre.value = data.observacion;

                    $("#modalObsApre").modal("show");
                    tablaExcusas.api().ajax.reload(function () {});
                  } else {
                    Swal.fire({
                      title: "Error",
                      text: data.msg,
                      icon: "error"
                    });
                  }
                });
            }
          } catch {}
        });

        frmCrearExcusa.addEventListener("submit", (e) => {
          e.preventDefault();
          let frmExcusa = new FormData(frmCrearExcusa);
          fetch(base_url + "/excusas/setExcusas", {
            method: "POST",
            body: frmExcusa
          })
            .then((res) => res.json())
            .then((data) => {
              console.log(data);
              if (data.status) {
                Swal.fire({
                  title: "Registro Excusa",
                  text: data.msg,
                  icon: "success"
                });
                $("#crearExcusaModal").modal("hide");
                clearForm();
                tablaExcusas.api().ajax.reload(function () {});
              } else {
                Swal.fire({
                  title: "Error",
                  text: data.msg,
                  icon: "error"
                });
              }
            });
        });

        function clearForm() {
          txtArchivo.value = "";
          txtIdInasistencia.value = "0";
          txtIdInstructor.value = "0";
          txtIdUsuario.value = "0";
          txtIdExcusa.value = "0";
          txtObservacion.value = "";
        }

        function cargarTabla() {
          tablaExcusas = $("#tablaExcusas").dataTable({
            language: {
              url: `${base_url}/Assets/vendor/datatables/dataTables_es.json`
            },
            ajax: {
              url: " " + base_url + "/excusas/getExcusas",
              dataSrc: ""
            },
            columns: [
              { data: "fechaCompleta" },
              { data: "instructor.nombreIntru" },
              { data: "status" },
              { data: "action" }
            ],
            responsive: "true",
            iDisplayLength: 10,
            order: [[0, "asc"]]
          });
        }
      }
    } else {
      Swal.fire({
        title: "Error",
        text: data.msg,
        icon: "error"
      });
    }
  });
