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
          fetch(base_url + "/fichas/deleteUsuario", {
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
              tablaUsuarios.api().ajax.reload(function () {});
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
            console.log(data);
            frmNombre.value = data.nombre;
            frmNumeroFicha.value = data.numeroFicha;
            frmIdFicha.value = data.id;
            frmUserStatus.value = data.status;
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
  let frmFicha = new FormData();
  frmFicha.append("txtNombre",frmNombre.value)
  frmFicha.append("txtNumeroFicha",frmNumeroFicha.value)
  frmFicha.append("txtIdFicha",frmUserStatus.value)
  frmFicha.append("userStatus",frmIdFicha.value)
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
        window.location.reload();
        $("#crearFichaModal").modal("hide");
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
