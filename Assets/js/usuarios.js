const btnCrearUsuario = document.querySelector('#btnCrearUsuario')
const frmCrearUsuario = document.querySelector('#frmCrearUsuario')
const btnSubmit = document.querySelector('#btnSubmit')

btnCrearUsuario.addEventListener('click', ()=>{
    $('#crearUsuarioModal').modal('show')
})

frmCrearUsuario.addEventListener('submit', (e)=>{
    e.preventDefault()
    console.log('asdsad')
    let frmUsuarios = new FormData(frmCrearUsuario)
    fetch(base_url + '/usuarios/setUsuario', {
        method:'POST',
        body:frmUsuarios
    })
    .then((res) => console.log(res))
})
