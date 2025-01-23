let tablaExcepciones = document.querySelector("#tblExcepciones");
let btnCrearExcepcion = document.querySelector("#btnCrearExcepcion");
let fichaOpciones = document.querySelector(".ficha");
let slcts = document.querySelector("#slcts");
let divFicha = document.querySelector("#divFicha");
let divAprendiz = document.querySelector("#divAprendiz");
let AprendizOpciones = document.querySelector(".aprendiz");

let frmCrearExcepcion = document.querySelector("#frmCrearExcepcion");
let selectExepcion = document.querySelector("#selectExepcion");
let Aprendiz = document.querySelector("#aprendiz");
let txtFicha = document.querySelector("#ficha");
let txtFechaInicio = document.querySelector("#txtFechaInicio");
let txtFechaFin = document.querySelector("#txtFechaFin");
let txtMotivo = document.querySelector("#txtMotivo");
let txtExcepcionId = document.querySelector("#txtExcepcionId");

cargarTabla();
function cargarTabla() {
  tablaExcepciones = $("#tblExcepciones").dataTable({
    language: {
      url: `${base_url}/Assets/vendor/datatables/dataTables_es.json`
    },
    ajax: {
      url: " " + base_url + "/excepciones/getExcepciones",
      dataSrc: ""
    },
    columns: [
      { data: "descripcion" },
      { data: "fechaInicio" },
      { data: "fechaFin" },
      { data: "nombreUsu" },
      { data: "nombreFicha" },
      { data: "tipoExcepcion" },
      { data: "status" },
      { data: "action" }
    ],
    responsive: "true",
    iDisplayLength: 10,
    order: [[0, "asc"]]
  });
}

btnCrearExcepcion.addEventListener("click", () => {
  clearForm();
  mostrarInputs();
  
  $("#crearExcepcionModal").modal("show");
});

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
                icon: data.status ? "success" : "error"
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
            txtFechaFin.value = data.fechaFin;
            txtFechaInicio.value = data.fechaInicio;
            txtMotivo.value = data.descripcion;

            if (data.tipoExcepcion === 1) {
              await cargarFicha();
              txtFicha.value = data.fichaId;
              await mostrarAprendices();
              $("#crearExcepcionModal").modal("show");
              slcts.style.display = "flex";
              mostrarInputs();
              Aprendiz.value = data.usuarioId;
            } else if (data.tipoExcepcion === 2) {
              await cargarFicha();
              txtFicha.value = data.fichaId;
              $("#crearExcepcionModal").modal("show");
              slcts.style.display = "flex";
              mostrarInputs();
            } else if (data.tipoExcepcion === 3) {
              $("#crearExcepcionModal").modal("show");
              mostrarInputs();
            }
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error"
            });
            tablaExcepciones.api().ajax.reload(function () {});
          }
        });
    }
  } catch {}

  function clearForm() {
    selectExepcion.value = "";
    /*   Aprendiz.value = '<option value="null" disabled selected="">Seleccione el Aprendiz</option>';
    ficha.value = '<option value="null" disabled selected="">Seleccione la Ficha</option>'; */
    txtFechaInicio.value = "";
    txtFechaFin.value = "";
    txtMotivo.value = "";
    txtExcepcionId.value = 0;
    divFicha.classList.remove("col-12", "mb-2");
    divFicha.classList.add("col-6", "mb-2");
  }
});

async function cargarFicha() {
  fichaOpciones.innerHTML =
    '<option value="null" disabled selected="">Seleccione la Ficha</option>';
  let response = await fetch(base_url + "/excepciones/getFicha");
  let data = await response.json();

  data.map((ficha) => {
    let opciones = `<option value="${ficha.idFicha}">${ficha.nombre} - ${ficha.numeroFicha}</option>`;
    fichaOpciones.innerHTML += opciones;
  });
}
cargarFicha();

async function mostrarAprendices() {
  AprendizOpciones.innerHTML =
    '<option value="null" disabled selected="">Seleccione el Aprendiz</option>';

  let response = await fetch(base_url + "/excepciones/selectAprendisById/" + txtFicha.value);
  let data = await response.json();

  data = data.data;
  data.map((Aprendiz) => {
    let opcionesApre = `<option value="${Aprendiz.usuario_idUsuarios}">${Aprendiz.nombre} - ${Aprendiz.documento}</option>`;
    AprendizOpciones.innerHTML += opcionesApre;
  });
}
txtFicha.addEventListener("change", mostrarAprendices);

function mostrarInputs() {
  slcts.style.display = "none";
  divFicha.style.display = "none";
  divAprendiz.style.display = "none";

  if (selectExepcion.value == 1) {
    slcts.style.display = "flex";
    divFicha.style.display = "block";
    Aprendiz.setAttribute("required", "true");
    ficha.setAttribute("required", "true");
    divAprendiz.style.display = "block";
    divFicha.classList.remove("col-12", "mb-2");
    divFicha.classList.add("col-6", "mb-2");
  } else if (selectExepcion.value == 2) {
    slcts.style.display = "flex";
    divFicha.style.display = "block";
    ficha.setAttribute("required", "true");
    divFicha.classList.remove("col-6", "mb-2");
    divFicha.classList.add("col-12", "mb-2");
  }
}
selectExepcion.addEventListener("change", mostrarInputs);

function clearForm() {
  selectExepcion.value = "";
  /*   Aprendiz.value = '<option value="null" disabled selected="">Seleccione el Aprendiz</option>';
  ficha.value = '<option value="null" disabled selected="">Seleccione la Ficha</option>'; */
  txtFechaInicio.value = "";
  txtFechaFin.value = "";
  txtMotivo.value = "";
  txtExcepcionId.value = 0;
}

frmCrearExcepcion.addEventListener("submit", (e) => {
  e.preventDefault();
  let frmExcepcion = new FormData(frmCrearExcepcion);
  fetch(base_url + "/excepciones/setExcepciones", {
    method: "POST",
    body: frmExcepcion
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status) {
        Swal.fire({
          title: "Registro Excepcion",
          text: data.msg,
          icon: "success"
        });
        tablaExcepciones.api().ajax.reload(function () {});
        $("#crearExcepcionModal").modal("hide");
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
