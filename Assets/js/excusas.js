let tablaExcusas = document.querySelector("#tablaExcusas");
let frmCrearExcusa = document.querySelector("#frmCrearExcusa");
let txtIdExcusa = document.querySelector("#txtIdExcusa");
let txtIdInasistencia = document.querySelector("#txtIdInasistencia");
let txtIdUsuario = document.querySelector("#txtIdUsuario");
let txtIdInstructor = document.querySelector("#txtIdInstructor");
let txtArchivo = document.querySelector("#txtArchivo");
let txtEstado = document.querySelector("#txtEstado");
let rol;

fetch(base_url + "/excusas/getUsuarioById")
  .then((res) => res.json())
  .then((data) => {
    if (data.status) {
      data = data.data;
      rol = data[0].rol;

      if (rol === "INSTRUCTOR") {
        let thead = document.querySelector("#tablaExcusas thead tr");

        thead.children[0].textContent = "Fecha Excusa";
        thead.children[2].textContent = "Ficha";
        thead.children[3].textContent = "NumeroFicha";
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
          } catch {}
        });
      } else if (rol === "APRENDIZ") {
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

            if (action == "delete") {
              console.log(id);
              Swal.fire({
                title: "Eliminar Excusa",
                text: "¿Está seguro de eliminar la excusa?",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: "Sí",
                denyButtonText: `Cancelar`
              }).then((result) => {
                if (result.isConfirmed) {
                  console.log(id);
                  let frmData = new FormData();
                  frmData.append("txtIdExcusa", id);
                  fetch(base_url + "/excusas/deleteExcusas", {
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

            if (action == "editar") {
              fetch(base_url + "/excusas/selectExcusaId/" + id)
                .then((res) => res.json())
                .then((data) => {
                  if (data.status) {
                    data = data.data;
                    txtIdInasistencia.value = data.inasistencias_idInasistencias;
                    txtIdInstructor.value = data.idInstructor;
                    txtIdUsuario.value = data.usuario_idUsuarios;
                    (txtIdExcusa.value = data.idExcusas), console.log(id);
                    /*  txtArchivo.value = data.uriArchivo   */
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
                  title: "Registro Usuarios",
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
              { data: "nombreCompleto" },
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
