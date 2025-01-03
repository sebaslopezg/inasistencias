const btnCrearFicha = document.querySelector("#btnCrearFicha");
const frmCrearFicha = document.querySelector("#frmCrearFicha");
const infoFichaModal = document.querySelector("#infoFichaModal");
const btnSubmit = document.querySelector("#btnSubmit");
const frmNombre = document.querySelector("#txtNombre");
const frmNumeroFicha = document.querySelector("#txtNumeroFicha");
const frmIdFicha = document.querySelector("#txtIdFicha");
const tituloModel = document.querySelector("#tituloModal");
const frmUserStatus = document.querySelector("#userStatus");
const btnAcccionUsuario = document.querySelector("#btnAccion");
const btnAcccionUsuarioVolver = document.querySelector("#btnAccionVolver");
let tablaFichasView = document.querySelector("#tablaFichasView");

let btnCerrarModal = document.getElementById("btnCerrarModal");
let tablaFicha = document.querySelector("#tablaFichas");
let tablaInfoInstructor = document.querySelector("#tablaInfoInstructor");
let tablaInfoAprendiz = document.querySelector("#tablaInfoAprendiz");
let cardTablaInfo = document.querySelector("#cardTabla");
let cardTablaEditar = document.querySelector("#cardTablaEditar");
loadTableView();

document.addEventListener("click", (e) => {
  try {
    let action = e.target.closest("button").getAttribute("data-action");
    let id = e.target.closest("button").getAttribute("data-id");
    if (action == "delete") {
      Swal.fire({
        title: "Eliminar Ficha",
        text: "¿Está seguro de eliminar la ficha?",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: "Sí",
        denyButtonText: `Cancelar`
      }).then((result) => {
        if (result.isConfirmed) {
          let frmData = new FormData();
          frmData.append("idFicha", id);
          fetch(base_url + "/fichas/deleteFicha", {
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
              tablaFicha.api().ajax.reload(function () {});
            });
        }
      });
    }

    if (action == "edit") {
      fetch(base_url + "/fichas/getFichaById/" + id)
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            data = data.data;
            frmNumeroFicha.setAttribute("readonly", "");
            frmNombre.value = data.nombre;
            frmNumeroFicha.value = data.numeroFicha;
            frmIdFicha.value = data.id;
            $("#crearFichaModal").modal("show");
            optionStatus(true);
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error"
            });
            tablaFicha.api().ajax.reload(function () {});
          }
        });
    }
    if (action == "info") {
      loadInfoFicha(id);
    }
  } catch {}
});

btnCrearFicha.addEventListener("click", () => {
  frmNumeroFicha.disabled = false;
  clearForm();
  optionStatus(false);
  $("#crearFichaModal").modal("show");
});

// btnAccion : Este boton se encarga de Mandar Al Usuario a la parte de Gestion de Fichas, donde pude crear Fichas, Modificarlas Entr otras funcines..
btnAcccionUsuario.addEventListener("click", () => {
  cardTablaInfo.style.display = "none";
  cardTablaEditar.style.display = "Block";
  btnCrearFicha.style.display = "";
  btnAcccionUsuario.style.display = "none";
  btnAcccionUsuarioVolver.style.display = "";
});

// btnAccionVoler : Este boton se encarga de darle la opcion al usuario de retroceder a la parte de ver la informacion de las fichas.
btnAcccionUsuarioVolver.addEventListener("click", () => {
  cardTablaInfo.style.display = "";
  cardTablaEditar.style.display = "none";
  btnCrearFicha.style.display = "none";
  btnAcccionUsuario.style.display = "";
  btnAcccionUsuarioVolver.style.display = "none";
});

//btnCerraModal : Este boton se encarga de limpiar la tabla de informacion de la fichas.
btnCerrarModal.addEventListener("click", () => {
  console.log("click");
  tablaInfoInstructor.innerHTML = "";
  tablaInfoAprendiz.innerHTML = "";
});
/*
$(document).on("blur", "#infoFichaModal", function () {
  console.log("perdio foco");
  tablaInfoInstructor.innerHTML = "";
  tablaInfoAprendiz.innerHTML = "";
});*/
frmCrearFicha.addEventListener("submit", (e) => {
  e.preventDefault();
  let frmFicha = new FormData(frmCrearFicha);
  fetch(base_url + "/fichas/setFicha", {
    method: "POST",
    body: frmFicha
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status) {
        Swal.fire({
          title: "Registro Ficha",
          text: data.msg,
          icon: "success"
        });
        $("#crearFichaModal").modal("hide");
        tablaFicha.api().ajax.reload(function () {});
        clearForm();
      } else {
        Swal.fire({
          title: "Error",
          text: data.msg,
          icon: "error"
        });
      }
    });
});

function loadTableView() {
  // Cargamos las fichas solamente para mostar informacion de cada Una.
  tablaFichasView = $("#tablaFichasView").dataTable({
    language: {
      url: `${base_url}/Assets/vendor/datatables/dataTables_es.json`
    },
    ajax: {
      url: " " + base_url + "/fichas/getFichasPreview",
      dataSrc: ""
    },
    columns: [{ data: "nombre" }, { data: "numeroFicha" }, { data: "status" }, { data: "accion" }],
    responsive: "true",
    iDisplayLength: 6,
    order: [[0, "asc"]]
  });

  // Cargamos las fichas en la tabla con las acciones
  tablaFicha = $("#tablaFichas").dataTable({
    language: {
      url: `${base_url}/Assets/vendor/datatables/dataTables_es.json`
    },
    ajax: {
      url: " " + base_url + "/fichas/getFichas",
      dataSrc: ""
    },
    columns: [{ data: "nombre" }, { data: "numeroFicha" }, { data: "status" }, { data: "accion" }],
    responsive: "true",
    iDisplayLength: 6,
    order: [[0, "asc"]]
  });
}

// lodInfoFicha : Este metodo se encargar de trae la informacion de los Instructores  y Aprendices relacionados con la ficha para pintarla en lsas tablas.
function loadInfoFicha(id) {
  fetch(base_url + "/fichas/getInfoInstructoresFicha/" + id)
    .then((res) => res.json())
    .then((data) => {
      data.forEach((data) => {
        let titulo = `<h2 class="modal-title fs-5" id="exampleModalLabel" style="text-align: center; display: flex;">INFORMACION DE LA FICHA -  ${data.nombre_ficha}</h2>
        `;
        tituloModel.innerHTML = titulo;
      });

      data.forEach((data) => {
        let texto = ` <tr><td>${data.nombre_completo}</td><td>${data.correo}</td></tr>  `;
        tablaInfoInstructor.innerHTML += texto;
      });
    });

  fetch(base_url + "/fichas/getInfoAprendicesFicha/" + id)
    .then((res) => res.json())
    .then((data) => {
      data.forEach((data) => {
        let texto = ` <tr><td>${data.nombre_completo}</td><td>${data.correo}</td></tr>  `;
        tablaInfoAprendiz.innerHTML += texto;
      });
    });

  $("#infoFichaModal").modal("show");
}
function clearForm() {
  frmNombre.value = "";
  frmNumeroFicha.value = "";
  frmIdFicha.value = "0";
}

function optionStatus(mode) {
  let userStatus = document.getElementById("userStatusZone");

  if (mode) {
    userStatus.style.display = "block";
  } else {
    userStatus.style.display = "none";
  }
}

$(document).ready(function () {
  // -----------------------------------
  //    VERIFICAR Fichas DISPONIBLES
  // -----------------------------------
  let availableFichas = [];
  fetch(base_url + "/fichas/getFichas")
    .then((res) => res.json())
    .then((data) => {
      data.forEach((data) => {
        let fila = {
          label: "" + data.nombre + " - " + data.numeroFicha,
          value: "" + data.id + "",
          numeroFicha: "" + data.numeroFicha + ""
        };
        availableFichas.push(fila);
      });
    });

  // -----------------------------------
  //    AUTOCOMPLETADO DE FICHAS
  // -----------------------------------

  $("#ficha").autocomplete({
    // Establece como fuente de sugerencias el array "availableFichas", que contiene productos disponibles
    source: availableFichas,
    // event: Representa el evento JavaScript que desencadena la función select.
    // ui: Es un objeto de jQuery UI que contiene información específica sobre el elemento seleccionado en la lista de autocompletado.
    // Define la función que se ejecuta al seleccionar un producto de la lista de autocompletado
    select: function (event, ui) {
      // Pasa el idFicha (ui.item.value), el nombre(ui.item.label), el numeroFicha (ui.item.precio), como argumentos.
      agregarFicha(ui.item.value, ui.item.label, ui.item.numeroFicha);
      // Limpia el campo de entrada después de que se haya seleccionado un Ficha
      $("#ficha").val("");
      return false;
    }
  });

  // -----------------------------------
  //    AGREGAR FICHA A TABLA
  // -----------------------------------

  function agregarFicha(idFicha, nombre, numeroFicha) {
    // Verificar si la Ficha ya está en la tabla
    let fichaYaAgregado = false;
    // Itera sobre cada fila de la tabla para comprobar si el ID del producto ya existe
    $("#tabla-Ficha tbody tr").each(function () {
      if ($(this).find("input[name='idFicha[]']").val() == idFicha) {
        // Si encuentra una coincidencia de ID, marca productoYaAgregado como true
        fichaYaAgregado = true;
        // Sale del ciclo
        return false;
      }
    });
    // Si la fihca ya está en la tabla, muestra una alerta usando SweetAlert
    if (fichaYaAgregado) {
      Swal.fire({
        icon: "warning",
        title: "Ficha ya agregada ",
        text: "La Ficha ya está en la tabla de Fichas seleccionados."
      });
    } else {
      // traemos los instructores disponibles para asignarlos a la ficha Seleccionada.

      let nuevaFila =
        `
        <tr>
            <input type="hidden" name="idFicha[]" value="` +
        idFicha +
        `">
            <td><div class="alert alert-secondary" role="alert">  ` +
        nombre +
        `</div> 
            <td>
              <div class="accordion" id="accordionExample">
              <div class="accordion-item">
              <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><span class="badge rounded-pill bg-success" style="text-align:center;font-size:medium;">Personal</span></button></h2>
               <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
               <div class="accordion-body" id="mostrarInstr" >
         
               </div>
               </div>
               </div>
               </div>
               </div>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="bi bi-x-circle-fill"></i></button></td>
        </tr>
        `;

      fetch(base_url + "/fichas/getInstDisponibles/" + idFicha + "")
        .then((res) => res.json())
        .then((data) => {
          let mostrarCheck = document.getElementById("mostrarInstr");
          data.forEach((data) => {
            let fila = `
            <div class="form-check">
            ${data.checkBox}
            <label class="form-check-label" for="flexCheckIndeterminate"> ${data.nombre_completo}</label>
            </div>
            `;
            mostrarCheck.innerHTML += fila;
          });
        });
      $("#tabla-Ficha tbody").append(nuevaFila);
    }
  }
  // Asigna un evento "blur" a los campo de Busqueda de cliente
  $(document).on("blur", 'input[name="search_Ficha"]', function () {
    // Traemos el input del HTML, para deshabilitarlo una vez pierda el foco.
    /*   let txtSearchClient = document.querySelector("#ficha");
    txtSearchClient.disabled = true; */
  });
  $(document).on("click", ".eliminar-fila", function () {
    $(this).closest("tr").remove();
  });

  // Traemos los id de los Instructores por medio del evento click
  let idCapturadas = "";
  $(document).on("click", ".instruCheck", function () {
    let arrIdInstru = $('[name="selecInstru[]"]:checked')
      .map(function () {
        return this.value;
      })
      .get();
    idCapturadas = arrIdInstru.join(",");
  });

  // Maneja el evento de envío del formulario de asigancion de la ficha.
  $("#form-Ficha").on("submit", function (e) {
    // Prevenir el envío estándar del formulario
    e.preventDefault();
    let filas = $("#tabla-Ficha tbody tr").length;

    if (filas === 0) {
      Swal.fire({
        icon: "warning",
        title: "Sin productos",
        text: "Por favor, agregue al menos una ficha."
      });
      return false;
    }
    // Crear un objeto FormData para recolectar los datos del formulario
    let formData = new FormData();
    formData.append("txtIdInstructor", idCapturadas);
    formData.append("accion", "insert");

    $("#tabla-Ficha tbody tr").each(function () {
      let idFicha = $(this).find("input[name='idFicha[]']").val();
      formData.append("txtIdFicha", idFicha);
    });

    // console.log(formData.get("txtIdInstructores"));

    $.ajax({
      // Método de envío
      type: "POST",
      // URL del script de PHP que procesará la venta
      url: " " + base_url + "/fichas/setInstructor",
      data: formData,
      // No procesar los datos (ya se usa FormData)
      processData: false,
      // No establecer el tipo de contenido (ya se establece con FormData)
      contentType: false,
      success: function (respuesta) {
        Swal.fire({
          title: "¡Instructor Asiganado Correctamente!",
          icon: "success",
          // Duración de la alerta en milisegundos
          timer: 2000,
          // No mostrar botón de confirmación
          showConfirmButton: false
        }).then(() => {
          // Redirigir a la lista de ventas después de la alerta
          /*    window.location.href = "" + base_url + "/fichas"; */
        });
      },
      error: function (xhr, status, error) {
        // Manejar errores en la solicitud AJAX
        console.error("Error en la solicitud AJAX:", error);
      }
    });

    // Evita el comportamiento por defecto del formulario
    return false;
  });
});
