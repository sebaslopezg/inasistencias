let tablaExcusas = document.querySelector("#tablaExcusas");
let frmCrearExcusa = document.querySelector("#frmCrearExcusa");
let txtIdExcusa = document.querySelector("#txtIdExcusa");
let txtIdInasistencia = document.querySelector("#txtIdInasistencia");
let txtIdUsuario = document.querySelector("#txtIdUsuario");
let txtIdInstructor = document.querySelector("#txtIdInstructor");
let txtArchivo = document.querySelector("#txtArchivo");

document.addEventListener("click", (e) => {
  try {
    let id = e.target.closest("button").getAttribute("data-id");
    let action = e.target.closest("button").getAttribute("data-action");
    console.log(id);
    if (action == "agregar") {
      fetch(base_url + "/excusas/getInasistenciaById/" + id)
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            console.log(data);
            data = data.data;
            txtIdInasistencia.value = data.idIna;
            txtIdInstructor.value = data.idInstructor;
            txtIdUsuario.value = data.idUsu;

            $("#crearExcusaModal").modal("show");
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error"
            });
            tablaExcusas.api().ajax.reload(function () {});
          }
        });
    }
  } catch {}
});

frmCrearExcusa.addEventListener("submit", (e)=>{
    e.preventDefault()
    let frmExcusa = new FormData(frmCrearExcusa)
    fetch(base_url + '')
});

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
