let tablaExcusas = document.querySelector("#tablaExcusas");
let frmCrearExcusa = document.querySelector("#frmCrearExcusa");
let txtIdExcusa = document.querySelector("#txtIdExcusa");
let txtIdInasistencia = document.querySelector("#txtIdInasistencia");
let txtIdUsuario = document.querySelector("#txtIdUsuario");
let txtIdInstructor = document.querySelector("#txtIdInstructor");
let txtArchivo = document.querySelector("#txtArchivo");
let txtEstado = document.querySelector("#txtEstado")

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

    if (action == 'delete') {
      Swal.fire({
          title:"Eliminar Excusa",
          text:"¿Está seguro de eliminar la excusa?",
          icon: "warning",
          showDenyButton: true,
          confirmButtonText: "Sí",
          denyButtonText: `Cancelar`
      }).then((result)=>{
           if (result.isConfirmed) {
              let frmData = new FormData()
              frmData.append('idUsuario', id)
              fetch(base_url + '/usuarios/deleteUsuario',{
                  method: "POST",
                  body: frmData,
              })
              .then((res)=>res.json())
              .then((data)=>{
                  Swal.fire({
                      title: data.status ? 'Correcto' : 'Error',
                      text: data.msg,
                      icon: data.status ? "success" : 'error'
                  })
                  tablaUsuarios.api().ajax.reload(function(){})
              })
          } 
      })
  }
  } catch {}
});


frmCrearExcusa.addEventListener("submit", (e)=>{
    e.preventDefault()
    let frmExcusa = new FormData(frmCrearExcusa)
    fetch(base_url + '/excusas/setExcusas',{
      method:'POST',
      body:frmExcusa
    })
    .then((res) => res.json())
    .then((data) =>{
      console.log(data)
      if (data.status) {
        Swal.fire({
          title: "Registro Usuarios",
          text: data.msg,
          icon: "success"
        });
        cargarTabla()
        $("#crearExcusaModal").modal("hide");
      }else{
        Swal.fire({
          title: "Error",
          text: data.msg,
          icon: "error"
        });
      }
    })  
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
