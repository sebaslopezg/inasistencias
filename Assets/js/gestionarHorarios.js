let tablaHorarios;
const hFecha = document.querySelector('#hFecha')
const hInstructor = document.querySelector('#hInstructor')
const hHoraInicio = document.querySelector('#hHoraInicio')
const hHoraFin = document.querySelector('#hHoraFin')
const hFicha = document.querySelector('#hFicha')

loadTableHorarios()

document.addEventListener('click', (e)=>{
    try{
        let action = e.target.closest('button').getAttribute('data-action')
        let id = e.target.closest('button').getAttribute('data-id')

        if (action == 'delete') {
            Swal.fire({
                title:"Eliminar Registro",
                text:"¿Está seguro de eliminar este registro del horario?",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: "Sí",
                denyButtonText: `Cancelar`
            }).then((result)=>{
                 if (result.isConfirmed) {
                    let frmData = new FormData()
                    frmData.append('idHorario', id)
                    fetch(base_url + '/horario/deleteHorario',{
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
                        tablaHorarios.api().ajax.reload(function(){})
                    })
                }
            })
        }

        if (action == 'edit') {
            fetch(base_url + '/horario/getHorarioById/'+id)
            .then((res) => res.json())
            .then((data) => {
                if (data.status) {
                    data = data.data
                    hFecha.value = data.fecha
                    hInstructor.value = data.nombre
                    hHoraInicio.value = data.horaInicio
                    hHoraFin.value = data.horaFin
                    hFicha.value = data.ficha
                    $('#horarioEditarModal').modal('show')
                }else{
                    Swal.fire({
                        title: "Error",
                        text: data.msg,
                        icon: "error"
                    });
                    tablaHorarios.api().ajax.reload(function(){})
                }
            })
        }
    }catch{}
    
})

 function loadTableHorarios(){
    tablaHorarios = $('#tablaHorarios').dataTable({
        "language": {
            "url": `${base_url}/Assets/vendor/datatables/dataTables_es.json`
        },
        "ajax":{
            "url": " "+base_url+"/horario/getHorarios",
            "dataSrc":""
        },
        "columns":[
            {"data":"fechaInicio"},
            {"data":"horaInicio"},
            {"data":"horaFin"},
            {"data":"ficha"},
            {"data":"nombre"},
            {"data":"accion"},
        ],
        "responsive": "true",
        "iDisplayLength": 10,
        "order":[[0, "asc"]]
    }) 
}