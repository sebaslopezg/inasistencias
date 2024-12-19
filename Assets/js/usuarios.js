const btnCrearUsuario = document.querySelector('#btnCrearUsuario')
const frmCrearUsuario = document.querySelector('#frmCrearUsuario')
const btnSubmit = document.querySelector('#btnSubmit')

const frmNombre = document.querySelector('#txtNombre')
const frmApellido = document.querySelector('#txtApellido')
const frmDocumento = document.querySelector('#txtDocumento')
const frmTelefono = document.querySelector('#txtTelefono')
const frmGenero = document.querySelector('#genero')
const frmEmail = document.querySelector('#txtEmail')
const frmCodigo = document.querySelector('#txtCodigo')
const frmIdUsuario = document.querySelector('#idUsuario')
const frmUserStatus = document.querySelector('#userStatus')

let tablaUsuarios = document.querySelector('#tablaUsuarios')

loadTable()

document.addEventListener('click', (e)=>{
    try{
        let action = e.target.closest('button').getAttribute('data-action')
        let id = e.target.closest('button').getAttribute('data-id')

        if (action == 'delete') {
            Swal.fire({
                title:"Eliminar usuario",
                text:"¿Está seguro de eliminar el usuario?",
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

        if (action == 'edit') {
            fetch(base_url + '/usuarios/getUsariosById/'+id)
            .then((res) => res.json())
            .then((data) => {
                if (data.status) {
                    data = data.data
                    //console.log(data)
                    frmNombre.value = data.nombre
                    frmApellido.value = data.apellido
                    frmDocumento.value = data.documento
                    frmTelefono.value = data.telefono
                    frmGenero.value = data.genero
                    frmEmail.value = data.correo
                    frmCodigo.value = data.codigo
                    frmIdUsuario.value = data.id
                    frmUserStatus.value = data.status

                    frmDocumento.setAttribute('readonly','')
                    $('#crearUsuarioModal').modal('show')
                    optionStatus(true)
                }else{
                    Swal.fire({
                        title: "Error",
                        text: data.msg,
                        icon: "error"
                    });
                    tablaUsuarios.api().ajax.reload(function(){})
                }
            })
        }
    }catch{}
    
})

btnCrearUsuario.addEventListener('click', ()=>{
    clearForm()
    optionStatus(false)
    $('#crearUsuarioModal').modal('show')
})

frmCrearUsuario.addEventListener('submit', (e)=>{
    e.preventDefault()
    let frmUsuarios = new FormData(frmCrearUsuario)
    fetch(base_url + '/usuarios/setUsuario', {
        method:'POST',
        body:frmUsuarios
    })
    .then((res) => res.json())
    .then((data) =>{
        if (data.status) {
            Swal.fire({
                title: "Registro Usuarios",
                text: data.msg,
                icon: "success"
              });
              tablaUsuarios.api().ajax.reload(function(){})
              $('#crearUsuarioModal').modal('hide')
              clearForm()
        }else{
            Swal.fire({
                title: "Error",
                text: data.msg,
                icon: "error"
              });
        }
    })
})

function loadTable(){

    tablaUsuarios = $('#tablaUsuarios').dataTable({
        "language": {
            "url": `${base_url}/Assets/vendor/datatables/dataTables_es.json`
        },
        "ajax":{
            "url": " "+base_url+"/usuarios/getUsarios",
            "dataSrc":""
        },
        "columns":[
            {"data":"nombre_completo"},
            {"data":"documento"},
            {"data":"telefono"},
            {"data":"correo"},
            {"data":"rol"},
            {"data":"status"},
            {"data":"accion"},
        ],
        "responsive": "true",
        "iDisplayLength": 10,
        "order":[[0, "asc"]]
    }) 

    /*
    fetch(base_url + '/usuarios/getUsarios')
    .then((res) => res.json())
    .then((data) => {
        let html = ""
        data.forEach(row => {
            html += `
            <tr>
                <td>${row.nombre_completo}</td>
                <td>${row.documento}</td>
                <td>${row.telefono}</td>
                <td>${row.correo}</td>
                <td>${row.rol}</td>
                <td>${row.status}</td>
                <td>${row.accion}</td>
            </tr>
            `
        });

        let tabla = `
        
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Documento</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
        ${html}
        </tbody>
        
        `
        
        tablaUsuarios.innerHTML = tabla;
    }) */
}

function clearForm(){
    frmNombre.value= ""
    frmApellido.value= ""
    frmDocumento.value= ""
    frmTelefono.value= ""
    frmGenero.value= "0"
    frmEmail.value= ""
    frmCodigo.value= ""
    frmIdUsuario.value = "0"
    frmDocumento.removeAttribute('readonly')
}

function optionStatus(mode){
    let userStatus = document.getElementById('userStatusZone')

    if(mode) {
        userStatus.style.display = 'block'
    }else{
        userStatus.style.display = 'none'
    }
}