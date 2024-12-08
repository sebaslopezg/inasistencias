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

let tablaUsuarios = document.querySelector('#tablaUsuarios')

loadTable()

document.addEventListener('click', (e)=>{
    try{
        let action = e.target.closest('button').getAttribute('data-action')
        let id = e.target.closest('button').getAttribute('data-id')

        if (action == 'delete') {
            //elimino
        }

        if (action == 'edit') {
            fetch(base_url + '/usuarios/getUsariosById/'+id)
            .then((res) => res.json())
            .then((data) => {
                if (data.status) {
                    data = data.data
                    console.log(data)
                    frmNombre.value = data.nombre
                    frmApellido.value = data.apellido
                    frmDocumento.value = data.documento
                    frmTelefono.value = data.telefono
                    frmGenero.value = data.genero
                    frmEmail.value = data.correo
                    frmCodigo.value = data.codigo
                    frmIdUsuario.value = data.id
                    frmDocumento.setAttribute('readonly','')
                    $('#crearUsuarioModal').modal('show')
                }else{
                    Swal.fire({
                        title: "Error",
                        text: data.msg,
                        icon: "error"
                      });
                }
            })
        }
    }catch{}
    
})

btnCrearUsuario.addEventListener('click', ()=>{
    clearForm()
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
                title: "Usuario Registrado",
                text: data.msg,
                icon: "success"
              });
              loadTable()
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
    //tablaUsuarios.innerHTML = "";
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

    })
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