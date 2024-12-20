let tablaHorarios;

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
            fetch(base_url + '/usuarios/getUsariosById/'+id)
            .then((res) => res.json())
            .then((data) => {
                if (data.status) {
                    data = data.data
                    //console.log(data)
                    $('#crearUsuarioModal').modal('show')
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