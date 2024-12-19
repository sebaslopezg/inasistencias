const btnCrearFicha = document.querySelector("#btnCrearFicha");
const frmCrearFicha = document.querySelector("#frmCrearFicha");
const infoFichaModal = document.querySelector("#infoFichaModal");
const btnSubmit = document.querySelector("#btnSubmit");
const frmNombre = document.querySelector("#txtNombre");
const frmNumeroFicha = document.querySelector("#txtNumeroFicha");
const frmIdFicha = document.querySelector("#txtIdFicha");
const frmUserStatus = document.querySelector("#userStatus");
const btnAcccionUsuario = document.querySelector("#btnAccion");
const btnAcccionUsuarioVolver = document.querySelector("#btnAccionVolver");
let tablaFichasView = document.querySelector("#tablaFichasView");
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
    iDisplayLength: 10,
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
    iDisplayLength: 10,
    order: [[0, "asc"]]
  });
}
// lodInfoFicha : Este metodo se encargar de trae la informacion de los Instructores  y Aprendices relacionados con la ficha para pintarla en lsas tablas.
function loadInfoFicha(id) {
  fetch(base_url + "/fichas/getInfoInstructoresFicha/" + id)
    .then((res) => res.json())
    .then((data) => {
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
  // frmDocumento.removeAttribute("readonly");
}

function optionStatus(mode) {
  let userStatus = document.getElementById("userStatusZone");

  if (mode) {
    userStatus.style.display = "block";
  } else {
    userStatus.style.display = "none";
  }
}

function SelecionFicha() {
  $(document).ready(function () {
    // -----------------------------------
    //    VERIFICAR Fichas DISPONIBLES
    // -----------------------------------

    // Se declara un array de productos disponibles llamado "availableProducts"
    let availableFichas = [];

    // -----------------------------------
    //    AUTOCOMPLETADO DE FICHAS
    // -----------------------------------

    // Se activad la funcionalidad de autocompletar en el campo con id "product"
    $("#ficha").autocomplete({
      // Establece como fuente de sugerencias el array "availableProducts", que contiene productos disponibles
      source: availableFichas,
      // event: Representa el evento JavaScript que desencadena la función select.
      // ui: Es un objeto de jQuery UI que contiene información específica sobre el elemento seleccionado en la lista de autocompletado.
      // Define la función que se ejecuta al seleccionar un producto de la lista de autocompletado
      select: function (event, ui) {
        // Llama a la función "agregarProducto" para añadir el producto seleccionado a la tabla de ventas.
        // Pasa el idFicha (ui.item.value), el nombre(ui.item.label), el numeroFicha (ui.item.precio), como argumentos.
        agregarFicha(ui.item.idFicha, ui.item.nombre, ui.item.numeroFicha);
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
      // Si el producto ya está en la tabla, muestra una alerta usando SweetAlert
      if (productoYaAgregado) {
        Swal.fire({
          icon: "warning",
          title: "Ficha ya agregado",
          text: "La Ficha ya está en la tabla de Fichas seleccionados."
        });
      } else {
        // Si el producto no está en la tabla, crea una nueva fila para agregar el producto
        let nuevaFila =
          `
        <tr>
            <td><input type="hidden" name="idFicha[]" value="` +
          idFicha +
          `">` +
          `</td>  
            <td><input type="text" name="" class="form-control" placeholder="` +
          nombre +
          `" ></td>
          <td><input type="text" name="" class="form-control" placeholder="` +
          numeroFicha +
          `" ></td>
          <td><button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="fas fa-trash"></i></button></td>
        </tr>
    `;
        // Agrega la nueva fila al final del tbody de la tabla de productos
        $("#tabla-Ficha tbody").append(nuevaFila);
      }
    }
    function agregarIstructor(idInstructor, nombre) {
      // Verificar si la Ficha ya está en la tabla
      let IsntruYaAgregado = false;
      // Itera sobre cada fila de la tabla para comprobar si el ID del producto ya existe
      $("#tabla-BusqInstru tbody tr").each(function () {
        if ($(this).find("input[name='idInstructor[]']").val() == idInstructor) {
          // Si encuentra una coincidencia de ID, marca productoYaAgregado como true
          IsntruYaAgregado = true;
          // Sale del ciclo
          return false;
        }
      });
      // Si el producto ya está en la tabla, muestra una alerta usando SweetAlert
      if (IsntruYaAgregado) {
        Swal.fire({
          icon: "warning",
          title: "Isntructor  ya agregado",
          text: "El Isntructor ya está en la tabla de Isntructores seleccionados."
        });
      } else {
        // Si el producto no está en la tabla, crea una nueva fila para agregar el producto
        let nuevaFila =
          `
        <tr>
          <td>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="` +
          idInstructor +
          `" name="selecInstru[]" id="selecInstru" checked />                                   
              <label class="form-check-label" for="flexCheckChecked">                                                      
                 <p>` +
          nombre +
          `</p>
            </label>
           </div>
          </td>
        </tr>
    `;
        // Agrega la nueva fila al final del tbody de la tabla de productos
        $("#tabla-BusqInstru tbody").append(nuevaFila);
      }
    }
    // Asigna un evento "blur" a los campo de Busqueda de cliente
    $(document).on("blur", 'input[name="search_Ficha"]', function () {
      // Traemos el input del HTML, para deshabilitarlo una vez pierda el foco.
      let txtSearchClient = document.querySelector("#ficha");
      txtSearchClient.disabled = true;
    });
    $(document).on("click", ".eliminar-fila", function () {
      $(this).closest("tr").remove();
      /*  $("#total-pagar").val("");
      $("#cantidad").focus(); */
    });
    // Maneja el evento de envío del formulario de venta
    $("#form-Ficha").on("submit", function (e) {
      // Prevenir el envío estándar del formulario
      e.preventDefault();
      // Contar las filas de la tabla de productos
      let filas = $("#tabla-Ficha tbody tr").length;
      if (filas === 0) {
        // Si no hay productos en la tabla, mostrar una alerta
        Swal.fire({
          icon: "warning",
          title: "Sin productos",
          text: "Por favor, agregue al menos una ficha a la venta."
        });
        return false;
      }

      // Crear un objeto FormData para recolectar los datos del formulario
      let formData = new FormData();

      // Iterar sobre cada fila de la tabla de productos para recolectar datos
      $("#tabla-Ficha tbody tr").each(function () {
        let idFicha = $(this).find("input[name='idFicha[]']").val();
        formData.append("txtIdFicha", idFicha);
      });

      $("#tabla-BusqInstru tbody tr").each(function () {
        let idInstructor = $(this).find("input[name='selecInstru[]']").val();
        formData.append("txtIdInstructor", idInstructors);
      });

      // Enviar los datos al servidor utilizando AJAX
      $.ajax({
        // Método de envío
        type: "POST",
        // URL del script de PHP que procesará la venta
        url: " " + base_url + "/fichas/setIsntructor",
        data: formData,
        // No procesar los datos (ya se usa FormData)
        processData: false,
        // No establecer el tipo de contenido (ya se establece con FormData)
        contentType: false,
        success: function (respuesta) {
          // Si la venta se crea correctamente, mostrar una alerta de éxito
          Swal.fire({
            title: "¡Instructor Asiganado Correctamente!",
            icon: "success",
            // Duración de la alerta en milisegundos
            timer: 2000,
            // No mostrar botón de confirmación
            showConfirmButton: false
          }).then(() => {
            // Redirigir a la lista de ventas después de la alerta
            window.location.href = "" + base_url + "/fichas/getFichasPreview";
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
}
